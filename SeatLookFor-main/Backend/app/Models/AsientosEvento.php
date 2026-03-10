<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsientosEvento extends Model
{
    protected $table = 'asientos_evento';
    public $timestamps = false;

    protected $primaryKey = 'idAE';
    protected $fillable = [
        'precio',
        'idAsi',
        'idEve',
    ];
}
