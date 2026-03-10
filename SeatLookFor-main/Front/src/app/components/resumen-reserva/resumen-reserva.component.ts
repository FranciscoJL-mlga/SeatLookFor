import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ActivatedRoute, Router } from '@angular/router';
import { Asiento } from '../../models/asiento.model';
import { ReservaService } from '../../services/reserva.service';
import { AuthService } from '../../services/auth.service';
import { Reserva } from '../../models/reserva.model';

@Component({
  selector: 'app-resumen-reserva',
  standalone: true,
  imports: [CommonModule],
  template: `
    <div class="resumen-container">
      <h2>Resumen de tu Reserva</h2>
      
      <div class="seccion">
        <h3>Asientos Seleccionados</h3>
        <div class="asientos-lista">
          <div *ngFor="let asiento of asientosSeleccionados" class="asiento-item">
            <p>Zona: {{asiento.zona}}</p>
            <p>Fila: {{asiento.ejeX}}, Columna: {{asiento.ejeY}}</p>
            <p>Precio: {{asiento.precio}}€</p>
          </div>
        </div>
      </div>

      <div class="seccion">
        <h3>Resumen de Precios</h3>
        <div class="precios">
          <p>Subtotal: {{calcularSubtotal()}}€</p>
          <p>Descuento: {{calcularDescuento()}}€</p>
          <p class="total">Total: {{calcularTotal()}}€</p>
        </div>
      </div>

      <div class="seccion">
        <h3>Información de la Reserva</h3>
        <p>Fecha de Reserva: {{fechaReserva | date:'dd/MM/yyyy HH:mm'}}</p>
      </div>

      <div class="acciones">
        <button class="btn-cancelar" (click)="cancelarReserva()">Cancelar</button>
        <button class="btn-confirmar" (click)="confirmarReserva()">Confirmar Reserva</button>
      </div>
    </div>
  `,
  styles: [`
    .resumen-container {
      max-width: 800px;
      margin: 2rem auto;
      padding: 2rem;
      background: white;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    h2 {
      color: #333;
      margin-bottom: 2rem;
      text-align: center;
    }

    .seccion {
      margin-bottom: 2rem;
      padding: 1rem;
      border: 1px solid #eee;
      border-radius: 4px;
    }

    h3 {
      color: #666;
      margin-bottom: 1rem;
    }

    .asientos-lista {
      display: grid;
      gap: 1rem;
    }

    .asiento-item {
      padding: 1rem;
      background: #f9f9f9;
      border-radius: 4px;
    }

    .precios {
      font-size: 1.1rem;
    }

    .total {
      font-size: 1.3rem;
      font-weight: bold;
      color: #2c3e50;
      margin-top: 1rem;
      padding-top: 1rem;
      border-top: 2px solid #eee;
    }

    .acciones {
      display: flex;
      gap: 1rem;
      justify-content: flex-end;
      margin-top: 2rem;
    }

    button {
      padding: 0.8rem 1.5rem;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-weight: bold;
      transition: all 0.3s ease;
    }

    .btn-cancelar {
      background: #e74c3c;
      color: white;
    }

    .btn-confirmar {
      background: #2ecc71;
      color: white;
    }

    button:hover {
      transform: translateY(-2px);
      box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
  `]
})
export class ResumenReservaComponent implements OnInit {
  asientosSeleccionados: Asiento[] = [];
  fechaReserva: Date = new Date();
  descuento: number = 0;
  idEvento: number = 0;

  constructor(
    private route: ActivatedRoute,
    private router: Router,
    private reservaService: ReservaService,
    private authService: AuthService
  ) {}

  ngOnInit() {
    this.asientosSeleccionados = this.reservaService.getAsientosSeleccionados();
    if (this.asientosSeleccionados.length === 0) {
      this.router.navigate(['/eventos']);
    }
    this.idEvento = Number(this.route.snapshot.paramMap.get('idEvento'));
  }

  calcularSubtotal(): number {
    return this.asientosSeleccionados.reduce((total, asiento) => {
      const precio = typeof asiento.precio === 'string' ? parseFloat(asiento.precio) : asiento.precio;
      return total + precio;
    }, 0);
  }
  
  calcularDescuento(): number {
    // Aquí puedes implementar la lógica de descuento
    return this.descuento;
  }
  
  calcularTotal(): number {
    console.log(this.calcularSubtotal() - this.calcularDescuento());
    return this.calcularSubtotal() - this.calcularDescuento();
  }

  cancelarReserva() {
    this.reservaService.limpiarAsientosSeleccionados();
    this.router.navigate(['/eventos']);
  }

  confirmarReserva() {
    if (!this.authService.isLoggedIn()) {
      this.router.navigate(['/login']);
      return;
    }

    const usuario = this.authService.currentUserValue;
    if (!usuario) {
      this.router.navigate(['/login']);
      return;
    }

    // Crear una única reserva con todos los asientos
    const reserva = {
      totalPrecio: this.calcularTotal(),
      fechaReserva: this.fechaReserva.toISOString().split('T')[0],
      idAsientos: this.asientosSeleccionados.map(asiento => asiento.idAsi),
      idEve: this.idEvento
    };

    console.log('Datos de la reserva a enviar:', reserva);

    // Crear la reserva
    this.reservaService.crearReserva(reserva).subscribe({
      next: (response) => {
        if (!response.body) {
          throw new Error('No se recibió el PDF');
        }

        // Crear un blob con la respuesta
        const blob = new Blob([response.body], { type: 'application/pdf' });
        
        // Crear un enlace para descargar el PDF
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        
        // Obtener el nombre del archivo del header Content-Disposition
        const contentDisposition = response.headers.get('content-disposition');
        let filename = 'entrada.pdf';
        if (contentDisposition) {
          const filenameMatch = contentDisposition.match(/filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/);
          if (filenameMatch && filenameMatch[1]) {
            filename = filenameMatch[1].replace(/['"]/g, '');
          }
        }
        
        link.download = filename;
        document.body.appendChild(link);
        link.click();
        
        // Limpiar
        window.URL.revokeObjectURL(url);
        document.body.removeChild(link);
        
        // Limpiar asientos y redirigir
        this.reservaService.limpiarAsientosSeleccionados();
        this.router.navigate(['/usuario']);
      },
      error: (error) => {
        console.error('Error al crear la reserva:', error);
        if (error.status === 422) {
          console.error('Detalles del error de validación:', error.error);
          alert('Error en los datos de la reserva: ' + JSON.stringify(error.error));
        } else if (error.status === 500) {
          console.error('Error interno del servidor:', error.error);
          alert('Error interno del servidor. Por favor, contacta con soporte.');
        } else {
          alert('Error al crear la reserva. Por favor, inténtalo de nuevo.');
        }
      }
    });
  }
}
