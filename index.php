<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CitaManager - Agenda tus citas fácilmente</title>
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
            
            /* Estilos para el chat */
            .chat-container {
                @apply fixed bottom-6 right-6 z-50;
            }
            .chat-button {
                @apply w-14 h-14 bg-primary rounded-full flex items-center justify-center text-white shadow-lg cursor-pointer transition-all duration-300 hover:bg-blue-700 hover:scale-110;
            }
            .chat-window {
                @apply absolute bottom-16 right-0 w-80 h-96 bg-white rounded-lg shadow-xl flex flex-col transition-all duration-300 opacity-0 pointer-events-none;
            }
            .chat-window.open {
                @apply opacity-100 pointer-events-auto;
            }
            .chat-header {
                @apply bg-primary text-white p-4 rounded-t-lg flex justify-between items-center;
            }
            .chat-messages {
                @apply flex-1 p-4 overflow-y-auto;
            }
            .message {
                @apply mb-4;
            }
            .bot-message {
                @apply bg-gray-100 rounded-lg p-3 max-w-xs;
            }
            .user-message {
                @apply bg-primary text-white rounded-lg p-3 ml-auto max-w-xs;
            }
            .chat-input {
                @apply p-3 border-t flex;
            }
            .quick-replies {
                @apply flex flex-wrap gap-2 p-3 border-t bg-gray-50;
            }
            .quick-reply {
                @apply bg-gray-200 hover:bg-gray-300 text-gray-800 text-xs px-3 py-1 rounded-full cursor-pointer transition-colors;
            }
        }
    </style>
