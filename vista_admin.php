<?php
session_start();
require_once "dbconexion.php";

// Verificar si el usuario está activo
if($_SESSION['act'] =="no"){
    header('location:index.php');
    exit();
}

// Verificar si es administrador (necesario para estas funciones)
if($_SESSION['rol'] != "admin"){
    header('location:cliente.php');
    exit();
}

// Procesar desactivación de usuario
if (isset($_POST['desactiva'])) {
    $username = $_POST['username'];
    $activo = 'no';

    $sql = $cnnPDO->prepare('UPDATE usuarios_reg SET activo =:activo WHERE username =:username');
    $sql->bindParam(':activo', $activo);
    $sql->bindParam(':username', $username);
    $sql->execute();
    header('location:all_usuarios.php');
    exit();
}

// Procesar reactivación de usuario
if (isset($_POST['reactiva'])) {
    $username = $_POST['username'];
    $activo = 'si';

    $sql = $cnnPDO->prepare('UPDATE usuarios_reg SET activo = :activo WHERE username = :username');
    $sql->bindParam(':activo', $activo);
    $sql->bindParam(':username', $username);
    $sql->execute();
    header('location: all_usuarios.php');
    exit();
}

// Cerrar sesión
if (isset($_POST['cerrar_sesion'])){
    session_destroy();
    header('location:index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - CitaManager</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&family=Raleway:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap para modales -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    
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
            .circular-image {
                @apply w-32 h-32 rounded-full object-cover mx-auto shadow-md;
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
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        <?php echo ucfirst($_SESSION['rol']); ?>
                    </span>
                </div>
                
                <div class="flex items-center space-x-3">
                    <button class="btn-outline text-sm py-2 px-4" data-bs-toggle="modal" data-bs-target="#profileModal">
                        <i class="fas fa-user-circle mr-2"></i>Mi Perfil
                    </button>
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
            <!-- Welcome Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-dark mb-4 font-heading">Panel de Administración</h1>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">Bienvenido, <?php echo $_SESSION['nombre']; ?>. Gestiona usuarios y citas desde aquí.</p>
            </div>
            
            <!-- Services Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <!-- Todos los usuarios -->
                <div class="card p-6 text-center">
                    <div class="mb-6">
                        <img src="./images/todos_los_usuarios.webp" alt="Todos los usuarios" class="circular-image">
                    </div>
                    <h3 class="text-xl font-bold text-dark mb-3 font-heading">Usuarios Totales</h3>
                    <p class="text-gray-600 mb-6">Gestiona todos los usuarios registrados en el sistema</p>
                    <a href="./all_usuarios.php" class="btn-primary inline-block w-full">
                        <i class="fas fa-users mr-2"></i>Ver Usuarios
                    </a>
                </div>
                
                <!-- Citas y establecimientos -->
                <div class="card p-6 text-center">
                    <div class="mb-6">
                        <img src="./images/usuarios_que_agendaron.webp" alt="Citas y establecimientos" class="circular-image">
                    </div>
                    <h3 class="text-xl font-bold text-dark mb-3 font-heading">Citas y Establecimientos</h3>
                    <p class="text-gray-600 mb-6">Administra citas y establecimientos registrados</p>
                    <a href="./all_citas_y_estab.php" class="btn-primary inline-block w-full">
                        <i class="fas fa-calendar-check mr-2"></i>Ver Citas
                    </a>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="mt-16 max-w-4xl mx-auto">
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-dark mb-6 text-center font-heading">Acciones Rápidas</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a href="./all_usuarios.php" class="flex flex-col items-center p-4 border-2 border-gray-100 rounded-lg hover:border-primary transition-all duration-300">
                            <i class="fas fa-user-plus text-primary text-2xl mb-2"></i>
                            <span class="text-sm font-medium">Agregar Usuario</span>
                        </a>
                        <a href="./reportes.php" class="flex flex-col items-center p-4 border-2 border-gray-100 rounded-lg hover:border-primary transition-all duration-300">
                            <i class="fas fa-chart-bar text-primary text-2xl mb-2"></i>
                            <span class="text-sm font-medium">Reportes</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Profile Modal -->
    <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-heading font-bold" id="profileModalLabel">
                        <i class="fas fa-user-circle mr-2"></i>Perfil de Administrador
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="space-y-4">
                        <div>
                            <h5 class="text-sm font-medium text-gray-500">Nombre completo</h5>
                            <p class="text-lg font-semibold"><?php echo $_SESSION['nombre']; ?></p>
                        </div>
                        
                        <div>
                            <h5 class="text-sm font-medium text-gray-500">Nombre de usuario</h5>
                            <p class="text-lg font-semibold"><?php echo $_SESSION['username']; ?></p>
                        </div>
                        
                        <div>
                            <h5 class="text-sm font-medium text-gray-500">Correo electrónico</h5>
                            <p class="text-lg font-semibold"><?php echo $_SESSION['email']; ?></p>
                        </div>
                        
                        <div>
                            <h5 class="text-sm font-medium text-gray-500">Tipo de cuenta</h5>
                            <p class="text-lg font-semibold">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <?php echo ucfirst($_SESSION['rol']); ?>
                                </span>
                            </p>
                        </div>
                        
                        <div>
                            <h5 class="text-sm font-medium text-gray-500">Estado de cuenta</h5>
                            <p class="text-lg font-semibold">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Activa
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-outline" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn-primary">Editar Perfil</button>
                </div>
            </div>
        </div>
    </div>

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
                    <h3 class="text-lg font-bold mb-4 font-heading">Administración</h3>
                    <ul class="space-y-2">
                        <li><a href="all_usuarios.php" class="text-gray-400 hover:text-white transition-colors">Usuarios</a></li>
                        <li><a href="all_citas_y_estab.php" class="text-gray-400 hover:text-white transition-colors">Citas</a></li>
                        <li><a href="reportes.php" class="text-gray-400 hover:text-white transition-colors">Reportes</a></li>
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

    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>