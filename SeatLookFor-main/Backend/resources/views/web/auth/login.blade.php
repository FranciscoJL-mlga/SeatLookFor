@extends('web.layouts.app')

@section('title', 'Iniciar Sesión - SeatLookFor')

@section('content')

<div class="auth-container">

    <a href="{{ route('home') }}" class="home-button">
        ← Inicio
    </a>

    <div class="auth-card">

        {{-- Logo / Brand --}}
        <div style="text-align:center;margin-bottom:8px;">
            <span style="font-family:'Poppins',sans-serif;font-size:13px;font-weight:600;
                         text-transform:uppercase;letter-spacing:3px;color:var(--text-dim);">
                SeatLookFor
            </span>
        </div>

        <h2>Bienvenido de nuevo</h2>

        {{-- Validation errors --}}
        @if($errors->any())
            <div class="error-message"
                 style="margin-bottom:1.25rem;padding:12px 16px;background:rgba(239,68,68,0.1);
                        border:1px solid rgba(239,68,68,0.25);border-radius:8px;">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}" class="auth-form">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email"
                       id="email"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       autofocus
                       placeholder="tu@email.com"
                       class="{{ $errors->has('email') ? 'error' : '' }}">
                @if($errors->has('email'))
                    <span class="error-message">{{ $errors->first('email') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password"
                       id="password"
                       name="password"
                       required
                       placeholder="Tu contraseña"
                       class="{{ $errors->has('password') ? 'error' : '' }}">
                @if($errors->has('password'))
                    <span class="error-message">{{ $errors->first('password') }}</span>
                @endif
            </div>

            <button type="submit" class="submit-button">
                Iniciar Sesión
            </button>

            <div class="auth-links">
                <p>¿No tienes una cuenta? <a href="{{ route('registro') }}">Regístrate</a></p>
            </div>

        </form>
    </div>
</div>

@endsection