</head>
<body class="font-body bg-gray-50">
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

    <!-- Hero Section with Carousel -->
    <div class="pt-16 relative">
        <div class="relative h-[500px] overflow-hidden">
            <!-- Slide 1 -->
            <div class="absolute inset-0 transition-opacity duration-1000 ease-in-out opacity-100">
                <img src="./images/restaurant.webp" class="w-full h-full object-cover" alt="Restaurantes">
                <div class="absolute inset-0 bg-black bg-opacity-40"></div>
                <div class="absolute inset-0 flex items-center justify-center text-center px-4">
                    <div class="text-white max-w-3xl">
                        <h2 class="text-4xl md:text-5xl font-bold mb-4 font-heading">Restaurantes</h2>
                        <p class="text-xl mb-8">¿Para qué hacer la cena hoy si puedes ir a comer en un restaurante?</p>
                        
                    </div>
                </div>
            </div>
            
            <!-- Navigation arrows -->
            <button class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-20 text-white p-2 rounded-full hover:bg-opacity-30 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-20 text-white p-2 rounded-full hover:bg-opacity-30 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            
            <!-- Indicators -->
            <div class="absolute bottom-4 left-0 right-0 flex justify-center space-x-2">
                <button class="w-3 h-3 rounded-full bg-white"></button>
                <button class="w-3 h-3 rounded-full bg-white bg-opacity-50"></button>
                <button class="w-3 h-3 rounded-full bg-white bg-opacity-50"></button>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <section class="py-16 bg-light">
        <div class="container mx-auto px-4">
            <h2 class="section-title">¿Por qué elegir CitaManager?</h2>
            <p class="section-subtitle">Simplificamos la gestión de citas para que puedas concentrarte en lo que realmente importa</p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
                <!-- Feature 1 -->
                <div class="card p-6 text-center">
                    <div class="flex justify-center mb-4">
                        <div class="bg-blue-100 p-4 rounded-full">
                            <img width="48" height="48" src="https://img.icons8.com/pulsar-color/48/search-more.png" alt="search-more"/>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-dark mb-3 font-heading">Agenda eficiente</h3>
                    <p class="text-gray-600">Elige tu hora preferida y solicita cita sin llamar. Fácil, cómodo y rápido.</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="card p-6 text-center">
                    <div class="flex justify-center mb-4">
                        <div class="bg-green-100 p-4 rounded-full">
                            <img width="48" height="48" src="https://img.icons8.com/pulsar-color/48/web-account.png" alt="web-account"/>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-dark mb-3 font-heading">Encuentra especialistas</h3>
                    <p class="text-gray-600">Accede a profesionales calificados en diferentes sectores con solo unos clics.</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="card p-6 text-center">
                    <div class="flex justify-center mb-4">
                        <div class="bg-amber-100 p-4 rounded-full">
                            <img width="48" height="48" src="https://img.icons8.com/pulsar-color/48/clock.png" alt="clock"/>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-dark mb-3 font-heading">Ahorra tiempo</h3>
                    <p class="text-gray-600">Olvídate de las esperas telefónicas y agenda en el momento que prefieras.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <h2 class="section-title">Nuestros servicios</h2>
            <p class="section-subtitle">Descubre una amplia gama de establecimientos disponibles para agendar</p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
                <!-- Category 1 -->
                <div class="card group relative overflow-hidden h-80">
                    <img src="./images/restaurant.webp" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="Restaurantes">
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-end">
                        <div class="p-6 text-white">
                            <h3 class="text-2xl font-bold mb-2 font-heading">Restaurantes</h3>
                            <p class="mb-4">Reserva en los mejores restaurantes de tu ciudad</p>
                            <a href="./404.php" class="text-white font-medium flex items-center">
                                Explorar
                                <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Category 2 -->
                <div class="card group relative overflow-hidden h-80">
                    <img src="./images/doctor1.webp" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="Sector Salud">
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-end">
                        <div class="p-6 text-white">
                            <h3 class="text-2xl font-bold mb-2 font-heading">Sector Salud</h3>
                            <p class="mb-4">Profesionales de la salud dispuestos a ayudarte</p>
                            <a href="./404.php" class="text-white font-medium flex items-center">
                                Explorar
                                <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Category 3 -->
                <div class="card group relative overflow-hidden h-80">
                    <img src="./images/peluqueria.webp" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="Belleza y cuidado personal">
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-end">
                        <div class="p-6 text-white">
                            <h3 class="text-2xl font-bold mb-2 font-heading">Belleza y cuidado</h3>
                            <p class="mb-4">¿Quieres tener un pequeño cambio?</p>
                            <a href="./404.php" class="text-white font-medium flex items-center">
                                Explorar
                                <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-primary">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6 font-heading">¿Listo para agendar tu cita?</h2>
            <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">Regístrate ahora y comienza a disfrutar de una gestión de citas sin complicaciones</p>
            <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="registrarse.php" class="btn-secondary">Crear cuenta</a>
                <a href="inicio_sesion.php" class="bg-white text-primary hover:bg-gray-100 font-bold py-3 px-6 rounded-lg transition-all duration-300">Iniciar sesión</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-12">
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

    <!-- Chat Widget -->
    <div class="chat-container">
        <div class="chat-button" id="chat-toggle">
            <i class="fas fa-comments text-xl"></i>
        </div>
        <div class="chat-window" id="chat-window">
            <div class="chat-header">
                <h3 class="font-bold">Asistente Virtual</h3>
                <button id="chat-close" class="text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="chat-messages" id="chat-messages">
                <div class="message">
                    <div class="bot-message">
                        ¡Hola! 👋 Soy tu asistente de CitaManager. ¿En qué puedo ayudarte hoy?
                    </div>
                </div>
            </div>
            <div class="quick-replies" id="quick-replies">
                <div class="quick-reply" data-message="¿Cómo agendo una cita?">¿Cómo agendo una cita?</div>
                <div class="quick-reply" data-message="Tipos de servicios">Tipos de servicios</div>
                <div class="quick-reply" data-message="Registrarme">Registrarme</div>
                <div class="quick-reply" data-message="Contactar soporte">Contactar soporte</div>
            </div>
            <div class="chat-input">
                <input type="text" id="chat-input" placeholder="Escribe tu mensaje..." class="flex-1 border rounded-l-lg p-2 focus:outline-none focus:ring-2 focus:ring-primary">
                <button id="chat-send" class="bg-primary text-white px-4 rounded-r-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu functionality -->
    <script>
        document.getElementById('menu-toggle').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });

        // Chat functionality
        const chatToggle = document.getElementById('chat-toggle');
        const chatWindow = document.getElementById('chat-window');
        const chatClose = document.getElementById('chat-close');
        const chatMessages = document.getElementById('chat-messages');
        const chatInput = document.getElementById('chat-input');
        const chatSend = document.getElementById('chat-send');
        const quickReplies = document.querySelectorAll('.quick-reply');

        // Respuestas predeterminadas del chatbot
        const responses = {
            "hola": "¡Hola! 😊 ¿En qué puedo ayudarte hoy? Puedo ayudarte con información sobre cómo agendar citas, tipos de servicios disponibles, registro en la plataforma y más.",
            "¿cómo estás?": "¡Estoy muy bien, gracias por preguntar! Listo para ayudarte con todo lo que necesites sobre CitaManager.",
            "adiós": "¡Hasta luego! 👋 No dudes en volver si tienes más preguntas. ¡Que tengas un excelente día!",
            "gracias": "¡De nada! 😊 Estoy aquí para ayudarte cuando lo necesites. ¿Hay algo más en lo que pueda asistirte?",
            "¿qué es citamanager?": "CitaManager es una plataforma que te permite agendar citas de manera fácil y rápida en diferentes tipos de establecimientos: restaurantes, centros de salud, barberías, estéticas y más. Simplificamos el proceso de reserva para que ahorres tiempo.",
            "¿cómo agendo una cita?": "Para agendar una cita: 1) Regístrate o inicia sesión, 2) Selecciona el tipo de servicio que necesitas, 3) Elige el establecimiento de tu preferencia, 4) Selecciona fecha y hora disponibles, 5) Confirma tu cita. ¡Es así de fácil!",
            "tipos de servicios": "En CitaManager encontrarás: 🍕 Restaurantes, 🏥 Servicios de salud (médicos, dentistas, etc.), 💇 Barberías y estéticas, 🧘 Bienestar y spas, y pronto más categorías. ¿Te interesa alguno en particular?",
            "registrarme": "¡Excelente decisión! Para registrarte, haz clic en el botón 'Registrarse' en la parte superior de la página. Solo necesitarás proporcionar tu nombre, email y crear una contraseña. El proceso es rápido y gratuito.",
            "contactar soporte": "Puedes contactar a nuestro equipo de soporte por: 📧 Email: soporte@citamanager.com 📞 Teléfono: +1 234 567 890 💬 Chat en vivo (como este) en horario de 9am a 6pm. Estamos aquí para ayudarte.",
            "precios": "¡Registrarse y usar la plataforma CitaManager es completamente gratuito para quienes buscan agendar citas! Los establecimientos pagan una comisión por cada cita confirmada. No hay sorpresas ni costos ocultos para los usuarios.",
            "cancelar cita": "Para cancelar una cita: 1) Inicia sesión en tu cuenta, 2) Ve a 'Mis Citas', 3) Encuentra la cita que deseas cancelar, 4) Haz clic en 'Cancelar Cita'. Te recomendamos cancelar con al menos 24 horas de anticipación.",
            "problemas técnicos": "Lamento escuchar que tienes problemas técnicos. Por favor, intenta: 1) Recargar la página, 2) Limpiar caché del navegador, 3) Probar en otro navegador. Si el problema persiste, contacta a nuestro soporte técnico.",
            "olvidé mi contraseña": "No te preocupes, podemos ayudarte. Haz clic en 'Iniciar Sesión' y luego en '¿Olvidaste tu contraseña?'. Ingresa tu email y te enviaremos un enlace para restablecerla. Revisa también tu carpeta de spam si no ves el email.",
            "seguridad": "En CitaManager tomamos la seguridad muy en serio. Tus datos personales están protegidos con encriptación SSL, no compartimos tu información con terceros sin tu consentimiento, y cumplimos con las regulaciones de protección de datos.",
            "empresa": "¿Tienes un establecimiento y quieres unirte a CitaManager? ¡Genial! Regístrate como establecimiento en nuestra página y nuestro equipo se pondrá en contacto contigo para explicarte los beneficios y el proceso de integración.",
            "disponibilidad": "La disponibilidad de citas varía según cada establecimiento. Cuando selecciones un establecimiento, podrás ver en tiempo real las fechas y horarios disponibles para agendar. Los establecimientos actualizan constantemente su disponibilidad.",
            "promociones": "¡Sí! Algunos establecimientos ofrecen promociones especiales exclusivas para usuarios de CitaManager. Mantente atento a tu email y a nuestras redes sociales para no perderte ninguna oferta especial."
        };

        // Alternativas para las preguntas comunes
        const alternativePhrasings = {
            "hola": ["hola", "hi", "hello", "buenas", "saludos", "qué tal"],
            "¿cómo estás?": ["cómo estás", "qué tal estás", "cómo te va"],
            "adiós": ["adiós", "chao", "hasta luego", "nos vemos", "bye"],
            "gracias": ["gracias", "thank you", "thanks", "agradecido", "te lo agradezco"],
            "¿qué es citamanager?": ["qué es citamanager", "qué hacen", "para qué sirve", "qué ofrece"],
            "¿cómo agendo una cita?": ["cómo agendo", "agendar cita", "reservar cita", "cómo reservo", "proceso de cita"],
            "tipos de servicios": ["qué servicios", "tipos de servicios", "qué categorías", "qué ofrecen"],
            "registrarme": ["registrarme", "crear cuenta", "cómo me registro", "quiero registrarme"],
            "contactar soporte": ["soporte", "contactar", "ayuda", "problema", "asistencia"],
            "precios": ["precios", "cuánto cuesta", "es gratuito", "costos", "tarifas"],
            "cancelar cita": ["cancelar cita", "anular cita", "eliminar cita", "borrar cita"],
            "problemas técnicos": ["problemas técnicos", "no funciona", "error", "bug", "fallo"],
            "olvidé mi contraseña": ["olvidé contraseña", "recuperar contraseña", "no recuerdo contraseña"],
            "seguridad": ["seguridad", "protección de datos", "privacidad", "datos personales"],
            "empresa": ["empresa", "establecimiento", "negocio", "unirme", "colaborar"],
            "disponibilidad": ["disponibilidad", "horarios", "cuándo hay", "fechas disponibles"],
            "promociones": ["promociones", "descuentos", "ofertas", "cupones"]
        };

        // Función para encontrar la respuesta adecuada
        function findResponse(message) {
            const lowerMessage = message.toLowerCase().trim();
            
            // Buscar coincidencias directas
            for (const key in alternativePhrasings) {
                if (alternativePhrasings[key].some(phrase => lowerMessage.includes(phrase))) {
                    return responses[key];
                }
            }
            
            // Si no encuentra coincidencia
            return "Lo siento, no entendí tu pregunta. ¿Podrías reformularla? Puedo ayudarte con información sobre agendar citas, tipos de servicios, registro, soporte técnico y más.";
        }

        // Alternar visibilidad del chat
        chatToggle.addEventListener('click', () => {
            chatWindow.classList.toggle('open');
        });

        chatClose.addEventListener('click', () => {
            chatWindow.classList.remove('open');
        });

        // Enviar mensaje
        function sendMessage() {
            const message = chatInput.value.trim();
            if (message) {
                // Agregar mensaje del usuario
                addMessage(message, 'user');
                chatInput.value = '';
                
                // Simular respuesta después de un breve retraso
                setTimeout(() => {
                    const response = findResponse(message);
                    addMessage(response, 'bot');
                    
                    // Desplazar hacia el último mensaje
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }, 500);
            }
        }

        // Agregar mensaje al chat
        function addMessage(text, sender) {
            const messageDiv = document.createElement('div');
            messageDiv.className = 'message';
            
            const contentDiv = document.createElement('div');
            contentDiv.className = sender === 'bot' ? 'bot-message' : 'user-message';
            contentDiv.textContent = text;
            
            messageDiv.appendChild(contentDiv);
            chatMessages.appendChild(messageDiv);
            
            // Desplazar hacia el último mensaje
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Event listeners
        chatSend.addEventListener('click', sendMessage);
        chatInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });

        // Quick replies
        quickReplies.forEach(reply => {
            reply.addEventListener('click', () => {
                const message = reply.getAttribute('data-message');
                chatInput.value = message;
                sendMessage();
            });
        });
    </script>
</body>
</html>