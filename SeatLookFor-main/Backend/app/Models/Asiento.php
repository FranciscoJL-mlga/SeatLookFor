<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asiento extends Model
{
    /** @use HasFactory<\Database\Factories\AsientoFactory> */
    use HasFactory;
    
    public $timestamps = false;

    protected $table = 'asiento';

    // Clave primaria 
    protected $primaryKey = 'idAsi';

    protected $fillable=
    [
        'estado',
        'zona',
        'ejeX',
        'ejeY',
        'estado',
        'idEst',
        'precio'
    
    ];
public function usuariosComentaron()
{
    return $this->belongsToMany(Usuario::class, 'comentario', 'idAsi', 'idUsu')
                ->withPivot('opinion', 'valoracion', 'foto');
}

    
    public function asientoReservado()
    {
        return $this->hasMany(Reserva::class,'idAsi');
    }

    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class);
        
    }

    public function eventos()
{
    return $this->belongsToMany(Evento::class, 'asientos_evento', 'idAsi', 'idEve')
                ->withPivot('precio');
}


}
