<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Auth\Events\Registered;

/**
 * @OA\Tag(
 *     name="Autenticación",
 *     description="API Endpoints para la gestión de autenticación"
 * )
 */
class AuthController extends BaseController
{
    public function __construct()
    {
        // Quitamos el middleware web para el registro
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     operationId="login",
     *     summary="Iniciar sesión",
     *     description="Autentica a un usuario y devuelve un token de acceso",
     *     tags={"Autenticación"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="usuario@ejemplo.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login exitoso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="idUsu", type="integer", example=1),
     *                 @OA\Property(property="nombre", type="string", example="Juan"),
     *                 @OA\Property(property="apellido", type="string", example="Pérez"),
     *                 @OA\Property(property="email", type="string", example="usuario@ejemplo.com"),
     *                 @OA\Property(property="admin", type="boolean", example=false)
     *             ),
     *             @OA\Property(property="token", type="string", example="1|abcdef123456..."),
     *             @OA\Property(property="message", type="string", example="Login exitoso")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciales inválidas",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Credenciales inválidas")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Error de validación"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     type="array",
     *                     @OA\Items(type="string", example="El campo email es obligatorio")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        try {
            Log::info('Intento de login', ['email' => $request->email]);

            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            $credentials = $request->only('email', 'password');
            
            if (!Auth::attempt($credentials)) {
                Log::warning('Credenciales inválidas', ['email' => $request->email]);
                return response()->json([
                    'message' => 'Credenciales inválidas'
                ], 401);
            }

            $user = Auth::user();
            
            // Si es una petición API (desde el frontend)
            if ($request->expectsJson()) {
                // Revocar tokens anteriores
                $user->tokens()->delete();
                // Crear nuevo token
                $token = $user->createToken('auth-token')->plainTextToken;
                
                Log::info('Login API exitoso', ['user_id' => $user->idUsu]);

                return response()->json([
                    'user' => $user,
                    'token' => $token,
                    'message' => 'Login exitoso'
                ]);
            }
            
            // Si es una petición web (desde el backend)
            Log::info('Login web exitoso', ['user_id' => $user->idUsu]);
            
            if ($user->admin) {
                return redirect()->route('dashboard');
            }
            
            return redirect()->route('home');

        } catch (ValidationException $e) {
            Log::error('Error de validación en login', [
                'errors' => $e->errors(),
                'email' => $request->email
            ]);
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error en login', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     operationId="logout",
     *     summary="Cerrar sesión",
     *     description="Cierra la sesión del usuario y revoca el token de acceso",
     *     tags={"Autenticación"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout exitoso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Logout exitoso")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al cerrar sesión",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Error al cerrar sesión")
     *         )
     *     )
     * )
     */
    public function logout(Request $request)
    {
        try {
            // Si es una petición API
            if ($request->expectsJson()) {
                $request->user()->currentAccessToken()->delete();
            } else {
                // Si es una petición web
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }
            
            return response()->json([
                'message' => 'Logout exitoso'
            ]);
        } catch (\Exception $e) {
            Log::error('Error en logout', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'message' => 'Error al cerrar sesión'
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/user",
     *     operationId="getUser",
     *     summary="Obtener usuario actual",
     *     description="Devuelve la información del usuario autenticado",
     *     tags={"Autenticación"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Información del usuario",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="idUsu", type="integer", example=1),
     *             @OA\Property(property="nombre", type="string", example="Juan"),
     *             @OA\Property(property="apellido", type="string", example="Pérez"),
     *             @OA\Property(property="email", type="string", example="usuario@ejemplo.com"),
     *             @OA\Property(property="admin", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="No autenticado")
     *         )
     *     )
     * )
     */
    public function user(Request $request)
    {
        try {
            if (!Auth::guard('web')->check()) {
                return response()->json([
                    'message' => 'No autenticado'
                ], 401);
            }

            return response()->json(Auth::guard('web')->user());
        } catch (\Exception $e) {
            Log::error('Error al obtener usuario', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'message' => 'Error al obtener usuario'
            ], 500);
        }
    }

    public function handleAdminRedirect(Request $request)
    {
        $token = $request->query('token');
        
        if (!$token) {
            return redirect()->route('login');
        }

        $user = \Laravel\Sanctum\PersonalAccessToken::findToken($token);
        
        if (!$user) {
            return redirect()->route('login');
        }

        $user = $user->tokenable;

        if (!$user || !$user->admin) {
            return redirect()->route('login');
        }

        Auth::login($user);
        
        return redirect()->route('establecimiento.listado');
    }

    /**
     * @OA\Post(
     *     path="/api/register",
     *     operationId="register",
     *     summary="Registrar nuevo usuario",
     *     description="Crea un nuevo usuario y devuelve un token de acceso",
     *     tags={"Autenticación"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre", "apellido", "email", "password", "password_confirmation"},
     *             @OA\Property(property="nombre", type="string", maxLength=255, example="Juan"),
     *             @OA\Property(property="apellido", type="string", maxLength=255, example="Pérez"),
     *             @OA\Property(property="email", type="string", format="email", maxLength=255, example="usuario@ejemplo.com"),
     *             @OA\Property(property="password", type="string", format="password", minLength=8, example="password123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", minLength=8, example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuario registrado exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Usuario registrado exitosamente"),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="idUsu", type="integer", example=1),
     *                 @OA\Property(property="nombre", type="string", example="Juan"),
     *                 @OA\Property(property="apellido", type="string", example="Pérez"),
     *                 @OA\Property(property="email", type="string", example="usuario@ejemplo.com"),
     *                 @OA\Property(property="admin", type="boolean", example=false)
     *             ),
     *             @OA\Property(property="token", type="string", example="1|abcdef123456...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Error de validación"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     type="array",
     *                     @OA\Items(type="string", example="El email ya está registrado")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function register(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:255',
                'apellido' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:usuario',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $usuario = Usuario::create([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'estado' => true,
                'admin' => false
            ]);

            event(new Registered($usuario));

            Auth::login($usuario);

            $token = $usuario->createToken('auth-token')->plainTextToken;

            return response()->json([
                'message' => 'Usuario registrado exitosamente',
                'user' => $usuario,
                'token' => $token
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error en registro', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'message' => 'Error al registrar usuario'
            ], 500);
        }
    }
}
