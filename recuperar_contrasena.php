<?php
session_start();
require './dbconexion.php';

// Incluir PHPMailer - solución simplificada
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Verificar si PHPMailer está disponible a través de Composer
if (file_exists('vendor/autoload.php')) {
    require 'vendor/autoload.php';
} 
// Si no, cargar manualmente desde la carpeta PHPMailer-master
else if (file_exists('PHPMailer-master/src/Exception.php')) {
    require 'PHPMailer-master/src/Exception.php';
    require 'PHPMailer-master/src/PHPMailer.php';
    require 'PHPMailer-master/src/SMTP.php';
} 
// Si no existe ninguna versión, mostrar error
else {
    die("Error: No se encontró PHPMailer. Por favor, instálalo via Composer o descarga manualmente.");
}

$message = '';
$error = '';

// Verificar si las columnas necesarias existen en la tabla
$column_check = $conn->query("SHOW COLUMNS FROM usuarios_reg LIKE 'token_recuperacion'");
if ($column_check->num_rows == 0) {
    $error = "Sistema de recuperación no configurado. Contacta al administrador.";
}

if (isset($_POST['recuperar']) && empty($error)) {
    $email = trim($_POST['email']);
    
    if (!empty($email)) {
        // Verificar si el email existe en la base de datos
        $query = "SELECT * FROM usuarios_reg WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $usuario = $result->fetch_assoc();
            
            // Generar token único
            $token = bin2hex(random_bytes(50));
            $expiracion = date("Y-m-d H:i:s", strtotime('+1 hour'));
            
            // Cerrar el primer statement
            $stmt->close();
            
            // Guardar token en la base de datos
            $query = "UPDATE usuarios_reg SET token_recuperacion = ?, expiracion_token = ? WHERE email = ?";
            $stmt2 = $conn->prepare($query);
            $stmt2->bind_param("sss", $token, $expiracion, $email);
            
            if ($stmt2->execute()) {
                // Configurar PHPMailer
                $mail = new PHPMailer(true);
                
                try {
                    // Configuración del servidor SMTP
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';  // Servidor SMTP de Gmail
                    $mail->SMTPAuth = true;
                    $mail->Username = 'anadarielaurbanogarciapruebas';  // Tu dirección de Gmail
                    $mail->Password = 'yazi ozal guff cuah
';  // Tu contraseña de aplicación
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;
                    
                    // Remitente y destinatario
                    $mail->setFrom('no-reply@citamanager.com', 'CitaManager');
                    $mail->addAddress($email, $usuario['nombre']);
                    
                    // Contenido del correo
                    $mail->isHTML(true);
                    $mail->Subject = 'Recuperación de contraseña - CitaManager';
                    
                    $enlace = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/nueva_contrasena.php?token=" . $token;
                    /*  */
                    $mail->Body = "
                        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                            <h2 style='color: #3B82F6;'>Recuperación de contraseña</h2>
                            <p>Hola <strong>{$usuario['nombre']}</strong>,</p>
                            <p>Has solicitado restablecer tu contraseña en CitaManager.</p>
                            <p>Por favor, haz clic en el siguiente botón para crear una nueva contraseña:</p>
                            <p style='text-align: center; margin: 30px 0;'>
                                <a href='$enlace' style='background-color: #3B82F6; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;'>Restablecer contraseña</a>
                            </p>
                            <p>O copia y pega este enlace en tu navegador:</p>
                            <p style='word-break: break-all; background-color: #f5f5f5; padding: 10px; border-radius: 5px;'>$enlace</p>
                            <p><strong>Este enlace expirará en 1 hora.</strong></p>
                            <p>Si no solicitaste este cambio, ignora este mensaje.</p>
                            <br>
                            <p>Saludos,<br>El equipo de <strong>CitaManager</strong></p>
                        </div>
                    ";
                    
                    $mail->AltBody = "Hola {$usuario['nombre']},\n\nHas solicitado restablecer tu contraseña en CitaManager.\nPor favor, visita el siguiente enlace para crear una nueva contraseña:\n$enlace\n\nEste enlace expirará en 1 hora.\nSi no solicitaste este cambio, ignora este mensaje.\n\nSaludos,\nEl equipo de CitaManager";
                    
                    $mail->send();
                    $message = "Se ha enviado un correo con instrucciones para restablecer tu contraseña.";
                } catch (Exception $e) {
                    $error = "Error al enviar el correo. Por favor, intenta nuevamente más tarde.";
                    // Para debugging, puedes mostrar el error completo (no recomendado en producción):
                    // $error = "Error al enviar el correo: " . $mail->ErrorInfo;
                }
            } else {
                $error = "Error al procesar la solicitud. Por favor, intenta nuevamente.";
            }
            $stmt2->close();
        } else {
            $error = "No existe una cuenta asociada a este correo electrónico.";
        }
    } else {
        $error = "Por favor, ingresa tu correo electrónico.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - CitaManager</title>
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
                    <a href="login.php" class="text-primary hover:text-blue-700 text-sm">
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
                        <i class="fas fa-key text-primary text-5xl"></i>
                    </div>
                </div>
                <h1 class="text-3xl font-bold text-dark mb-2 font-heading">Recuperar Contraseña</h1>
                <p class="text-gray-600">Ingresa tu correo electrónico para restablecer tu contraseña</p>
            </div>

            <?php if (!empty($message)): ?>
                <div class="alert-success" role="alert">
                    <p class="font-bold">Éxito</p>
                    <p><?php echo $message; ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="alert-error" role="alert">
                    <p class="font-bold">Error</p>
                    <p><?php echo $error; ?></p>
                </div>
            <?php endif; ?>

            <?php if (empty($error) || (isset($_POST['recuperar']) && !empty($error))): ?>
            <form method="POST" class="space-y-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Correo Electrónico</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="far fa-envelope text-gray-400"></i>
                        </div>
                        <input type="email" id="email" name="email" required 
                            class="input-field pl-10" placeholder="Ingresa tu correo electrónico"
                            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>
                </div>

                <button type="submit" name="recuperar" class="w-full btn-primary py-3">
                    <i class="fas fa-paper-plane mr-2"></i>
                    <span>Enviar enlace de recuperación</span>
                </button>
            </form>
            <?php endif; ?>

            <div class="text-center mt-8 pt-6 border-t border-gray-200">
                <p class="text-gray-600">¿Recordaste tu contraseña?</p>
                <a href="login.php" class="inline-block mt-2 text-primary hover:text-blue-700 font-medium">
                    <i class="fas fa-arrow-right mr-2"></i>Inicia sesión aquí
                </a>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-sm font-medium text-gray-700 mb-3 font-heading flex items-center">
                    <i class="fas fa-info-circle text-accent mr-2"></i>¿Problemas para recuperar tu cuenta?
                </h3>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-primary mt-1 mr-2 text-xs"></i>
                        <span>Verifica que el correo electrónico sea el correcto</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-primary mt-1 mr-2 text-xs"></i>
                        <span>Revisa tu carpeta de spam si no recibes el correo</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-primary mt-1 mr-2 text-xs"></i>
                        <span>Contacta al administrador si necesitas ayuda adicional</span>
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
                        <li><a href="login.php" class="text-gray-400 hover:text-white transition-colors"><i class="fas fa-sign-in-alt mr-2"></i>Iniciar Sesión</a></li>
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