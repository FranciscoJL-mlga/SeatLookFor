<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasApiTokens, Notifiable;

    protected $table = 'usuario';

    // Clave primaria 
    protected $primaryKey = 'idUsu';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'password',
        'estado',
        'admin',
    ];

    public $timestamps = false;
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'admin' => 'boolean',
    ];

/*     public function comentarios()
    {
        return $this->belongsToMany(Asiento::class, 'comentario', 'idUsu', 'idAsi');
    }
 */
    public function Reservas()
    {
        return $this->hasMany(Evento::class,);
    }


    public function asientosComentados()
{
    return $this->belongsToMany(Asiento::class, 'comentario', 'idUsu', 'idAsi')
                ->withPivot('opinion', 'valoracion', 'foto')
                ->withTimestamps();
}

}
