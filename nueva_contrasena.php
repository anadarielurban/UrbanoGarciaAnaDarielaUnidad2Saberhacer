<?php
session_start();
require './dbconexion.php';

$error = '';
$success = '';
$token_valido = false;
$token = '';

// Verificar si hay un token válido
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Verificar si el token es válido y no ha expirado
    $query = "SELECT * FROM usuarios_reg WHERE token_recuperacion = ? AND expiracion_token > NOW()";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $token_valido = true;
    } else {
        $error = "El enlace de recuperación no es válido o ha expirado.";
    }
    
    $stmt->close();
} else {
    $error = "Enlace de recuperación no válido.";
}

// Procesar el cambio de contraseña
if (isset($_POST['cambiar_contrasena']) && $token_valido) {
    $nueva_contrasena = trim($_POST['nueva_contrasena']);
    $confirmar_contrasena = trim($_POST['confirmar_contrasena']);
    
    if (!empty($nueva_contrasena) && !empty($confirmar_contrasena)) {
        if ($nueva_contrasena === $confirmar_contrasena) {
            // Validar fortaleza de la contraseña
            if (strlen($nueva_contrasena) < 8) {
                $error = "La contraseña debe tener al menos 8 caracteres.";
            } else {
                // MODIFICACIÓN: Guardar la contraseña como texto plano (NO SEGURO)
                $query = "UPDATE usuarios_reg SET password = ?, token_recuperacion = NULL, expiracion_token = NULL WHERE token_recuperacion = ?";
                $stmt = $conn->prepare($query);
                
                if ($stmt) {
                    $stmt->bind_param("ss", $nueva_contrasena, $token); // Se guarda como texto
                    
                    if ($stmt->execute()) {
                        if ($stmt->affected_rows > 0) {
                            $success = "Tu contraseña se ha restablecido correctamente. Ahora puedes iniciar sesión.";
                            $token_valido = false; // Invalidar el token después del uso
                        } else {
                            $error = "Error: No se pudo actualizar la contraseña. El token podría ser inválido.";
                        }
                    } else {
                        $error = "Error en la ejecución de la consulta: " . $stmt->error;
                    }
                    
                    $stmt->close();
                } else {
                    $error = "Error preparando la consulta: " . $conn->error;
                }
            }
        } else {
            $error = "Las contraseñas no coinciden.";
        }
    } else {
        $error = "Por favor, completa todos los campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Contraseña - CitaManager</title>
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
            .alert-success {
                @apply bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6;
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
                    <a href="./inicio_sesion.php" class="text-primary hover:text-blue-700 text-sm">
                        <i class="fas fa-sign-in-alt mr-2"></i>Iniciar Sesión
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
                        <i class="fas fa-lock text-primary text-5xl"></i>
                    </div>
                </div>
                <h1 class="text-3xl font-bold text-dark mb-2 font-heading">Nueva Contraseña</h1>
                <p class="text-gray-600">Ingresa y confirma tu nueva contraseña</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert-error" role="alert">
                    <p class="font-bold">Error</p>
                    <p><?php echo $error; ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert-success" role="alert">
                    <p class="font-bold">Éxito</p>
                    <p><?php echo $success; ?></p>
                    <p class="mt-2"><a href="./inicio_sesion.php" class="text-primary hover:text-blue-700 font-medium">Iniciar sesión</a></p>
                </div>
            <?php endif; ?>

            <?php if ($token_valido && empty($success)): ?>
            <form method="POST" class="space-y-6">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                
                <div>
                    <label for="nueva_contrasena" class="block text-sm font-medium text-gray-700 mb-2">Nueva Contraseña</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" id="nueva_contrasena" name="nueva_contrasena" required 
                            class="input-field pl-10" placeholder="Ingresa tu nueva contraseña (mínimo 8 caracteres)"
                            minlength="8">
                    </div>
                </div>

                <div>
                    <label for="confirmar_contrasena" class="block text-sm font-medium text-gray-700 mb-2">Confirmar Contraseña</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" required 
                            class="input-field pl-10" placeholder="Confirma tu nueva contraseña"
                            minlength="8">
                    </div>
                </div>

                <button type="submit" name="cambiar_contrasena" class="w-full btn-primary py-3">
                    <i class="fas fa-save mr-2"></i>
                    <span>Establecer nueva contraseña</span>
                </button>
            </form>
            <?php elseif (empty($error) && empty($success)): ?>
                <div class="alert-warning">
                    <p>No se ha proporcionado un token válido para restablecer la contraseña.</p>
                </div>
            <?php endif; ?>

            <div class="text-center mt-8 pt-6 border-t border-gray-200">
                <a href="./inicio_sesion.php" class="inline-block mt-2 text-primary hover:text-blue-700 font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Volver al inicio de sesión
                </a>
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
                        <li><a href="./inicio_sesion.php" class="text-gray-400 hover:text-white transition-colors"><i class="fas fa-sign-in-alt mr-2"></i>Iniciar Sesión</a></li>
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