import { Component, OnInit, AfterViewInit, OnDestroy } from '@angular/core';
import { NavbarComponent } from '../navbar/navbar.component';
import { FooterComponent } from '../footer/footer.component';
import { CardService } from '../../services/card.service';
import { CommonModule } from '@angular/common';
import { Evento } from '../../models/evento.model';
import { RouterLink } from '@angular/router';
import { AcortarDescripcionPipe } from './acortar-descripcion.pipe';


@Component({
  selector: 'app-landing-page',
  imports: [NavbarComponent, FooterComponent, CommonModule, RouterLink, AcortarDescripcionPipe],
  templateUrl: './landing-page.component.html',
  styleUrl: './landing-page.component.css'
})
export class LandingPageComponent implements OnInit {
  animatedText = "Nuestras Recomendaciones"
  cards: Evento[] = [];
  letters: string[] = [];

  constructor(private cardService: CardService) { }

  /* ngOnInit() {
    this.letters = this.animatedText.split('');
  } */

  ngOnInit() {
    this.fetchCards();
  }

  seClico() {
    console.log("se ha clicado el boton para ir a reservas");

  }

  fetchCards() {
    const baseUrl = 'http://localhost'; 

    this.cardService.getCards().subscribe(
      (data) => {
        this.cards = data.map(evento => ({

          ...evento,
          portada: `${baseUrl}/${evento.portada}`
        }));

        console.log('Cards con URL de imagen:', this.cards);
      },
      (error) => {
        console.error('Error al obtener los eventos:', error);
      }
    );
  }
}
