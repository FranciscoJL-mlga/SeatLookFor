<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComentariosSeeder extends Seeder
{
    public function run(): void
    {
        $opiniones = [
            // Zona A – Preferente
            ['zona' => 'A', 'opinion' => 'Vista privilegiada desde zona preferente. El escenario se ve completo sin ningún obstáculo. El sonido es espectacular, claramente la mejor ubicación del teatro.', 'valoracion' => 5.0],
            ['zona' => 'A', 'opinion' => 'Fila 2, columna central. No puedes pedir más. Visibilidad perfecta, se escucha todo con una nitidez increíble. Merece cada euro del precio.', 'valoracion' => 5.0],
            ['zona' => 'A', 'opinion' => 'Zona A primera fila, sensación inmersiva total. Los actores casi los puedes tocar. Muy recomendable para musicals donde quieres ver todos los detalles.', 'valoracion' => 4.5],
            ['zona' => 'A', 'opinion' => 'Excelente visibilidad desde aquí. La iluminación del escenario se aprecia perfectamente. Butacas muy cómodas, espacio para las piernas suficiente.', 'valoracion' => 4.5],
            ['zona' => 'A', 'opinion' => 'Primera vez en zona preferente y quedé impresionada. El ángulo es ideal, sin tener que girar el cuello. Los efectos especiales se ven de forma espectacular desde aquí.', 'valoracion' => 5.0],
            // Zona B – Platea
            ['zona' => 'B', 'opinion' => 'Zona B fila 4, buena relación calidad-precio. Se ve todo perfectamente, algo más lejos que preferente pero sin perder detalle. El sonido llega con mucha claridad.', 'valoracion' => 4.0],
            ['zona' => 'B', 'opinion' => 'Platea central, vista estupenda. A esta distancia se pierde algo de detalle facial pero se gana perspectiva del conjunto del escenario. Muy satisfecho.', 'valoracion' => 4.0],
            ['zona' => 'B', 'opinion' => 'Columna lateral en zona B, se ve el escenario un poco en ángulo pero sin problemas. El precio es justo para lo que ofrece. Butacas cómodas.', 'valoracion' => 3.5],
            ['zona' => 'B', 'opinion' => 'Vista correcta desde platea. El escenario completo, el sonido envolvente. Para el precio que tiene es una opción muy recomendable. Repetir seguro.', 'valoracion' => 4.0],
            ['zona' => 'B', 'opinion' => 'Fila 6 zona B, algo lejos pero se compensa con una vista panorámica del escenario completo. Para obras de teatro clásico es ideal porque ves la escenografía entera.', 'valoracion' => 3.5],
            // Zona C – Anfiteatro
            ['zona' => 'C', 'opinion' => 'Anfiteatro fila 7. La distancia es notable pero el precio es imbatible. Para conciertos es una opción fantástica porque el sonido sube muy bien. Para teatro prefiero platea.', 'valoracion' => 3.5],
            ['zona' => 'C', 'opinion' => 'Zona C correcta para el precio. Se ve todo aunque pequeño. Con binoculares sería perfecta. Para conciertos de música clásica el sonido es magnífico incluso desde aquí arriba.', 'valoracion' => 3.0],
            ['zona' => 'C', 'opinion' => 'Última fila anfiteatro. Punto de vista único, ves el espectáculo desde arriba y la perspectiva es muy diferente. Para ópera es bonito ver los movimientos corales desde esta altura.', 'valoracion' => 3.5],
            ['zona' => 'C', 'opinion' => 'Columna 8 zona C, vista ligeramente lateral. Nada grave pero se nota algo. El precio hace que valga la pena completamente. Repetiría si el presupuesto es ajustado.', 'valoracion' => 3.0],
            ['zona' => 'C', 'opinion' => 'Anfiteatro central fila 8. Lejos sí, pero con buen ángulo. La acústica del teatro es excelente y el sonido llega perfectamente. Para el precio, imposible quejarse.', 'valoracion' => 3.5],
        ];

        $usuarios = DB::table('usuario')->where('admin', false)->pluck('idUsu')->toArray();
        $eventos  = DB::table('evento')->pluck('idEve', 'idEst'); // idEst => idEve

        $asientos = DB::table('asiento')->get();

        $comentarios = [];
        $usadas = [];

        foreach ($opiniones as $i => $op) {
            // Buscar un asiento de la zona que no haya sido comentado por este usuario
            $candidatos = $asientos->filter(fn($a) => $a->zona === $op['zona'])->values();
            $asiento    = $candidatos[$i % $candidatos->count()];
            $idUsu      = $usuarios[$i % count($usuarios)];
            $idEve      = DB::table('evento')->where('idEst', $asiento->idEst)->value('idEve');

            if (!$idEve) continue;

            $key = $idUsu . '-' . $asiento->idAsi;
            if (isset($usadas[$key])) continue;
            $usadas[$key] = true;

            $comentarios[] = [
                'opinion'    => $op['opinion'],
                'valoracion' => $op['valoracion'],
                'foto'       => null,
                'idUsu'      => $idUsu,
                'idAsi'      => $asiento->idAsi,
                'idEve'      => $idEve,
            ];
        }

        if (!empty($comentarios)) {
            DB::table('comentario')->insert($comentarios);
        }
    }
}
