import { ComponentFixture, TestBed } from '@angular/core/testing';

import { TodosEventosComponent } from './todos-eventos.component';

describe('TodosEventosComponent', () => {
  let component: TodosEventosComponent;
  let fixture: ComponentFixture<TodosEventosComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [TodosEventosComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(TodosEventosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
