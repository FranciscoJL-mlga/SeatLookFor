<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Comentario extends Model
{
    use HasFactory;

    
    protected $table = 'comentario';

    // Clave primaria 
    protected $primaryKey = 'idCom';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'opinion',
        'valoracion',
        'foto',
        'idUsu',
        'idAsi',
        'idEve'
    ];

    public function usuario(){
        return $this->belongsTo(Usuario::class, 'idUsu');
    }
    public function asiento(){
         return $this->belongsTo(Asiento::class, 'idAsi');
    }

}
