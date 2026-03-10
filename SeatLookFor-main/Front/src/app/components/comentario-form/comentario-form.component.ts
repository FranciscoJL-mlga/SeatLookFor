import { Component, Input, Output, EventEmitter } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule, FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ComentarioService } from '../../services/comentario.service';

@Component({
  selector: 'app-comentario-form',
  imports: [CommonModule, FormsModule, ReactiveFormsModule],
  template: `
    <div class="comment-form" *ngIf="asientoSeleccionado">
      <h3>Añadir comentario para el asiento {{asientoSeleccionado.zona}} - {{asientoSeleccionado.ejeX}}-{{asientoSeleccionado.ejeY}}</h3>
      <form [formGroup]="comentarioForm" (ngSubmit)="onSubmit()">
        <div class="comment-form__group">
          <label for="opinion">Opinión:</label>
          <textarea id="opinion" formControlName="opinion" rows="4"></textarea>
          <div class="comment-form__error" *ngIf="comentarioForm.get('opinion')?.errors?.['required'] && comentarioForm.get('opinion')?.touched">
            La opinión es requerida
          </div>
        </div>

        <div class="comment-form__group">
          <label for="valoracion">Valoración:</label>
          <div class="comment-form__stars">
            <span *ngFor="let estrella of [1,2,3,4,5]" 
                  (click)="setValoracion(estrella)"
                  [class.comment-form__star--active]="comentarioForm.get('valoracion')?.value >= estrella">
              ⭐
            </span>
          </div>
          <div class="comment-form__error" *ngIf="comentarioForm.get('valoracion')?.errors?.['required'] && comentarioForm.get('valoracion')?.touched">
            La valoración es requerida
          </div>
        </div>

        <div class="comment-form__group">
          <label for="foto">Foto del asiento:</label>
          <input type="file" id="foto" (change)="onFileSelected($event)" accept="image/*">
          <div class="comment-form__preview" *ngIf="previewUrl">
            <img [src]="previewUrl" alt="Vista previa">
          </div>
        </div>

        <button type="submit" [disabled]="!comentarioForm.valid || !selectedFile">
          Enviar comentario
        </button>
      </form>
    </div>
  `,
  styles: [`
    .comment-form {
      background: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      margin-top: 20px;
    }

    .comment-form__group {
      margin-bottom: 15px;
    }

    label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }

    textarea {
      width: 100%;
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 4px;
    }

    .comment-form__stars {
      display: flex;
      gap: 5px;
      font-size: 24px;
      cursor: pointer;
    }

    .comment-form__stars span {
      opacity: 0.3;
      transition: all 0.3s ease;
      cursor: pointer;
      user-select: none;
    }

    .comment-form__stars span:hover {
      opacity: 0.7;
    }

    .comment-form__star--active {
      opacity: 1 !important;
      transform: scale(1.1);
    }

    .comment-form__preview {
      margin-top: 10px;
    }

    .comment-form__preview img {
      max-width: 200px;
      max-height: 150px;
      border-radius: 4px;
    }

    button {
      background-color: var(--primary-color);
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    button:disabled {
      background-color: #ccc;
      cursor: not-allowed;
    }

    .comment-form__error {
      color: red;
      font-size: 12px;
      margin-top: 5px;
    }
  `]
})
export class ComentarioFormComponent {
  @Input() asientoSeleccionado: any;
  @Output() comentarioCreado = new EventEmitter<void>();

  comentarioForm: FormGroup;
  selectedFile: File | null = null;
  previewUrl: string | null = null;

  constructor(
    private fb: FormBuilder,
    private comentarioService: ComentarioService
  ) {
    this.comentarioForm = this.fb.group({
      opinion: ['', Validators.required],
      valoracion: [0, [Validators.required, Validators.min(1), Validators.max(5)]]
    });
  }

  setValoracion(valor: number) {
    this.comentarioForm.get('valoracion')?.setValue(valor);
    this.comentarioForm.get('valoracion')?.markAsTouched();
  }

  private async compressImage(file: File): Promise<string> {
    return new Promise((resolve, reject) => {
      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = (event) => {
        const img = new Image();
        img.src = event.target?.result as string;
        img.onload = () => {
          const canvas = document.createElement('canvas');
          const MAX_WIDTH = 800;
          const MAX_HEIGHT = 800;
          let width = img.width;
          let height = img.height;

          if (width > height) {
            if (width > MAX_WIDTH) {
              height *= MAX_WIDTH / width;
              width = MAX_WIDTH;
            }
          } else {
            if (height > MAX_HEIGHT) {
              width *= MAX_HEIGHT / height;
              height = MAX_HEIGHT;
            }
          }

          canvas.width = width;
          canvas.height = height;
          const ctx = canvas.getContext('2d');
          ctx?.drawImage(img, 0, 0, width, height);
          
          // Comprimir la imagen con calidad 0.7
          const compressedImage = canvas.toDataURL('image/jpeg', 0.7);
          resolve(compressedImage);
        };
        img.onerror = reject;
      };
      reader.onerror = reject;
    });
  }

  async onFileSelected(event: any) {
    const file = event.target.files[0];
    if (file) {
      try {
        this.selectedFile = file;
        // Comprimir la imagen antes de crear la vista previa
        this.previewUrl = await this.compressImage(file);
      } catch (error) {
        console.error('Error al procesar la imagen:', error);
        alert('Error al procesar la imagen');
      }
    }
  }

  onSubmit() {
    if (this.comentarioForm.valid) {
      const comentario = {
        opinion: this.comentarioForm.get('opinion')?.value,
        valoracion: this.comentarioForm.get('valoracion')?.value,
        foto: this.previewUrl || undefined
      };

      console.log('Enviando comentario:', comentario);

      this.comentarioService.crearComentario(this.asientoSeleccionado.idAsi, comentario).subscribe({
        next: (response) => {
          console.log('Comentario creado:', response);
          this.comentarioCreado.emit();
          this.comentarioForm.reset();
          this.selectedFile = null;
          this.previewUrl = null;
        },
        error: (error) => {
          console.error('Error al crear el comentario:', error);
          alert(error.error?.mensaje || 'Error al crear el comentario');
        }
      });
    }
  }
} 