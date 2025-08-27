<?php
    session_start();

    // Incluir la conexión a la base de datos
    require './dbconexion.php';

    // Verificar si el usuario ya está logueado
    if (isset($_SESSION['username'])) {
        // Redirigir según el rol
        if ($_SESSION['rol'] === 'admin') {
            header('Location: vista_admin.php');
            exit();
        } else if ($_SESSION['rol'] === 'dueño') {
            header('Location: vista_dueno.php');
            exit();
        } else if ($_SESSION['rol'] === 'cliente') {
            header('Location: vista_cliente.php');
            exit();
        }
    }

    // Procesar inicio de sesión
    if (isset($_POST['login'])) {
        $username = trim($_POST['username']);
        $contra   = trim($_POST['contra']);

        if (! empty($username) && ! empty($contra)) {
            // Usar consultas preparadas con MySQLi
            $query = "SELECT * FROM usuarios_reg WHERE username = ? AND password = ?";
            $stmt  = $conn->prepare($query);
            $stmt->bind_param("ss", $username, $contra);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $campo = $result->fetch_assoc();

                // Verificar si la cuenta está activa
                if ($campo['activo'] !== 'si') {
                    $error_msg = "Tu cuenta está desactivada. Contacta al administrador.";
                } else {
                    $_SESSION['nombre']   = $campo['nombre'];
                    $_SESSION['username'] = $campo['username'];
                    $_SESSION['email']    = $campo['email'];
                    $_SESSION['contra']   = $campo['password'];
                    $_SESSION['act']      = $campo['activo'];
                    $_SESSION['rol']      = $campo['rol'];

                    // Determinar la redirección según el rol del usuario
                    if ($_SESSION['rol'] === 'admin') {
                        header('Location: vista_admin.php');
                        exit();
                    } else if ($_SESSION['rol'] === 'dueño') {
                        header('Location: vista_dueno.php');
                        exit();
                    } else if ($_SESSION['rol'] === 'cliente') {
                        header('Location: vista_cliente.php');
                        exit();
                    } else {
                        $error_msg = "Rol no válido. Contacta al administrador del sistema.";
                    }
                }
            } else {
                $error_msg = "El usuario o la contraseña son incorrectos.";
            }
            $stmt->close();
        } else {
            $error_msg = "Por favor, ingresa usuario y contraseña.";
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - CitaManager</title>
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
                @apply bg-primary hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 flex items-center justify-center;
            }
            .btn-secondary {
                @apply bg-secondary hover:bg-emerald-600 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300;
            }
            .btn-outline {
                @apply border-2 border-primary text-primary hover:bg-primary hover:text-white font-bold py-2 px-4 rounded-lg transition-all duration-300;
            }
            .input-field {
                @apply w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all duration-300;
            }
            .card {
                @apply bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300;
            }
            .alert-error {
                @apply bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6;
            }
            .alert-warning {
                @apply bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6;
            }
        }
    </style>
</head>
<body class="font-body bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="fixed w-full bg-white shadow-md z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <a href="./index.php" class="flex items-center space-x-2 text-primary font-bold text-xl font-heading">
                    <img class="w-8 h-8" src="https://img.icons8.com/windows/32/000000/baby-calendar.png" alt="CitaManager Logo">
                    <span>CitaManager</span>
                </a>

                <div class="flex items-center space-x-4">
                    <a href="registrarse.php" class="btn-outline text-sm py-2 px-4">
                        <i class="fas fa-user-plus mr-2"></i>Registrarse
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center pt-20 pb-12 px-4">
        <div class="w-full max-w-md card p-8">
            <div class="text-center mb-8">
                <div class="flex justify-center mb-6">
                    <div class="w-24 h-24 rounded-full bg-blue-100 flex items-center justify-center shadow-inner">
                        <i class="fas fa-user-circle text-primary text-5xl"></i>
                    </div>
                </div>
                <h1 class="text-3xl font-bold text-dark mb-2 font-heading">Iniciar Sesión</h1>
                <p class="text-gray-600">Ingresa tus credenciales para acceder a tu cuenta</p>
            </div>

            <?php if (isset($error_msg)): ?>
                <div class="alert-error" role="alert">
                    <p class="font-bold">Error</p>
                    <p><?php echo $error_msg; ?></p>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Usuario</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="far fa-user text-gray-400"></i>
                        </div>
                        <input type="text" id="username" name="username" required
                            class="input-field pl-10" placeholder="Ingresa tu nombre de usuario"
                            value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                    </div>
                </div>

                <div>
                    <label for="contra" class="block text-sm font-medium text-gray-700 mb-2">Contraseña</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" id="contra" name="contra" required
                            class="input-field pl-10" placeholder="Ingresa tu contraseña">
                    </div>
                    <div class="mt-2 text-right">
    <a href="recuperar_contrasena.php" class="text-sm text-primary hover:text-blue-700">¿Olvidaste tu contraseña?</a>
</div>
                </div>

                <button type="submit" name="login" class="w-full btn-primary py-3">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    <span>Iniciar Sesión</span>
                </button>
            </form>

            <div class="text-center mt-8 pt-6 border-t border-gray-200">
                <p class="text-gray-600">¿No tienes una cuenta aún?</p>
                <a href="registrarse.php" class="inline-block mt-2 text-primary hover:text-blue-700 font-medium">
                    <i class="fas fa-arrow-right mr-2"></i>Regístrate aquí
                </a>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-sm font-medium text-gray-700 mb-3 font-heading flex items-center">
                    <i class="fas fa-info-circle text-accent mr-2"></i>¿Problemas para acceder?
                </h3>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-primary mt-1 mr-2 text-xs"></i>
                        <span>Asegúrate de que tu usuario y contraseña sean correctos</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-primary mt-1 mr-2 text-xs"></i>
                        <span>Contacta al administrador si olvidaste tus credenciales</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-primary mt-1 mr-2 text-xs"></i>
                        <span>Verifica que tu cuenta esté activa</span>
                    </li>
                </ul>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-8 mt-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <a href="./index.php" class="flex items-center space-x-2 text-white font-bold text-xl font-heading mb-4">
                        <img class="w-8 h-8" src="https://img.icons8.com/windows/32/ffffff/baby-calendar.png" alt="CitaManager Logo">
                        <span>CitaManager</span>
                    </a>
                    <p class="text-gray-400 mb-4">La forma más sencilla de gestionar tus citas en línea.</p>
                </div>

                <div>
                    <h3 class="text-lg font-bold mb-4 font-heading">Enlaces rápidos</h3>
                    <ul class="space-y-2">
                        <li><a href="index.php" class="text-gray-400 hover:text-white transition-colors"><i class="fas fa-home mr-2"></i>Inicio</a></li>
                        <li><a href="registrarse.php" class="text-gray-400 hover:text-white transition-colors"><i class="fas fa-user-plus mr-2"></i>Registrarse</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors"><i class="fas fa-question-circle mr-2"></i>Soporte</a></li>
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