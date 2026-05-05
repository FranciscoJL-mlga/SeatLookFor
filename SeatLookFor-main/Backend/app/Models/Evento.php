<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Evento extends Model
{
    /** @use HasFactory<\Database\Factories\EventoFactory> */
    use HasFactory;

    public $timestamps = false;
    protected $table = 'evento';

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
        'portada',
        'codigo',
        'demo',
    ];

    public function getRouteKeyName(): string
    {
        return 'codigo';
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($evento) {
            if (empty($evento->codigo)) {
                do {
                    $codigo = Str::random(8);
                } while (static::where('codigo', $codigo)->exists());
                $evento->codigo = $codigo;
            }
        });
    }

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
