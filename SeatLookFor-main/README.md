# SeatLookFor 🎭🎟️

## Descripción 📌

SeatLookFor es una plataforma web que permite a los usuarios conocer la vista real desde cualquier asiento antes de comprar su entrada. Los usuarios pueden reservar entradas, subir fotos desde su asiento, valorar su experiencia y comentar sobre eventos y espectáculos.

## Funcionalidades principales ✨

- 🗺️ **Mapa de asientos interactivo**: Selección visual de asientos por zonas con disponibilidad en tiempo real.
- 🔒 **Reserva con bloqueo temporal**: Los asientos se bloquean 5 minutos durante el proceso de compra para evitar conflictos.
- 📷 **Fotos desde el asiento**: Los usuarios que han asistido pueden subir fotos reales desde su ubicación.
- ⭐ **Valoraciones y comentarios**: Sistema de valoración por asiento y comentarios generales sobre el evento.
- 💬 **Respuestas entre usuarios**: Los usuarios pueden responder a comentarios de otros.
- 🎟️ **Historial de reservas**: Cada usuario tiene acceso a sus entradas y eventos visitados en su perfil.
- 👤 **Gestión de cuenta**: Cambio de contraseña y eliminación de cuenta desde el perfil.
- 🛡️ **Panel de administración**: Gestión de eventos, establecimientos y usuarios.
- 📄 **Generación de PDF**: Entrada en formato PDF descargable tras la compra.

## Tecnologías utilizadas 🛠️

- **Frontend**: Laravel Blade + Alpine.js + CSS personalizado
- **Backend**: Laravel 11 (PHP)
- **Base de datos**: MySQL (local) / PostgreSQL via Supabase (producción)
- **Servidor local**: Laragon

## Instalación local 🚀

### Requisitos
- PHP 8.2+
- Composer
- Node.js + npm
- MySQL (o Laragon)

### Pasos

```bash
# Clonar el repositorio
git clone https://github.com/FranciscoJL-mlga/SeatLookFor.git
cd SeatLookFor/Backend

# Instalar dependencias PHP
composer install

# Instalar dependencias JS y compilar assets
npm install && npm run build

# Copiar y configurar el .env
cp .env.example .env
php artisan key:generate

# Configurar la base de datos en .env y ejecutar migraciones
php artisan migrate --seed

# Enlazar el almacenamiento de imágenes
php artisan storage:link

# Iniciar el servidor
php artisan serve
```

La aplicación estará disponible en `http://localhost:8000`.

### Credenciales de prueba

| Rol | Email | Contraseña |
|-----|-------|-----------|
| Administrador | paco@seatlookfor.com | (ver seeder) |
| Usuario | maria.garcia@gmail.com | (ver seeder) |

## Base de datos 🗄️

El archivo `seatlook_dump.sql` contiene un volcado completo de MySQL con datos de prueba.

Para importar en Supabase (PostgreSQL) usa el archivo `seatlook_postgresql.sql`.

## Equipo 👥

- **Francisco Jiménez López**
- **Antonio J. Heredia Leiva**

## Anteproyecto 📄

[TFG - SeatLookFor en Notion](https://branched-juniper-ded.notion.site/TFG-1b984cda3c97803dbb8dd31a2e6bb895)

## Checkpoint
[Enlace a video de YouTube](https://www.youtube.com/watch?v=KySzsRHFuxM&ab_channel=AntonioJes%C3%BAsHerediaLeiva)

## Presentación PDF
[TGC.pdf](https://github.com/user-attachments/files/20769626/TGC.pdf)

## Enlaces de Diseño (Figma)

### UI Kits
[UI Kits de SeatLookFor](https://www.figma.com/proto/ImMMo3FgZPSp6FfYw4JNMP/SeatLookFor?node-id=3027-141&p=f&t=Kvjn1FSMpw0egeMm-0&scaling=contain&content-scaling=fixed&page-id=0%3A1)

### Wireframes
- [Wireframe de Baja Fidelidad](https://www.figma.com/proto/ImMMo3FgZPSp6FfYw4JNMP/SeatLookFor?node-id=3261-604&p=f&t=Kvjn1FSMpw0egeMm-0&scaling=min-zoom&content-scaling=fixed&page-id=3261%3A594)
- [Wireframe de Alta Fidelidad](https://www.figma.com/proto/ImMMo3FgZPSp6FfYw4JNMP/SeatLookFor?node-id=3261-652&p=f&t=oJVvSz3zhEM0c21j-1&scaling=min-zoom&content-scaling=fixed&page-id=3261%3A595&starting-point-node-id=3261%3A652)

### FigJam
[FigJam de SeatLookFor](https://www.figma.com/board/hK2Am5sJmjC7Rc83VmBF1f/SeatLookFor?node-id=1-731&t=VQYImc6Rd39f3ank-1)

## Video de Review del Proyecto
[Ver video de review](https://youtu.be/nXtgN2nFSh8)
