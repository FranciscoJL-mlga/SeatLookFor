import { Component, OnInit } from '@angular/core';
import { NavbarComponent } from '../../navbar/navbar.component';
import { FooterComponent } from '../../footer/footer.component';
import { EventoDetalle } from '../../../models/evento-detalle.model';
import { ActivatedRoute, RouterModule, Router } from '@angular/router';
import { EventoService } from '../../../services/evento.service';
import { AsientosComponent } from '../../asientos/asientos.component';
import { CommonModule } from '@angular/common';
import { Asiento } from '../../../models/asiento.model';
import { Comentario } from '../../../models/comentario.model'; // 🔄 CAMBIO: importar modelo de comentario
import { ComentarioService } from '../../../services/comentario.service'; // 🔄 CAMBIO: importar servicio de comentarios
import { AuthService } from '../../../services/auth.service';

@Component({
  selector: 'eventos-personalizados',
  imports: [NavbarComponent, FooterComponent, AsientosComponent, CommonModule, RouterModule],
  templateUrl: './eventos-personalizados.component.html',
  styleUrl: './eventos-personalizados.component.css'
})
export class EventosPersonalizadosComponent implements OnInit {

  titulo: String = "Matilda";
  datosEvento?: EventoDetalle;
  asientosSeleccionados: Asiento[] = [];
  asientoSeleccionado: Asiento | null = null;

  comentariosEvento: Comentario[] = []; // ✅ Siempre es un array


  constructor(
    private route: ActivatedRoute,
    private eventoService: EventoService,
    private comentarioService: ComentarioService, // 🔄 CAMBIO: inyectar servicio
    private authService: AuthService,
    private router: Router
  ) { }

  ngOnInit(): void {
    const id = Number(this.route.snapshot.paramMap.get('id'));
    if (id) {
      this.eventoService.getEventoDetalle(id).subscribe({
        next: (data) => {
          this.datosEvento = data;
          console.log(this.datosEvento);
          console.log('Datos recibidos del evento:', data);


          // 🔄 CAMBIO: cargar comentarios del evento   
          this.cargarComentariosEvento(data.evento.idEve);
        },
        error: (err) => {
          console.error(err);
        }
      });
    }
  }

  // 🔄 CAMBIO: método para obtener comentarios del evento
  private cargarComentariosEvento(idEvento: number): void {
    this.comentarioService.getComentariosPorEvento(idEvento).subscribe({
      next: (res: any) => {
        this.comentariosEvento = res.comentarios;
      },
      error: (err) => {
        console.error('Error al cargar comentarios del evento:', err);
      }
    });
  }

  onAsientosSeleccionados(asientos: Asiento[]) {
    this.asientosSeleccionados = asientos;
    if (asientos.length > 0) {
      this.asientoSeleccionado = asientos[asientos.length - 1];
    } else {
      this.asientoSeleccionado = null;
    }
  }

  eliminarAsiento(asiento: Asiento) {
    const index = this.asientosSeleccionados.findIndex(a => 
      a.ejeX === asiento.ejeX && a.ejeY === asiento.ejeY
    );
    if (index !== -1) {
      this.asientosSeleccionados.splice(index, 1);
      if (this.asientoSeleccionado?.idAsi === asiento.idAsi) {
        this.asientoSeleccionado = this.asientosSeleccionados.length > 0 ? 
          this.asientosSeleccionados[this.asientosSeleccionados.length - 1] : null;
      }
    }
  }

  calcularTotal(): number {
    return this.asientosSeleccionados.reduce((total, asiento) => {
      const precio = typeof asiento.precio === 'string' ? parseFloat(asiento.precio) : asiento.precio;
      return total + precio;
    }, 0);
  }

  onComentarioCreado() {
    if (this.asientoSeleccionado) {
      this.eventoService.getEventoDetalle(Number(this.route.snapshot.paramMap.get('id'))).subscribe({
        next: (data) => {
          this.datosEvento = data;
          const asientoActualizado = this.datosEvento?.establecimiento?.asientos?.find(
            a => a.idAsi === this.asientoSeleccionado?.idAsi
          );
          if (asientoActualizado) {
            this.asientoSeleccionado = asientoActualizado;
          }
        },
        error: (err) => {
          console.error('Error al actualizar comentarios:', err);
        }
      });
    }
  }

  getEstrellas(valoracion: number | string | undefined): number[] {
  const val = Math.round(typeof valoracion === 'string' ? parseFloat(valoracion) : valoracion || 0);
  return Array(val).fill(0);
  }

  getFotoComentario(foto?: string): string {
    if (!foto) return 'assets/images/no-image.png';
    if (foto.startsWith('http')) return foto;
    // return `http://seatlookfor.duckdns.org/${foto}`;
    return `http://localhost/${foto}`; 
  }

  confirmarReserva() {
    if (!this.authService.isLoggedIn()) {
      this.router.navigate(['/login']);
      return;
    }
    this.router.navigate(['/resumen', this.datosEvento?.evento?.idEve]);
  }
}
