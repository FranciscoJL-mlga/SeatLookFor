<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zona extends Model
{
    protected $table = 'zona';
    protected $primaryKey = 'idZona';
    public $timestamps = false;

    protected $fillable = ['nombre', 'idEst'];

    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class, 'idEst', 'idEst');
    }

    public function eventos()
    {
        return $this->belongsToMany(Evento::class, 'zona_evento', 'idZona', 'idEve');
    }
}
