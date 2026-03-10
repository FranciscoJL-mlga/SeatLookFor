import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpErrorResponse } from '@angular/common/http';
import { Observable, map, catchError, throwError } from 'rxjs';
import { Evento } from '../models/evento.model';
import { environment } from '../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class CardService {
  // private apiUrl = 'https://seatlookadmin.duckdns.org/api/recientes/3';
  private apiUrl = `${environment.apiUrlLocal}/recientes/3`;

  constructor(private http: HttpClient) {}

  getCards(): Observable<Evento[]> {
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
        
        return response.recientes;
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
} 