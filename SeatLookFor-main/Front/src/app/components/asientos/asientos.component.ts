import { Component, Input, Output, EventEmitter, OnInit } from '@angular/core';
import { Asiento } from '../../models/asiento.model';
import { CommonModule } from '@angular/common';
import { ComentarioService } from '../../services/comentario.service';
import { ComentarioFormComponent } from '../comentario-form/comentario-form.component';
import { Comentario } from '../../models/comentario.model';
import { ReservaService } from '../../services/reserva.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-asientos',
  imports: [CommonModule, ComentarioFormComponent],
  templateUrl: './asientos.component.html',
  styleUrl: './asientos.component.css'
})
export class AsientosComponent implements OnInit {
  @Input() asientos: Asiento[] = [];
  @Output() asientosSeleccionadosChange = new EventEmitter<Asiento[]>();

  asientosSeleccionados: Asiento[] = [];
  mostrarTooltip = false;
  tooltipX = 0;
  tooltipY = 0;
  comentariosAsiento: Comentario[] = [];
  asientoSeleccionado: Asiento | null = null;
  imagenActual = 0;
  mouseEnTooltip = false;

  constructor(
    private comentarioService: ComentarioService,
    private reservaService: ReservaService,
    private router: Router
  ) {}

  ngOnInit() {
    return true
    // Cargar comentarios para cada asiento
    this.asientos.forEach(asiento => {
      this.comentarioService.getComentariosAsiento(asiento.idAsi).subscribe(
        comentarios => {
          asiento.comentarios = comentarios;
        }
      );
    });
  }

  selecionarAsiento(asiento: Asiento) {
    if (asiento.estado === 'ocupado') return;
    
    const index = this.asientosSeleccionados.findIndex(a => 
      a.ejeX === asiento.ejeX && a.ejeY === asiento.ejeY
    );

    if (index === -1) {
      this.asientosSeleccionados.push(asiento);
      this.asientoSeleccionado = asiento;
    } else {
      this.asientosSeleccionados.splice(index, 1);
      if (this.asientoSeleccionado?.idAsi === asiento.idAsi) {
        this.asientoSeleccionado = null;
      }
    }

    this.asientosSeleccionadosChange.emit(this.asientosSeleccionados);
    this.reservaService.setAsientosSeleccionados(this.asientosSeleccionados);
    
    // Redirigir a la página de resumen de reserva
    if (this.asientosSeleccionados.length > 0) {
      this.router.navigate(['/resumen-reserva']);
    }
  }

  estaSeleccionado(asiento: Asiento): boolean {
    return this.asientosSeleccionados.some(a => 
      a.ejeX === asiento.ejeX && a.ejeY === asiento.ejeY
    );
  }

  mostrarImagen(event: MouseEvent, asiento: Asiento) {
    if (asiento.comentarios?.some(c => c.foto)) {
      this.mostrarTooltip = true;
      this.tooltipX = event.clientX + 10;
      this.tooltipY = event.clientY + 10;
      this.comentariosAsiento = asiento.comentarios.filter(c => c.foto);
      this.imagenActual = 0;
    }
  }

  siguienteImagen() {
    if (this.imagenActual < this.comentariosAsiento.length - 1) {
      this.imagenActual++;
    } else {
      this.imagenActual = 0;
    }
  }

  anteriorImagen() {
    if (this.imagenActual > 0) {
      this.imagenActual--;
    } else {
      this.imagenActual = this.comentariosAsiento.length - 1;
    }
  }

  mouseEntraTooltip() {
    this.mouseEnTooltip = true;
  }

  mouseSaleTooltip() {
    this.mouseEnTooltip = false;
    this.ocultarImagen();
  }

  ocultarImagen() {
    if (!this.mouseEnTooltip) {
      this.mostrarTooltip = false;
      this.imagenActual = 0;
    }
  }

  onComentarioCreado() {
    if (this.asientoSeleccionado) {
      this.comentarioService.getComentariosAsiento(this.asientoSeleccionado.idAsi).subscribe(
        comentarios => {
          this.asientoSeleccionado!.comentarios = comentarios;
        }
      );
    }
  }
}
