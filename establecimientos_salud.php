<?php
session_start();
require_once 'dbconexion.php';

if($_SESSION['act'] =="no"){
    header('location:index.php');
    exit();
}
if (isset($_POST['cerrar_sesion'])){
    session_destroy();
    header('location:index.php');
    exit();
}

// Procesar búsqueda si se envió el formulario
$terminoBusqueda = '';
$resultados = [];
if (isset($_GET['buscar'])) {
    $terminoBusqueda = trim($_GET['buscar']);
    
    if (!empty($terminoBusqueda)) {
        // Simular búsqueda en base de datos (aquí deberías conectar con tu BD real)
        $establecimientos = [
            [
                'nombre' => 'Farmacias Similares',
                'ubicacion' => 'Ubicados en Zona Centro',
                'horario' => 'Lunes a Viernes: 12pm - 12am',
                'email' => 'simisoff@gmail.com',
                'telefono' => '844-111-3456',
                'tipo' => 'farmacia'
            ],
            [
                'nombre' => 'Farmacias Guadalajara',
                'ubicacion' => 'Venustiano Carranza',
                'horario' => 'Lunes a Viernes: 10pm - 8am',
                'email' => 'farmaciasg65094@gmail.com',
                'telefono' => '844-987-6677',
                'tipo' => 'farmacia'
            ],
            [
                'nombre' => 'Farmacias del Ahorro',
                'ubicacion' => 'Colonia Morelos',
                'horario' => 'Sábados a Domingo: 1pm - 6pm',
                'email' => 'ahorrrosfarm0@gmail.com',
                'telefono' => '844-564-300',
                'tipo' => 'farmacia'
            ]
        ];
        
        // Filtrar resultados por similitud en el nombre
        foreach ($establecimientos as $establecimiento) {
            if (stripos($establecimiento['nombre'], $terminoBusqueda) !== false) {
                $resultados[] = $establecimiento;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Establecimientos de Salud - CitaManager</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&family=Raleway:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="shortcut icon" href="https://img.icons8.com/windows/32/000000/baby-calendar.png" type="image/x-icon">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                        secondary: '#10B981',
                        accent: '#F59E0B',
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
                @apply bg-primary hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300;
            }
            .btn-secondary {
                @apply bg-secondary hover:bg-emerald-600 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300;
            }
            .btn-outline {
                @apply border-2 border-primary text-primary hover:bg-primary hover:text-white font-bold py-2 px-4 rounded-lg transition-all duration-300;
            }
            .btn-danger {
                @apply bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition-all duration-300;
            }
            .card {
                @apply bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl;
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
                
                <div class="hidden md:flex items-center space-x-3">
                    <span class="text-gray-600 text-sm">
                        <i class="fas fa-user mr-1"></i><?php echo $_SESSION['username']; ?>
                    </span>
                    <span class="text-gray-400">|</span>
                    <span class="text-gray-600 text-sm">
                        <i class="fas fa-envelope mr-1"></i><?php echo $_SESSION['email']; ?>
                    </span>
                </div>
                
                <div class="flex items-center space-x-3">
                    <a href="modal_perfil.php" class="btn-outline text-sm py-2 px-4">
                        <i class="fas fa-user-circle mr-2"></i>Mi Perfil
                    </a>
                    <form method="POST">
                        <button type="submit" class="btn-danger text-sm py-2 px-4" name="cerrar_sesion">
                            <i class="fas fa-sign-out-alt mr-2"></i>Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-24 pb-12 px-4">
        <div class="container mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-dark mb-4 font-heading">Establecimientos de Salud</h1>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">Encuentra el establecimiento perfecto para tu cita médica</p>
            </div>
            
            <!-- Barra de Búsqueda -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-10">
                <h2 class="text-xl font-bold text-dark mb-4 font-heading text-center">Buscar establecimientos</h2>
                <form method="GET" class="flex flex-col md:flex-row gap-4 items-center">
                    <div class="flex-grow relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input 
                            type="text" 
                            name="buscar" 
                            value="<?php echo htmlspecialchars($terminoBusqueda); ?>" 
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" 
                            placeholder="Buscar por nombre de establecimiento..."
                        >
                    </div>
                    <button type="submit" class="btn-primary w-full md:w-auto">
                        <i class="fas fa-search mr-2"></i>Buscar
                    </button>
                    <?php if (!empty($terminoBusqueda)): ?>
                    <a href="?" class="btn-outline w-full md:w-auto">
                        <i class="fas fa-times mr-2"></i>Limpiar
                    </a>
                    <?php endif; ?>
                </form>
                
                <?php if (!empty($terminoBusqueda)): ?>
                <div class="mt-4 text-center">
                    <span class="inline-block bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full">
                        <?php echo count($resultados); ?> resultado(s) para: "<?php echo htmlspecialchars($terminoBusqueda); ?>"
                    </span>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Establecimientos Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <?php
                // Mostrar resultados de búsqueda o todos los establecimientos
                $mostrarEstablecimientos = !empty($resultados) ? $resultados : [
                    [
                        'nombre' => 'Farmacias Similares',
                        'ubicacion' => 'Ubicados en Zona Centro',
                        'horario' => 'Lunes a Viernes: 12pm - 12am',
                        'email' => 'simisoff@gmail.com',
                        'telefono' => '844-111-3456',
                        'tipo' => 'farmacia',
                        'color' => 'blue',
                        'icono' => 'fa-clinic-medical'
                    ],
                    [
                        'nombre' => 'Farmacias Guadalajara',
                        'ubicacion' => 'Venustiano Carranza',
                        'horario' => 'Lunes a Viernes: 10pm - 8am',
                        'email' => 'farmaciasg65094@gmail.com',
                        'telefono' => '844-987-6677',
                        'tipo' => 'farmacia',
                        'color' => 'green',
                        'icono' => 'fa-hospital'
                    ],
                    [
                        'nombre' => 'Farmacias del Ahorro',
                        'ubicacion' => 'Colonia Morelos',
                        'horario' => 'Sábados a Domingo: 1pm - 6pm',
                        'email' => 'ahorrrosfarm0@gmail.com',
                        'telefono' => '844-564-300',
                        'tipo' => 'farmacia',
                        'color' => 'purple',
                        'icono' => 'fa-pills'
                    ]
                ];
                
                if (empty($mostrarEstablecimientos) && !empty($terminoBusqueda)): ?>
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-search fa-3x text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-600 mb-2">No se encontraron resultados</h3>
                    <p class="text-gray-500">Intenta con otros términos de búsqueda</p>
                </div>
                <?php else: ?>
                    <?php foreach ($mostrarEstablecimientos as $establecimiento): 
                        $color = $establecimiento['color'] ?? 'blue';
                        $icono = $establecimiento['icono'] ?? 'fa-clinic-medical';
                    ?>
                    <div class="card p-6">
                        <div class="mb-6">
                            <div class="w-full h-48 bg-<?php echo $color; ?>-100 rounded-lg flex items-center justify-center">
                                <i class="fas <?php echo $icono; ?> text-<?php echo $color; ?>-600 text-6xl"></i>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-dark mb-3 font-heading"><?php echo $establecimiento['nombre']; ?></h3>
                        <div class="space-y-3 mb-6">
                            <p class="text-gray-600 flex items-center">
                                <i class="fas fa-map-marker-alt text-primary mr-2"></i>
                                <span><?php echo $establecimiento['ubicacion']; ?></span>
                            </p>
                            <p class="text-gray-600 flex items-center">
                                <i class="fas fa-clock text-primary mr-2"></i>
                                <span><?php echo $establecimiento['horario']; ?></span>
                            </p>
                            <p class="text-gray-600 flex items-center">
                                <i class="fas fa-envelope text-primary mr-2"></i>
                                <span><?php echo $establecimiento['email']; ?></span>
                            </p>
                            <p class="text-gray-600 flex items-center">
                                <i class="fas fa-phone text-primary mr-2"></i>
                                <span><?php echo $establecimiento['telefono']; ?></span>
                            </p>
                        </div>
                        <a href="salud.php" class="btn-primary inline-block w-full text-center">
                            <i class="fas fa-calendar-plus mr-2"></i>Agendar Cita
                        </a>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <!-- Additional Info -->
            <div class="mt-16 max-w-4xl mx-auto">
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-dark mb-6 text-center font-heading">¿Necesitas ayuda?</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-info-circle text-primary text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-bold mb-2">Información importante</h3>
                            <p class="text-gray-600">Todos nuestros establecimientos cuentan con profesionales certificados y medicamentos de calidad.</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-headset text-green-600 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-bold mb-2">Soporte 24/7</h3>
                            <p class="text-gray-600">Contáctanos si tienes dudas sobre nuestros servicios o necesitas asistencia con tu cita.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-8 mt-12">
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
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-bold mb-4 font-heading">Servicios</h3>
                    <ul class="space-y-2">
                        <li><a href="establecimientos_salud.php" class="text-gray-400 hover:text-white transition-colors">Salud</a></li>
                        <li><a href="estabs_bar.php" class="text-gray-400 hover:text-white transition-colors">Barberías</a></li>
                        <li><a href="estabs_comida.php" class="text-gray-400 hover:text-white transition-colors">Restaurantes</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-bold mb-4 font-heading">Enlaces</h3>
                    <ul class="space-y-2">
                        <li><a href="index.php" class="text-gray-400 hover:text-white transition-colors">Inicio</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Soporte</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">FAQ</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-bold mb-4 font-heading">Contacto</h3>
                    <ul class="space-y-2">
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-3 text-gray-400"></i>
                            <span class="text-gray-400">soporte@citamanager.com</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone mr-3 text-gray-400"></i>
                            <span class="text-gray-400">+1 (234) 567-8900</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-3 text-gray-400"></i>
                            <span class="text-gray-400">Ciudad, País</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center">
                <p class="text-gray-400">© 2023 CitaManager. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
</body>
</html>