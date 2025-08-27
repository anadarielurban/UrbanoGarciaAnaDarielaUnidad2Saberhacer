<?php
ob_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

// Incluir el archivo de conexión a la base de datos
require_once './dbconexion.php';

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    $db_error = "No se pudo conectar a la base de datos. Verifique la configuración en dbconexion.php";
    error_log("Error de conexión a la base de datos: " . $conn->connect_error);
} else {
    // Verificar si la tabla usuarios_reg existe
    $query = "SHOW TABLES LIKE 'usuarios_reg'";
    $result = $conn->query($query);
    
    if ($result->num_rows == 0) {
        // Crear la tabla si no existe
        $createTableQuery = "CREATE TABLE usuarios_reg (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(100) NOT NULL,
            apellido VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            telefono VARCHAR(20) NOT NULL,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            rol ENUM('dueño', 'cliente', 'admin') DEFAULT 'cliente',
            activo ENUM('si', 'no') DEFAULT 'si',
            token_recuperacion VARCHAR(255) DEFAULT NULL,
            expiration_token DATETIME DEFAULT NULL,
            fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        if ($conn->query($createTableQuery)) {
            // Tabla creada exitosamente
        } else {
            $db_error = "Error al crear la tabla: " . $conn->error;
            error_log($db_error);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse - CitaManager</title>
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
                @apply bg-primary hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300;
            }
            .btn-secondary {
                @apply bg-secondary hover:bg-emerald-600 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300;
            }
            .btn-outline {
                @apply border-2 border-primary text-primary hover:bg-primary hover:text-white font-bold py-3 px-6 rounded-lg transition-all duration-300;
            }
            .input-field {
                @apply w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all duration-300;
            }
            .card {
                @apply bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300;
            }
        }
    </style>
</head>
<body class="font-body bg-gray-50 min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="fixed w-full bg-white shadow-md z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <a href="./index.php" class="flex items-center space-x-2 text-primary font-bold text-xl font-heading">
                    <img class="w-8 h-8" src="https://img.icons8.com/windows/32/000000/baby-calendar.png" alt="CitaManager Logo">
                    <span>CitaManager</span>
                </a>
                
                <div class="flex items-center space-x-4">
                    <a href="inicio_sesion.php" class="text-gray-600 hover:text-primary transition-colors duration-300 font-medium">Iniciar Sesión</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center pt-20 pb-12 px-4">
        <div class="w-full max-w-4xl flex flex-col md:flex-row rounded-xl shadow-xl overflow-hidden">
            <!-- Left side - Illustration/Info -->
            <div class="w-full md:w-2/5 bg-gradient-to-br from-primary to-blue-700 text-white p-8 flex flex-col justify-center">
                <div class="mb-6">
                    <h2 class="text-3xl font-bold mb-4 font-heading">Únete a CitaManager</h2>
                    <p class="text-blue-100">Gestiona tus citas de manera eficiente y ahorra tiempo con nuestra plataforma.</p>
                </div>
                <div class="space-y-4 mt-8">
                    <div class="flex items-start">
                        <div class="bg-white bg-opacity-20 p-2 rounded-full mr-3">
                            <i class="fas fa-check-circle text-white"></i>
                        </div>
                        <p class="text-blue-100">Agenda citas en minutos</p>
                    </div>
                    <div class="flex items-start">
                        <div class="bg-white bg-opacity-20 p-2 rounded-full mr-3">
                            <i class="fas fa-check-circle text-white"></i>
                        </div>
                        <p class="text-blue-100">Accede a profesionales calificados</p>
                    </div>
                    <div class="flex items-start">
                        <div class="bg-white bg-opacity-20 p-2 rounded-full mr-3">
                            <i class="fas fa-check-circle text-white"></i>
                        </div>
                        <p class="text-blue-100">Recibe recordatorios automáticos</p>
                    </div>
                </div>
            </div>
            
            <!-- Right side - Form -->
            <div class="w-full md:w-3/5 bg-white p-8">
                <h1 class="text-2xl font-bold text-dark mb-2 font-heading">Crear una cuenta</h1>
                <p class="text-gray-600 mb-6">Ingresa tus datos para registrarte</p>
                
                <?php
                // Mostrar error de conexión a la base de datos si existe
                if (isset($db_error) && !empty($db_error)) {
                    echo '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                        <p class="font-bold">Error de configuración</p>
                        <p>No se pudo conectar a la base de datos. Por favor, contacte al administrador.</p>
                        <p class="text-sm">Detalles: ' . htmlspecialchars($db_error) . '</p>
                    </div>';
                }

                # Inicia Código de REGISTRAR
                if (isset($_POST['registrar'])) {
                    // Verificar si la conexión a la base de datos está disponible
                    if ($conn->connect_error) {
                        echo '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                            <p class="font-bold">Error de conexión</p>
                            <p>No se pudo conectar a la base de datos. Por favor, intente más tarde.</p>
                        </div>';
                    } else {
                        $nombre = trim($_POST['nombre']);
                        $apellido = trim($_POST['apellido']);
                        $email = trim($_POST['email']);
                        $telefono = trim($_POST['telefono']);
                        $username = trim($_POST['username']);
                        $contra = $_POST['contra'];
                        $rol_elegido = $_POST['rol'];
                        $activo = "si";

                        if ($username === 'admin') {
                            $rol_elegido = 'admin';
                        }

                        if (!empty($nombre) && !empty($apellido) && !empty($email) && !empty($telefono) && !empty($username) && !empty($contra) && !empty($rol_elegido)) {
                            // Verificar si el correo electrónico ya existe
                            $query = "SELECT * FROM usuarios_reg WHERE email = ?";
                            $stmt = $conn->prepare($query);
                            $stmt->bind_param("s", $email);
                            $stmt->execute();
                            $resultCorreo = $stmt->get_result();

                            if ($resultCorreo->num_rows > 0) {
                                echo '<div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
                                    <p class="font-bold">¡Ups!</p>
                                    <p>El correo electrónico ya existe. Prueba con otro.</p>
                                </div>';
                            } else {
                                // Verificar si el nombre de usuario ya existe
                                $query = "SELECT * FROM usuarios_reg WHERE username = ?";
                                $stmt = $conn->prepare($query);
                                $stmt->bind_param("s", $username);
                                $stmt->execute();
                                $resultUsuario = $stmt->get_result();

                                if ($resultUsuario->num_rows > 0) {
                                    echo '<div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
                                        <p class="font-bold">¡Ups!</p>
                                        <p>El usuario ya existe. Prueba con otro.</p>
                                    </div>';
                                } else {
                                    // Insertar el nuevo usuario
                                    $query = "INSERT INTO usuarios_reg (nombre, apellido, email, telefono, username, password, rol, activo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("ssssssss", $nombre, $apellido, $email, $telefono, $username, $contra, $rol_elegido, $activo);
                                    
                                    if ($stmt->execute()) {
                                        try {
                                            $mail = new PHPMailer(true);
                                            // Configuración del objeto $mail
                                            $mail->SMTPDebug = 0;
                                            $mail->isSMTP();
                                            $mail->Host = 'smtp.gmail.com';
                                            $mail->SMTPAuth = true;
                                            $mail->Username = 'managercitas@gmail.com';
                                            $mail->Password = 'wigq ntao jxoz ejyp';
                                            $mail->SMTPSecure = 'tls';
                                            $mail->Port = 587;

                                            $imagen_url0 = "https://anadarielaurbanogarcia.000webhostapp.com/logo.png";

                                            $mail->setFrom('managercitas@gmail.com', 'Citas Manager'); 
                                            $mail->addAddress($email);

                                            $mail->isHTML(true);
                                            $mail->Subject = "¡Gracias por ser parte de nosotros!";
                                            $mail->Body =  '<div style="background-color: #fff;">
                                                <div style="background-color: #fff; padding: 10px; text-align: center;">
                                                    <img src="' . $imagen_url0 . '" alt="" style="display:block; margin:0 auto; width: 100px;">
                                                    <h1 style="font-size: 24px; color: #black;">Gracias por registrarte en Citas Manager. Verificamos que este es tu correo: ' . $email . '</h1>
                                                </div>
                                            </div>';

                                            $mail->send();
                                            echo '<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                                                <p class="font-bold">¡Registro exitoso!</p>
                                                <p>Te has registrado correctamente. Revisa tu correo para más información.</p>
                                            </div>';
                                        } catch (Exception $e) {
                                            echo '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                                                <p class="font-bold">Error de envío</p>
                                                <p>El registro se completó pero no pudimos enviar el correo de confirmación.</p>
                                            </div>';
                                        }
                                    } else {
                                        echo '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                                            <p class="font-bold">Error en el registro</p>
                                            <p>Ocurrió un error al registrar el usuario. Por favor, intente nuevamente.</p>
                                        </div>';
                                    }
                                }
                            }
                        } else {
                            echo '<div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
                                <p class="font-bold">Campos incompletos</p>
                                <p>Por favor, complete todos los campos del formulario.</p>
                            </div>';
                        }
                    }
                }
                ?>  

                <form method="POST" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                            <input type="text" id="nombre" name="nombre" required 
                                class="input-field" placeholder="Tu nombre">
                        </div>
                        <div>
                            <label for="apellido" class="block text-sm font-medium text-gray-700 mb-1">Apellido</label>
                            <input type="text" id="apellido" name="apellido" required 
                                class="input-field" placeholder="Tu apellido">
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico</label>
                        <input type="email" id="email" name="email" required 
                            class="input-field" placeholder="correo@ejemplo.com">
                    </div>

                    <div>
                        <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                        <input type="tel" id="telefono" name="telefono" required 
                            class="input-field" placeholder="+1 234 567 890">
                    </div>

                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Nombre de usuario</label>
                        <input type="text" id="username" name="username" required 
                            class="input-field" placeholder="Elige un nombre de usuario">
                    </div>

                    <div>
                        <label for="contra" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                        <input type="password" id="contra" name="contra" required 
                            class="input-field" placeholder="Crea una contraseña segura">
                    </div>

                    <div>
                        <label for="rol" class="block text-sm font-medium text-gray-700 mb-1">Tipo de cuenta</label>
                        <select id="rol" name="rol" required class="input-field">
                            <option value="">Selecciona un rol</option>
                            <option value="dueño">Dueño de establecimiento</option>
                            <option value="cliente">Cliente</option>
                        </select>
                    </div>

                    <button type="submit" name="registrar" 
                        class="w-full btn-primary flex items-center justify-center py-3 mt-2">
                        <span>Crear cuenta</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </form>

                <div class="text-center mt-6">
                    <p class="text-gray-600">¿Ya tienes una cuenta? 
                        <a href="inicio_sesion.php" class="text-primary hover:text-blue-700 font-medium">Inicia sesión</a>
                    </p>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-8">
        <div class="container mx-auto px-4 text-center">
            <p>© 2023 CitaManager. Todos los derechos reservados.</p>
            <div class="flex justify-center space-x-6 mt-4">
                <a href="#" class="text-gray-400 hover:text-white transition-colors">Términos de servicio</a>
                <a href="#" class="text-gray-400 hover:text-white transition-colors">Política de privacidad</a>
                <a href="#" class="text-gray-400 hover:text-white transition-colors">Contacto</a>
            </div>
        </div>
    </footer>
</body>
</html>