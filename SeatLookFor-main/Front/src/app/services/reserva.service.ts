import { Injectable } from '@angular/core';
import { BehaviorSubject, Observable, map } from 'rxjs';
import { Asiento } from '../models/asiento.model';
import { Reserva } from '../models/reserva.model';
import { HttpClient, HttpHeaders, HttpResponse } from '@angular/common/http';
import { environment } from '../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class ReservaService {
  // private apiUrl = 'https://seatlookadmin.duckdns.org/api';
  private apiUrl = `${environment.apiUrl}/reservas`;
  private asientosSeleccionados = new BehaviorSubject<Asiento[]>([]);
  asientosSeleccionados$ = this.asientosSeleccionados.asObservable();

  constructor(private http: HttpClient) {}

  private getHeaders(): HttpHeaders {
    const token = localStorage.getItem('token');
    return new HttpHeaders({
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${token}`
    });
  }

  setAsientosSeleccionados(asientos: Asiento[]) {
    this.asientosSeleccionados.next(asientos);
  }

  getAsientosSeleccionados(): Asiento[] {
    return this.asientosSeleccionados.value;
  }

  limpiarAsientosSeleccionados() {
    this.asientosSeleccionados.next([]);
  }

  crearReserva(reserva: Reserva): Observable<HttpResponse<Blob>> {
    return this.http.post(
      `${this.apiUrl}`,
      reserva,
      { 
        headers: this.getHeaders(),
        observe: 'response',
        responseType: 'blob'
      }
    );
  }
}
