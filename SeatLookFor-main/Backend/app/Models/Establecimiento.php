<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Establecimiento extends Model
{
    /** @use HasFactory<\Database\Factories\EstablecimientoFactory> */
    use HasFactory;

    protected $table = 'establecimiento';
    protected $primaryKey = 'idEst';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'ubicacion',
        'imagen',
        'tipo'
    ];

    public function eventos()
    {
        return $this->hasMany(Evento::class, 'idEst');
    }

    public function asientos()
    {
        return $this->hasMany(Asiento::class, 'idEst');
    }
}
