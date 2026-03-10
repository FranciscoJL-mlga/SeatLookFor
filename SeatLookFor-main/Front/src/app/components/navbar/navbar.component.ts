import { Component, HostListener, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Router, RouterModule } from '@angular/router';
import { AuthService } from '../../services/auth.service';
import { Usuario } from '../../models/usuario.model';

@Component({
  selector: 'app-navbar',
  standalone: true,
  imports: [CommonModule, RouterModule],
  templateUrl: './navbar.component.html',
  styleUrl: './navbar.component.css'
})
export class NavbarComponent implements OnInit {
  isUserMenuOpen = false;
  currentUser: Usuario | null = null;

  constructor(
    private auth: AuthService,
    private router: Router
  ) {}

  ngOnInit() {
    this.auth.currentUser$.subscribe(user => {
      this.currentUser = user;
    });
  }

  toggleUserMenu() {
    this.isUserMenuOpen = !this.isUserMenuOpen;
  }

  @HostListener('document:click', ['$event'])
  onDocumentClick(event: MouseEvent) {
    const userIcon = document.querySelector('.user-icon');
    if (userIcon && !userIcon.contains(event.target as Node)) {
      this.isUserMenuOpen = false;
    }
  }

  logout() {
    console.log('logout');
    this.auth.logout();
  }

  navigateToUserOrRegister() {
    if (this.auth.isLoggedIn()) {
      this.router.navigate(['/usuario']);
    } else {
      this.router.navigate(['/registro']);
    }
  }
}
