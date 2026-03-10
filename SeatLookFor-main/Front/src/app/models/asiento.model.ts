import { Comentario } from './comentario.model';

export interface Asiento {
  idAsi: number;
  zona: string;
  estado: string;
  ejeX: number;
  ejeY: number;
  precio: number;
  imagen?: string;
  comentarios?: Comentario[];
}
