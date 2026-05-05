# SeatLookFor

Plataforma web de reserva de asientos para teatros, musicales y eventos en espacios pequeños. Permite a los usuarios elegir su asiento en un mapa interactivo, ver cómo se ve el escenario desde esa posición gracias a las valoraciones de asistentes anteriores, y confirmar su reserva descargando una entrada en PDF.

> **Aviso:** La aplicación está desplegada en un servidor gratuito de [Render](https://render.com). Si llevas un tiempo sin acceder, el servidor puede estar en reposo y tardar **aproximadamente 1 minuto** en arrancar antes de responder. Es completamente normal, solo hay que esperar.

---

## Tecnologías

**Backend:** PHP · Laravel · PostgreSQL  
**Frontend:** Blade · Alpine.js · Tailwind CSS · JavaScript  
**Infraestructura:** AWS · Artisan CLI

---

## Funcionalidades — Modo Usuario

### Explorar eventos

Los usuarios pueden navegar por el catálogo de eventos activos desde la página de inicio y desde la sección `/eventos`. Cada tarjeta muestra la portada, el título, la fecha, la categoría y el precio.

### Ver detalle de un evento

Al entrar en un evento se muestra:

- Mapa de asientos interactivo con colores por zona
- Información del establecimiento (nombre, imagen, ubicación)
- Sinopsis, duración, categoría y tipo del evento
- Sección de comentarios generales del evento
- Valoraciones por asiento dejadas por asistentes anteriores

### Registro e inicio de sesión

Cualquier persona puede crear una cuenta con nombre, apellido, email y contraseña.

### Selección de asientos

El mapa muestra todos los asientos codificados por color según su zona. Cada asiento puede estar en uno de estos estados:

| Color | Estado |
|---|---|
| Color de zona | Libre, seleccionable |
| Dorado | Tu asiento reservado en este evento |
| Amarillo | Seleccionado por ti en este momento |
| Naranja (parpadeante) | Bloqueado temporalmente por otro usuario |
| Rojo (opaco) | Ocupado, ya reservado |

Al pasar el cursor sobre un asiento con valoraciones, aparece un **tooltip** encima del asiento con la foto del escenario desde esa posición, la puntuación y la opinión de quien lo ocupó anteriormente. Si hay varios comentarios se puede navegar entre ellos con flechas.

### Proceso de reserva y bloqueo de asientos

1. El usuario selecciona uno o varios asientos libres en el mapa
2. Pulsa **Confirmar Reserva**
3. Los asientos seleccionados quedan **bloqueados durante 5 minutos** para ese usuario, impidiendo que otra persona los reserve simultáneamente
4. Se muestra una pantalla de resumen con los asientos, el precio total y un contador de tiempo
5. El usuario confirma y se genera una **entrada en PDF** descargable al instante
6. Al confirmar, los bloqueos se eliminan y los asientos quedan marcados como ocupados

Si el usuario abandona el proceso sin confirmar, los bloqueos expiran automáticamente pasados los 5 minutos. Si otro usuario llega a esos asientos mientras están bloqueados, recibe un aviso y se le redirige al mapa para elegir otros.

### Comentarios en eventos

Cualquier usuario autenticado puede dejar un comentario de texto libre en la página de un evento y responder a los comentarios de otros. No requiere haber asistido.

### Valoración de asientos

Los usuarios que hayan reservado un asiento pueden dejar una **valoración con foto** desde ese mismo asiento en el mapa. Esta funcionalidad tiene condiciones estrictas:

- El evento debe estar en estado **finalizado**
- El usuario debe tener una **reserva confirmada** en ese asiento concreto
- Solo está disponible durante el **mes siguiente** a la fecha del evento; pasado ese plazo, el formulario se cierra definitivamente

La valoración incluye una opinión de texto, una puntuación de 1 a 5 estrellas y una foto opcional de cómo se ve el escenario desde ese asiento. Estas fotos y valoraciones son las que aparecen en el tooltip del mapa para futuros compradores.

### Perfil de usuario

Desde `/usuario` el usuario autenticado puede:

- Ver su **historial de reservas** con evento, fecha y asiento
- Acceder a la sección de **valoración pendiente** si tiene eventos finalizados recientemente
- **Cambiar su contraseña** introduciendo la actual
- **Eliminar su cuenta** de forma permanente confirmando con contraseña

---

## Modo Administrador

El panel de administración está disponible en `/admin` y es independiente del acceso de usuarios.

### Gestión de establecimientos

- Crear un establecimiento con nombre, ubicación e imagen
- Definir zonas y distribuir asientos en un editor de cuadrícula
- Ver el detalle de cada establecimiento con sus zonas y asientos
- Eliminar un establecimiento (elimina en cascada sus zonas y asientos)

### Gestión de eventos

- Crear un evento asociándolo a un establecimiento existente, con título, descripción, categoría, tipo, fecha, duración y portada
- Vincular los asientos del establecimiento al evento y asignar un precio por zona
- Ver el detalle de un evento con el estado de ocupación de cada asiento
- **Cambiar el estado** entre `activo` y `finalizado`
- **Repetir un evento**: crea un nuevo evento idéntico cambiando únicamente la fecha y hora, copiando todos los asientos y precios del original
- Eliminar un evento

---

## Modo Demo

El modo demo permite que cualquier persona explore el panel de administración sin riesgo de modificar datos reales.

### Cómo funciona

El acceso al modo demo se realiza con las siguientes credenciales:

| Campo | Valor |
|---|---|
| Email | `demo@seatlookfor.com` |
| Contraseña | `demo1234` |

- Al iniciar sesión, la cuenta **Demo SeatLookFor** tiene un tiempo de sesión limitado
- Durante la sesión puede crear establecimientos, eventos y asientos exactamente igual que un administrador real
- Todo el contenido creado queda marcado internamente como demo y no es visible para otros usuarios

### Reset automático

El contenido demo se borra y se regenera automáticamente en tres situaciones:

1. **Al cerrar sesión** manualmente
2. **Al expirar el tiempo** de sesión (el middleware lo detecta en cada petición al panel)
3. **Cuando el contador llega a cero** en el navegador (el cliente notifica al servidor vía JS)

El reset elimina en orden correcto todos los datos relacionados (reservas, bloqueos, comentarios, asientos, eventos, zonas y establecimientos demo), restaura la contraseña del usuario demo a `demo1234` y vuelve a sembrar el contenido de ejemplo desde el seeder.

### Visibilidad en modo usuario

Si el usuario demo accede al área pública (`/eventos`), puede ver los eventos que él mismo ha creado en el panel, aunque estos no son visibles para el resto de usuarios.

---

## Autores

**Francisco Jiménez López** — Backend  
[linkedin.com/in/francisco-jimenez-lopez-1a1517217](https://www.linkedin.com/in/francisco-jimenez-lopez-1a1517217)

**Antonio Jesus Heredias** — Frontend y diseño  
[linkedin.com/in/antoniojheredia](https://www.linkedin.com/in/antoniojheredia/)

Desarrollado como proyecto de fin de ciclo en el **Instituto Alan Turing** (DAW).
