<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>seatLookFor</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-purple-900 to-purple-600 text-white py-4">
        <div class="container mx-auto flex items-center justify-between">
            <a href="#" class="text-4xl font-cursive text-white">seatlookfor</a>
            <form class="flex-grow mx-8">
                <input type="search" placeholder="Buscar eventos, lugares..." class="w-full py-2 px-4 rounded-full shadow-md">
            </form>
            <button class="text-white text-2xl">
                <i class="bi bi-person-circle"></i>
            </button>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="min-h-screen bg-amber-50 py-20">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="bg-purple-900 text-white py-8">
        <div class="container mx-auto text-center">
            <div class="grid grid-cols-3 gap-8 mb-8">
                <div><h5>Encuéntranos</h5></div>
                <div><h5>Eventos</h5></div>
                <div><h5>Sobre seatLookFor</h5></div>
            </div>
            <div class="flex justify-center space-x-6">
                <a href="#" class="text-white text-2xl"><i class="bi bi-instagram"></i></a>
                <a href="#" class="text-white text-2xl"><i class="bi bi-linkedin"></i></a>
                <a href="#" class="text-white text-2xl"><i class="bi bi-twitter"></i></a>
                <a href="#" class="text-white text-2xl"><i class="bi bi-facebook"></i></a>
            </div>
            <hr class="border-t border-white my-4">
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>
<script type="module" src="/resources/js/app.js"></script>
</body>
</html>
