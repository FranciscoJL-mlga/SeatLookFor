<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Descuento extends Model
{
   
    protected $table = 'descuento';

    // Clave primaria 
    protected $primaryKey = 'idDes';

    protected $fillable=
    [
       
        "estado",
        "descuento",
        "codigo",


    ];
}
