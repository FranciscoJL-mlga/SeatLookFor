export interface Reserva {
    idRes?: number;
    precio?: number;
    descuento?: number;
    fechaReserva: string;
    idAsientos: number[];
    // idUsu: number;
    idEve: number;
    totalPrecio: number;
}
