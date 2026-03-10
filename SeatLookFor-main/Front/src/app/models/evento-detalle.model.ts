import { Establecimiento } from "./establecimiento.model";
import { Evento } from "./evento.model";
import { Asiento } from "./asiento.model";

export interface EventoDetalle {

  titulo: string;
  fecha: string;
  valoracion: number;
  descripcion: string;
  establecimiento: {
    idEst: number;
    nombre: string;
    ubicacion: string;
    imagen: string;
    tipo: string;
    asientos?: any[]; // puedes ajustar este tipo
  };
  evento: Evento;
  asientos?: any[]; // o Asiento[], si tienes el modelo
}
