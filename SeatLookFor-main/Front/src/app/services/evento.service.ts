import { Injectable } from '@angular/core';
import { HttpClient, HttpErrorResponse, HttpHeaders } from '@angular/common/http';
import { catchError, map, Observable, throwError } from 'rxjs';
import { Evento } from '../models/evento.model';
import { EventoDetalle } from '../models/evento-detalle.model';
import { environment } from '../../environments/environment';


@Injectable({
  providedIn: 'root'
})
export class EventoService {
  // private apiUrl = 'https://seatlookadmin.duckdns.org/api/eventos'; 
  private apiUrl = `${environment.apiUrlLocal}/eventos`;

  constructor(private http: HttpClient) {}

  getEventos(): Observable<Evento[]> {
    const headers = new HttpHeaders({
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    });

    console.log('Realizando petición a:', this.apiUrl);

    return this.http.get<any>(this.apiUrl, { 
      headers
    }).pipe(
      map(response => {
        console.log('Respuesta del servidor:', response);
        if (response && response.data) {
          console.log("No es una array");
          
          return response.data;
        }
        if (Array.isArray(response)) {
          console.log("Es un array");

          return response;
        }
        
        return response;
      }),
      catchError((error: HttpErrorResponse) => {
        console.error('Error en la petición:', error);
        if (error.error instanceof ErrorEvent) {
          console.error('Error del cliente:', error.error.message);
        } else {
          console.error(`Código de error ${error.status}, ` + `mensaje: ${error.message}`);
        }
        return throwError(() => new Error('Error al obtener los eventos'));
      })
    );
  }
  
  /* con este metodo accedemos a los eventos personalizados */
  getEventoDetalle(id: number): Observable<EventoDetalle> {
    const headers = new HttpHeaders({
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    });
  
    const url = `${this.apiUrl}/${id}`;
    console.log('Petición de detalle a:', url);
  
    return this.http.get<EventoDetalle>(url, { headers }).pipe(
      catchError((error: HttpErrorResponse) => {
        console.error('Error al obtener el evento con detalle:', error);
        return throwError(() => new Error('Error al obtener el detalle del evento'));
      })
    );
  }
} 
