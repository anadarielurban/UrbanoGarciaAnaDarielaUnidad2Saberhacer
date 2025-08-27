<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página no encontrada - CitaManager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&family=Raleway:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="https://img.icons8.com/windows/32/000000/baby-calendar.png" type="image/x-icon">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                        secondary: '#10B981',
                        dark: '#1F2937',
                        light: '#F9FAFB',
                    },
                    fontFamily: {
                        heading: ['Raleway', 'sans-serif'],
                        body: ['Quicksand', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        @layer components {
            .btn-primary {
                @apply bg-primary hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 transform hover:-translate-y-1;
            }
            .btn-secondary {
                @apply bg-secondary hover:bg-emerald-600 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300;
            }
            .btn-outline {
                @apply border-2 border-primary text-primary hover:bg-primary hover:text-white font-bold py-3 px-6 rounded-lg transition-all duration-300;
            }
            .card {
                @apply bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl;
            }
            .section-title {
                @apply text-3xl md:text-4xl font-bold text-center text-dark mb-2 font-heading;
            }
            .section-subtitle {
                @apply text-lg text-gray-600 text-center max-w-3xl mx-auto mb-12 font-body;
            }
        }
    </style>
</head>
<body class="font-body bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <!-- Navbar -->
    <nav class="fixed w-full bg-white shadow-md z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <a href="./index.php" class="flex items-center space-x-2 text-primary font-bold text-xl font-heading">
                    <img class="w-8 h-8" src="https://img.icons8.com/windows/32/000000/baby-calendar.png" alt="CitaManager Logo">
                    <span>CitaManager</span>
                </a>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="registrarse.php" class="text-gray-600 hover:text-primary transition-colors duration-300">¿Tienes un establecimiento?</a>
                    <a href="inicio_sesion.php" class="text-gray-600 hover:text-primary transition-colors duration-300">Inicia Sesión</a>
                    <a href="registrarse.php" class="btn-outline">Registrarse</a>
                    <a href="inicio_sesion.php" class="btn-primary">Agendar Cita</a>
                </div>
                
                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button id="menu-toggle" class="text-gray-600 hover:text-primary focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden pb-4">
                <a href="registrarse.php" class="block py-2 text-gray-600 hover:text-primary">¿Tienes un establecimiento?</a>
                <a href="inicio_sesion.php" class="block py-2 text-gray-600 hover:text-primary">Inicia Sesión</a>
                <a href="registrarse.php" class="block py-2 text-gray-600 hover:text-primary">Registrarse</a>
                <a href="inicio_sesion.php" class="block py-2 text-primary font-medium">Agendar Cita</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-32 pb-12 px-4">
        <div class="container mx-auto">
            <!-- 404 Content -->
            <div class="max-w-4xl mx-auto text-center">
                <!-- Animated 404 Number -->
                <div class="mb-8">
                    <div class="text-9xl font-bold text-primary font-heading">
                        <span class="inline-block animate-bounce">4</span>
                        <span class="inline-block animate-pulse">0</span>
                        <span class="inline-block animate-bounce">4</span>
                    </div>
                </div>
                
                <!-- Message -->
                <h1 class="section-title">¡Ups! Página no encontrada</h1>
                <p class="section-subtitle">La página que estás buscando parece haberse esfumado. No te preocupes, podemos ayudarte a encontrar lo que necesitas.</p>
                
                <!-- Actions -->
                <div class="flex flex-col sm:flex-row justify-center gap-4 mt-12">
                    <a href="./index.php" class="btn-primary">
                        <i class="fas fa-home mr-2"></i>Volver al Inicio
                    </a>
                    <a href="./inicio_sesion.php" class="btn-secondary">
                        <i class="fas fa-calendar-check mr-2"></i>Agendar Cita
                    </a>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-12 mt-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <a href="./index.php" class="flex items-center space-x-2 text-white font-bold text-xl font-heading mb-4">
                        <img class="w-8 h-8" src="https://img.icons8.com/windows/32/ffffff/baby-calendar.png" alt="CitaManager Logo">
                        <span>CitaManager</span>
                    </a>
                    <p class="text-gray-400 mb-4">La forma más sencilla de gestionar tus citas en línea.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-bold mb-4 font-heading">Enlaces rápidos</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Inicio</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Acerca de</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Servicios</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Contacto</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-bold mb-4 font-heading">Servicios</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Restaurantes</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Salud</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Belleza</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Más categorías</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-bold mb-4 font-heading">Contacto</h3>
                    <ul class="space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-3 text-gray-400"></i>
                            <span class="text-gray-400">123 Calle Principal, Ciudad</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone-alt mr-3 text-gray-400"></i>
                            <span class="text-gray-400">+1 234 567 890</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-3 text-gray-400"></i>
                            <span class="text-gray-400">info@citamanager.com</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm">© 2023 CitaManager. Todos los derechos reservados.</p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">Política de privacidad</a>
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">Términos de servicio</a>
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">FAQ</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile menu functionality -->
    <script>
        document.getElementById('menu-toggle').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });
    </script>
</body>
</html>