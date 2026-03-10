import { Component, Input, OnChanges } from '@angular/core';
import { Evento } from '../../models/evento.model';
import { EventoService } from '../../services/evento.service';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';

@Component({
  selector: 'app-eventos-card',
  imports: [CommonModule, RouterModule],
  templateUrl: './eventos-card.component.html',
  styleUrl: './eventos-card.component.css'
})
export class EventosCardComponent implements OnChanges {
  @Input() eventos: Evento[] = [];
  cards: Evento[] = [];

  loading = true;
  error: string | null = null;

  private readonly baseUrl = 'http://localhost';
  pageSize = 4;
  currentPage = 1;
  totalItems = 0;
  totalPages = 0;

  constructor(private eventoService: EventoService) {}

  ngOnChanges() {
    if (!this.eventos || this.eventos.length === 0) {
      this.resetPagination();
      return;
    }

    this.eventos = this.eventos.map(e => ({
      ...e,
      portada: this.transformImageUrl(e.portada)
    }));

    this.totalItems = this.eventos.length;
    this.totalPages = Math.ceil(this.totalItems / this.pageSize);
    this.currentPage = 1;
    this.updatePagedCards();
    this.loading = false;
  }

  private transformImageUrl(url: string): string {
    if (!url) return '';
    return url.startsWith('http') ? url : `${this.baseUrl}/images/eventos/${url.split('/').pop()}`;
  }

  private resetPagination() {
    this.cards = [];
    this.totalItems = 0;
    this.totalPages = 0;
    this.loading = false;
  }

  updatePagedCards() {
    const start = (this.currentPage - 1) * this.pageSize;
    const end = start + this.pageSize;
    this.cards = this.eventos.slice(start, end);
  }

  nextPage() {
    if (this.currentPage < this.totalPages) {
      this.currentPage++;
      this.updatePagedCards();
    }
  }

  previousPage() {
    if (this.currentPage > 1) {
      this.currentPage--;
      this.updatePagedCards();
    }
  }

  goToPage(page: number) {
    if (page >= 1 && page <= this.totalPages) {
      this.currentPage = page;
      this.updatePagedCards();
    }
  }

  getPages(): number[] {
    return Array.from({ length: this.totalPages }, (_, i) => i + 1);
  }
}
