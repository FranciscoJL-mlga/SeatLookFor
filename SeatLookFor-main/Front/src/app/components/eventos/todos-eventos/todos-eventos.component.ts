import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { EventosCardComponent } from '../../eventos-card/eventos-card.component';
import { FooterComponent } from '../../footer/footer.component';
import { RouterModule } from '@angular/router';
import { NavbarComponent } from '../../navbar/navbar.component';
import { EventoService } from '../../../services/evento.service';
import { Evento } from '../../../models/evento.model';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-todos-eventos',
  imports: [CommonModule, FooterComponent, EventosCardComponent, RouterModule, NavbarComponent, FormsModule],
  templateUrl: './todos-eventos.component.html',
  styleUrl: './todos-eventos.component.css'
})
export class TodosEventosComponent implements OnInit {
  eventos: Evento[] = [];
  eventosFiltrados: Evento[] = [];

  categorias = ['Drama', 'Familiar', 'Clásica', 'Musical', 'Barroco', 'Fantasía', 'Suspenso', 'Comedia'];
  tipos = ['Teatro', 'Orquesta', 'Musical', 'Concierto'];

  filtros = {
    tipo: '',
    categoria: '',
    duracion: '',
    fecha: '',
    valoracion: ''
  };

  constructor(private eventoService: EventoService) { }

  ngOnInit() {
    this.eventoService.getEventos().subscribe((data) => {
      this.eventos = data;
      this.eventosFiltrados = [...this.eventos];
    });
  }

  ordenarPorValoracion = false;
  sinResultados: boolean = false;


aplicarFiltros() {
  const valoracionFiltro = this.filtros.valoracion ? Number(this.filtros.valoracion) : 0;

  this.eventosFiltrados = this.eventos
    .filter(evento => {
      return (
        (!this.filtros.tipo || evento.tipo === this.filtros.tipo) &&
        (!this.filtros.categoria || evento.categoria === this.filtros.categoria) &&
        (!this.filtros.duracion || evento.duracion === this.filtros.duracion) &&
        (!this.filtros.fecha || evento.fecha === this.filtros.fecha) &&
        (!this.filtros.valoracion || (evento.valoracion && Number(evento.valoracion) >= valoracionFiltro))
      );
    });

  if (this.ordenarPorValoracion) {
    this.eventosFiltrados.sort((a, b) => Number(b.valoracion) - Number(a.valoracion));
  }

  // ⚠️ Verifica si el array filtrado está vacío
  this.sinResultados = this.eventosFiltrados.length === 0;
}


  ordenarValoracion() {
    this.ordenarPorValoracion = true;
    
    this.aplicarFiltros();
  }



  resetFiltros() {
    this.filtros = { tipo: '', categoria: '', duracion: '', fecha: '', valoracion: '' };
    this.eventosFiltrados = [...this.eventos];
    this.ordenarPorValoracion = false;
    this.sinResultados = false;
  }
}
