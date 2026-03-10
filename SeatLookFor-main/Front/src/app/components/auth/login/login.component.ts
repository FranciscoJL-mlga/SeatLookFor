import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { RouterModule } from '@angular/router';
import { AuthService } from '../../../services/auth.service';
// import { NavbarComponent } from '../../navbar/navbar.component';

@Component({
  selector: 'app-login',
  imports: [
    CommonModule,
    ReactiveFormsModule,
    RouterModule,
    // NavbarComponent,
  ],
  templateUrl: './login.component.html',
  styleUrl: './login.component.css'
})
export class LoginComponent {
  loginForm: FormGroup;
  errorMessage = '';
  loading = false;
  rutaAdmin: string = "https://seatlookadmin.duckdns.org/login";
  // rutaAdmin: string = "http://localhost/login";

  constructor(
    private fb: FormBuilder,
    private auth: AuthService
  ) {
    this.loginForm = this.fb.group({
      email: ['', [Validators.required, Validators.email]],
      password: ['', [Validators.required]]
    });
    console.log('LoginComponent inicializado');
  }

  onSubmit() {
    if (this.loginForm.valid) {
      console.log(this.loginForm.value);
      this.loading = true;
      this.errorMessage = '';

      console.log('Datos de login:', { email: this.loginForm.value.email, passwordLength: this.loginForm.value.password.length });

      this.auth.login(this.loginForm.value.email, this.loginForm.value.password).subscribe({
        next: (res) => {
          console.log('Login exitoso, respuesta:', res);
          this.loading = false;
          // La redirección se maneja en el servicio
        },
        error: (err) => {
          console.error('Error en login:', err);
          this.loading = false;
          
          if (err.status === 419) {
            this.errorMessage = 'Error de autenticación CSRF. Por favor, recarga la página e intenta nuevamente.';
          } else if (err.status === 401) {
            this.errorMessage = 'Credenciales incorrectas';
          } else if (err.status === 0) {
            this.errorMessage = 'No se pudo conectar con el servidor';
          } else {
            this.errorMessage = err.error?.message || 'Error al iniciar sesión';
          }
          console.log('Mensaje de error mostrado:', this.errorMessage);
        }
      });
    }
  }
}
