<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    /** @use HasFactory<\Database\Factories\ReservaFactory> */
    use HasFactory;

    public $timestamps = false;
    protected $table = 'reserva';

    // Clave primaria 
    protected $primaryKey = 'idRes';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'fechaReserva',
        'idAsi',
        'idUsu',
        'idEve',
        'totalPrecio'
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'idEve', 'idEve');
    }

    public function asiento()
    {
        return $this->belongsTo(Asiento::class, 'idAsi', 'idAsi');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsu', 'idUsu');
    }
}
