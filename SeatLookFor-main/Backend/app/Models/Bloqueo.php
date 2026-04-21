<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bloqueo extends Model
{
    public $timestamps = false;
    protected $fillable = ['idAsi', 'idUsu', 'idEve', 'expires_at'];
    protected $casts    = ['expires_at' => 'datetime'];

    public static function limpiarExpirados(): void
    {
        static::where('expires_at', '<', now())->delete();
    }

    public static function estaBlockeado(int $idAsi, int $idEve, ?int $idUsuActual = null): bool
    {
        return static::where('idAsi', $idAsi)
            ->where('idEve', $idEve)
            ->where('expires_at', '>', now())
            ->when($idUsuActual, fn ($q) => $q->where('idUsu', '!=', $idUsuActual))
            ->exists();
    }
}
