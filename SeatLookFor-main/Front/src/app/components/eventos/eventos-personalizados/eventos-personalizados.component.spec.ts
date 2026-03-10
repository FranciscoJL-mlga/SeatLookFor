import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EventosPersonalizadosComponent } from './eventos-personalizados.component';

describe('EventosPersonalizadosComponent', () => {
  let component: EventosPersonalizadosComponent;
  let fixture: ComponentFixture<EventosPersonalizadosComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [EventosPersonalizadosComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(EventosPersonalizadosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
