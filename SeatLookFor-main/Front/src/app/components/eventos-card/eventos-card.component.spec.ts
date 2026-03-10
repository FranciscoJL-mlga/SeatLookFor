import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EventosCardComponent } from './eventos-card.component';

describe('EventosCardComponent', () => {
  let component: EventosCardComponent;
  let fixture: ComponentFixture<EventosCardComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [EventosCardComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(EventosCardComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
