import { Asiento } from './asiento.model';

export interface Establecimiento {
  idEst: number;
  nombre: string;
  ubicacion: string;
  imagen: string;
  tipo: string;
  asientos: Asiento[];
}
