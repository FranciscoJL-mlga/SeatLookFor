<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventoSeeder extends Seeder
{
    public function run(): void
    {
        // idEst: 1=Cervantes(Málaga), 2=Auditorio(Madrid), 3=Liceu(Barcelona), 4=Maestranza(Sevilla)
        DB::table('evento')->insert([
            [
                'titulo'      => 'El Fantasma de la Ópera',
                'estado'      => 'activo',
                'valoracion'  => '4.8',
                'ubicacion'   => 'C/ Ramos Marín s/n, Málaga',
                'tipo'        => 'Musical',
                'descripcion' => 'El clásico de Andrew Lloyd Webber vuelve a los escenarios en una producción espectacular con los mejores efectos especiales. Una historia de amor, obsesión y música que te dejará sin aliento.',
                'portada'     => 'https://picsum.photos/seed/fantasma-opera/800/450',
                'duracion'    => '02:30:00',
                'fecha'       => '2025-05-15',
                'categoria'   => 'Musical',
                'idEst'       => 1,
            ],
            [
                'titulo'      => 'La Traviata',
                'estado'      => 'activo',
                'valoracion'  => '4.9',
                'ubicacion'   => 'La Rambla 51-59, Barcelona',
                'tipo'        => 'Ópera',
                'descripcion' => 'La ópera más representada del mundo de Giuseppe Verdi. Una historia de amor imposible entre Violetta, cortesana parisina, y el joven Alfredo. Una producción del Gran Teatro del Liceu con voces de primera categoría mundial.',
                'portada'     => 'https://picsum.photos/seed/la-traviata/800/450',
                'duracion'    => '02:45:00',
                'fecha'       => '2025-05-22',
                'categoria'   => 'Ópera',
                'idEst'       => 3,
            ],
            [
                'titulo'      => 'Chicago el Musical',
                'estado'      => 'activo',
                'valoracion'  => '4.6',
                'ubicacion'   => 'C/ Ramos Marín s/n, Málaga',
                'tipo'        => 'Musical',
                'descripcion' => 'Broadway llega a Málaga. Crimen, corrupción, jazz y showbiz en el Chicago de los años 20. Con los temas más icónicos como "All That Jazz" y "Roxie". Una noche que no olvidarás.',
                'portada'     => 'https://picsum.photos/seed/chicago-musical/800/450',
                'duracion'    => '02:15:00',
                'fecha'       => '2025-06-07',
                'categoria'   => 'Musical',
                'idEst'       => 1,
            ],
            [
                'titulo'      => 'Carmina Burana',
                'estado'      => 'activo',
                'valoracion'  => '4.7',
                'ubicacion'   => 'C/ Príncipe de Vergara 146, Madrid',
                'tipo'        => 'Concierto',
                'descripcion' => 'La Orquesta y Coro Nacional de España interpreta la obra magna de Carl Orff. Una experiencia musical sin igual con más de 200 músicos y cantantes en escena. La obertura O Fortuna en todo su esplendor.',
                'portada'     => 'https://picsum.photos/seed/carmina-burana/800/450',
                'duracion'    => '01:45:00',
                'fecha'       => '2025-06-14',
                'categoria'   => 'Clásica',
                'idEst'       => 2,
            ],
            [
                'titulo'      => 'Noche de Flamenco',
                'estado'      => 'activo',
                'valoracion'  => '4.5',
                'ubicacion'   => 'Paseo de Colón 22, Sevilla',
                'tipo'        => 'Concierto',
                'descripcion' => 'Una noche mágica con los mejores exponentes del flamenco contemporáneo. Baile, cante jondo y guitarra española en el teatro más emblemático de Sevilla. Arte puro en estado bruto.',
                'portada'     => 'https://picsum.photos/seed/noche-flamenco/800/450',
                'duracion'    => '02:00:00',
                'fecha'       => '2025-06-21',
                'categoria'   => 'Flamenco',
                'idEst'       => 4,
            ],
            [
                'titulo'      => 'Hamlet',
                'estado'      => 'activo',
                'valoracion'  => '4.7',
                'ubicacion'   => 'La Rambla 51-59, Barcelona',
                'tipo'        => 'Teatro',
                'descripcion' => 'La tragedia más universal de Shakespeare en una versión contemporánea dirigida por uno de los directores más reconocidos del teatro europeo. Una reflexión sobre el poder, la traición y la locura.',
                'portada'     => 'https://picsum.photos/seed/hamlet-shakespeare/800/450',
                'duracion'    => '02:30:00',
                'fecha'       => '2025-07-05',
                'categoria'   => 'Drama',
                'idEst'       => 3,
            ],
            [
                'titulo'      => 'Concierto de Jazz: Miles Davis Tribute',
                'estado'      => 'activo',
                'valoracion'  => '4.4',
                'ubicacion'   => 'C/ Príncipe de Vergara 146, Madrid',
                'tipo'        => 'Concierto',
                'descripcion' => 'Homenaje al genio del jazz en el centenario de su nacimiento. La Iberian Jazz Orchestra interpreta los grandes temas de Kind of Blue, Bitches Brew y otras obras maestras del artista más influyente del jazz moderno.',
                'portada'     => 'https://picsum.photos/seed/jazz-tribute-madrid/800/450',
                'duracion'    => '01:50:00',
                'fecha'       => '2025-07-12',
                'categoria'   => 'Jazz',
                'idEst'       => 2,
            ],
            [
                'titulo'      => 'La Casa de Bernarda Alba',
                'estado'      => 'activo',
                'valoracion'  => '4.6',
                'ubicacion'   => 'Paseo de Colón 22, Sevilla',
                'tipo'        => 'Teatro',
                'descripcion' => 'La obra cumbre de Federico García Lorca en una producción que ha recorrido los principales teatros de Europa. La historia de opresión, deseo y libertad de cinco mujeres bajo el yugo de una madre autoritaria.',
                'portada'     => 'https://picsum.photos/seed/bernarda-alba/800/450',
                'duracion'    => '01:45:00',
                'fecha'       => '2025-07-19',
                'categoria'   => 'Drama',
                'idEst'       => 4,
            ],
        ]);
    }
}
