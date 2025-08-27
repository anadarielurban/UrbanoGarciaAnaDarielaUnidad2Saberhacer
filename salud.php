<?php
session_start();

// Verificar si la sesión está activa
if ($_SESSION['act'] == "no") {
    header('location:index.php');
    exit();
}

// Cerrar sesión
if (isset($_POST['cerrar_sesion'])) {
    session_destroy();
    header('location:index.php');
    exit();
}

require_once 'dbconexion.php';

$verDatos = '';

// Código para agendar cita
if (isset($_POST['confirmar'])) {
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $tipo_cita = $_POST['tipo_cita'];
    $usuario_id = $_SESSION['id']; // Asumiendo que tienes el ID de usuario en la sesión

    if (!empty($fecha) && !empty($hora) && !empty($tipo_cita)) {
        try {
            // Verificar si ya existe una cita a la misma hora
            $checkQuery = $conn->prepare("SELECT * FROM agendar_cita WHERE fecha = ? AND hora = ?");
            $checkQuery->bind_param("ss", $fecha, $hora);
            $checkQuery->execute();
            $result = $checkQuery->get_result();
            
            if ($result->num_rows > 0) {
                echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>¡Horario ocupado!</strong> Ya existe una cita programada para esta fecha y hora.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
            } else {
                // Insertar la nueva cita
                $sql = $conn->prepare("INSERT INTO agendar_cita (fecha, hora, tipo_cita, usuario_id) VALUES (?, ?, ?, ?)");
                $sql->bind_param("sssi", $fecha, $hora, $tipo_cita, $usuario_id);
                
                if ($sql->execute()) {
                    // Obtener la última cita agendada
                    $last_id = $conn->insert_id;
                    $getCitaQuery = $conn->prepare("SELECT * FROM agendar_cita WHERE id = ?");
                    $getCitaQuery->bind_param("i", $last_id);
                    $getCitaQuery->execute();
                    $ultimaCita = $getCitaQuery->get_result()->fetch_assoc();
                    
                    if ($ultimaCita) {
                        $verDatos = '
                            <div class="card mt-4">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0"><i class="fas fa-calendar-check me-2"></i>Cita Agendada Exitosamente</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Día de la cita:</strong> ' . date('d/m/Y', strtotime($ultimaCita['fecha'])) . '</p>
                                            <p><strong>Hora:</strong> ' . $ultimaCita['hora'] . '</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Tipo de cita:</strong> ' . ucfirst($ultimaCita['tipo_cita']) . '</p>
                                            <p><strong>ID de cita:</strong> ' . $ultimaCita['id'] . '</p>
                                        </div>
                                    </div>
                                    <div class="text-center mt-3">
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                                            <i class="fas fa-times-circle me-2"></i>Cancelar Cita
                                        </button>
                                    </div>
                                </div>
                            </div>';

                        // Modal de confirmación de eliminación
                        $verDatos .= '
                        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Cancelación</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            ¿Estás seguro de que deseas cancelar tu cita?
                                        </div>
                                        <p><strong>ID de cita:</strong> ' . $ultimaCita['id'] . '</p>
                                        <p><strong>Fecha:</strong> ' . date('d/m/Y', strtotime($ultimaCita['fecha'])) . '</p>
                                        <p><strong>Hora:</strong> ' . $ultimaCita['hora'] . '</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="fas fa-times me-2"></i>No, mantener cita
                                        </button>
                                        <form method="post" action="">
                                            <input type="hidden" name="id" value="' . $ultimaCita['id'] . '">
                                            <button type="submit" class="btn btn-danger" name="eliminar">
                                                <i class="fas fa-trash me-2"></i>Sí, cancelar cita
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>';
                    }
                }
            }
        } catch (Exception $e) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error:</strong> ' . $e->getMessage() . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }
    }
}

// Código de ELIMINAR
if (isset($_POST['eliminar'])) {
    $id_cita = $_POST['id'];

    if (!empty($id_cita)) {
        try {
            $query = $conn->prepare('DELETE FROM agendar_cita WHERE id = ?');
            $query->bind_param("i", $id_cita);
            
            if ($query->execute()) {
                echo '<div class="alert alert-info alert-dismissible fade show" role="alert">
                    <strong>Cita cancelada:</strong> Tu cita ha sido cancelada exitosamente.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                $verDatos = ''; // Limpiar los datos mostrados
            }
        } catch (Exception $e) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error:</strong> No se pudo cancelar la cita. ' . $e->getMessage() . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Cita - CitaManager</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&family=Raleway:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="shortcut icon" href="https://img.icons8.com/windows/32/000000/baby-calendar.png" type="image/x-icon">
    
    <style>
        body {
            font-family: 'Quicksand', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .navbar-brand {
            font-family: 'Raleway', sans-serif;
            font-weight: 700;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 8px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .main-container {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin-top: 100px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="./index.php">
            <img class="icon_logo" width="32" height="32" src="https://img.icons8.com/windows/32/ffffff/baby-calendar.png" alt="CitaManager"/>
            CitaManager
        </a>
        
        <div class="text-white ms-auto me-3">
            <small>
                <i class="fas fa-user me-1"></i><?php echo $_SESSION['username']; ?><br>
                <i class="fas fa-envelope me-1"></i><?php echo $_SESSION['email']; ?>
            </small>
        </div>

        <div class="d-flex">
            <a href="modal_perfil.php" class="btn btn-outline-light me-2">
                <i class="fas fa-user-circle me-1"></i>Perfil
            </a>
            <form method="POST">
                <button type="submit" class="btn btn-outline-danger" name="cerrar_sesion">
                    <i class="fas fa-sign-out-alt me-1"></i>Salir
                </button>
            </form>
        </div>
    </div>
</nav>

<div class="container main-container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-5">
                <h1 class="fw-bold text-gradient">Agendar Nueva Cita</h1>
                <p class="text-muted">Complete el formulario para programar su cita</p>
            </div>

            <form method="POST" class="card p-4">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="fecha" class="form-label fw-semibold">Fecha de la cita</label>
                        <input type="date" class="form-control" id="fecha" name="fecha" 
                               min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="hora" class="form-label fw-semibold">Hora de la cita</label>
                        <input type="time" class="form-control" id="hora" name="hora" 
                               min="09:00" max="18:00" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="tipo_cita" class="form-label fw-semibold">Tipo de cita</label>
                    <select class="form-select" id="tipo_cita" name="tipo_cita" required>
                        <option value="">Selecciona el tipo de cita</option>
                        <option value="consulta">Consulta General</option>
                        <option value="emergencia">Emergencia</option>
                        <option value="seguimiento">Seguimiento</option>
                        <option value="especialista">Especialista</option>
                    </select>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                    <button type="submit" class="btn btn-primary me-md-2" name="confirmar">
                        <i class="fas fa-calendar-check me-2"></i>Confirmar Cita
                    </button>
                    <a href="vista_cliente.php" class="btn btn-outline-secondary">
                        <i class="fas fa-home me-2"></i>Volver al Inicio
                    </a>
                </div>
            </form>

            <?php echo $verDatos; ?>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Establecer la fecha mínima como mañana
    document.getElementById('fecha').min = new Date().toISOString().split('T')[0];
    
    // Establecer horas válidas (9 AM to 6 PM)
    document.getElementById('hora').addEventListener('change', function() {
        const hora = this.value;
        const [hours, minutes] = hora.split(':').map(Number);
        
        if (hours < 9 || hours > 18) {
            alert('Por favor, seleccione una hora entre 9:00 AM y 6:00 PM');
            this.value = '';
        }
    });
</script>

</body>
</html>