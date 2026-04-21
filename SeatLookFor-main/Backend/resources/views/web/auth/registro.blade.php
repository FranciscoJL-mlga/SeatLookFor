@extends('web.layouts.app')

@section('title', 'Registro - SeatLookFor')

@section('content')

<div class="auth-container">

    <a href="{{ route('home') }}" class="home-button">
        ← Inicio
    </a>

    <div class="auth-card">

        {{-- Brand --}}
        <div style="text-align:center;margin-bottom:8px;">
            <span style="font-family:'Poppins',sans-serif;font-size:13px;font-weight:600;
                         text-transform:uppercase;letter-spacing:3px;color:var(--text-dim);">
                SeatLookFor
            </span>
        </div>

        <h2>Crear cuenta</h2>

        {{-- Validation errors --}}
        @if($errors->any())
            <div class="error-message"
                 style="margin-bottom:1.25rem;padding:12px 16px;background:rgba(239,68,68,0.1);
                        border:1px solid rgba(239,68,68,0.25);border-radius:8px;">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('registro.store') }}" class="auth-form">
            @csrf

            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text"
                       id="nombre"
                       name="nombre"
                       value="{{ old('nombre') }}"
                       required
                       autofocus
                       placeholder="Tu nombre"
                       class="{{ $errors->has('nombre') ? 'error' : '' }}">
                @if($errors->has('nombre'))
                    <span class="error-message">{{ $errors->first('nombre') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label for="apellido">Apellido</label>
                <input type="text"
                       id="apellido"
                       name="apellido"
                       value="{{ old('apellido') }}"
                       required
                       placeholder="Tu apellido"
                       class="{{ $errors->has('apellido') ? 'error' : '' }}">
                @if($errors->has('apellido'))
                    <span class="error-message">{{ $errors->first('apellido') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email"
                       id="email"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       placeholder="tu@email.com"
                       class="{{ $errors->has('email') ? 'error' : '' }}">
                @if($errors->has('email'))
                    <span class="error-message">{{ $errors->first('email') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password"
                       id="password"
                       name="password"
                       required
                       placeholder="Mínimo 8 caracteres"
                       class="{{ $errors->has('password') ? 'error' : '' }}">
                @if($errors->has('password'))
                    <span class="error-message">{{ $errors->first('password') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirmar Contraseña</label>
                <input type="password"
                       id="password_confirmation"
                       name="password_confirmation"
                       required
                       placeholder="Repite tu contraseña">
            </div>

            <button type="submit" class="submit-button">
                Crear cuenta
            </button>

            <div class="auth-links">
                <p>¿Ya tienes una cuenta? <a href="{{ route('login') }}">Inicia sesión</a></p>
            </div>
        </form>
    </div>
</div>

@endsection
