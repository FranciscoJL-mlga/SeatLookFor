<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    /** @use HasFactory<\Database\Factories\EventoFactory> */
    use HasFactory;

    public $timestamps = false;
    protected $table = 'evento';

    // Clave primaria 
    protected $primaryKey = 'idEve';

    protected $fillable = [
        'estado',
        'valoracion',
        'tipo',
        'ubicacion',
        'titulo',
        'descripcion',
        'duracion',
        'fecha',
        'categoria',
        'idEst',
        'portada'
    ];

    public function ReservaDeEventos()
    {
        return $this->hasMany(Reserva::class,'idEve');
    }

    
    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class, 'idEst');
    }

        public function asientos()
        {
            return $this->belongsToMany(Asiento::class, 'asientos_evento', 'idEve', 'idAsi')->withPivot('precio');
        }

}
