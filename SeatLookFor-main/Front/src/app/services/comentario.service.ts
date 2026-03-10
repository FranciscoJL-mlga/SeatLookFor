import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Comentario } from '../models/comentario.model';
import { AuthService } from './auth.service';
import { environment } from '../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class ComentarioService {
  // private apiUrl = 'https://seatlookadmin.duckdns.org/api';
  private apiUrl = `${environment.apiUrlLocal}`;

  constructor(
    private http: HttpClient,
    private authService: AuthService
  ) {}

  crearComentario(idAsi: number, comentario: { opinion: string, valoracion: number, foto?: string }): Observable<Comentario> {
    return this.http.post<Comentario>(
      `${this.apiUrl}/asientos/${idAsi}/comentar`,
      comentario,
      { headers: this.authService.getAuthHeaders() }
    );
  }

  getComentariosAsiento(idAsiento: number): Observable<Comentario[]> {
    return this.http.get<Comentario[]>(
      `${this.apiUrl}/asientos/${idAsiento}/comentarios`,
      { headers: this.authService.getAuthHeaders() }
    );
  }

  eliminarComentario(idComentario: number): Observable<void> {
    return this.http.delete<void>(
      `${this.apiUrl}/comentarios/${idComentario}`,
      { headers: this.authService.getAuthHeaders() }
    );
  }

  getImagenAsiento(): Observable<string> {
    return this.http.get<string>(
      `${this.apiUrl}/imagenes/asiento1`,
      { headers: this.authService.getAuthHeaders() }
    );
  }

  getComentariosPorEvento(idEvento: number): Observable<{ comentarios: Comentario[] }> {
    return this.http.get<{ comentarios: Comentario[] }>(
      `${this.apiUrl}/eventos/${idEvento}/comentarios`
    );
  }
}
