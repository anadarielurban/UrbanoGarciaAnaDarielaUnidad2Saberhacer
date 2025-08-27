<?php 
 session_start();
 require_once 'dbconexion.php';

 if (isset($_POST['cerrar_sesion'])){
  session_destroy();
  header('location:index.php');
 }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300&family=Raleway:wght@500&display=swap" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="./style.css">
    <title>Establecimiento</title>
    <link rel="shortcut icon" href="https://img.icons8.com/windows/32/000000/baby-calendar.png" type="image/x-icon">
</head>
<body>

<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="./index.php">
            <img class="icon_logo" width="32" height="32" src="https://img.icons8.com/windows/32/000000/baby-calendar.png" alt="baby-calendar"/>
            CitaManager
        </a>
        
        <div class="text-white">
    <h3><?php echo $_SESSION['username']; ?><br><?php echo $_SESSION['email']; ?></h3>
</div>



        <div class="collapse navbar-collapse justify-content-end">
            <button href="modal_perfil.php" class="btn btn-outline-secondary me-2">Ver perfil</button>
            <form method="POST">
                <button type="submit" class="btn btn-outline-danger" name="cerrar_sesion">Cerrar sesión</button>
            </form>
        </div>
    </div>
</nav> 

<form method="POST" enctype="multipart/form-data">





<div class="box_registro_establecimiento">
<div class="container">

<?php 
require_once 'dbconexion.php';

if (isset($_POST['cerrar_sesion'])){
    session_destroy();
    header('location:index.php');
}

if (isset($_POST['registrar'])) {
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $descripcion = $_POST['descripcion'];
    $cita = $_POST['cita'];
    $horarios = $_POST['horarios'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];

    // Verifica si se ha subido un archivo y si no hay errores
    if (!empty($_FILES['imagen']['name']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
        $temp_name = $_FILES['imagen']['tmp_name'];
        $imagen = fopen($temp_name, 'rb');
    } else {
        // Define un valor por defecto si no se sube una imagen
        $imagen = null;
    }

    if (!empty($nombre) && !empty($direccion) && !empty($descripcion) && !empty($cita) && !empty($horarios) && !empty($correo) && !empty($telefono)) {
        $sql = $cnnPDO->prepare('INSERT INTO registro (nombre, direccion, descripcion, cita, horarios, correo, telefono, imagen) VALUES (:nombre, :direccion, :descripcion, :cita, :horarios, :correo, :telefono, :imagen)');
        $sql->bindParam(':nombre', $nombre);
        $sql->bindParam(':direccion', $direccion);
        $sql->bindParam(':descripcion', $descripcion);
        $sql->bindParam(':cita', $cita);
        $sql->bindParam(':horarios', $horarios);
        $sql->bindParam(':correo', $correo);
        $sql->bindParam(':telefono', $telefono);
        $sql->bindParam(':imagen', $imagen, PDO::PARAM_LOB);
        $sql->execute();
        unset($sql);
        unset($cnnPDO);
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Establecimiento registrado!</strong> El establecimiento ha sido registrado correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';

    }
}
?>

<?php
	# Código de BUSCAR

	$GLOBALS['$nombre'] = "";
	$GLOBALS['$direccion'] = "";
	$GLOBALS['$descripcion'] = "";
	$GLOBALS['$cita'] = "";
	$GLOBALS['$horarios'] = "";
	$GLOBALS['$correo'] = "";
	$GLOBALS['$telefono'] = "";
	$GLOBALS['$imagen'] = "";


	if (isset($_POST['buscar'])) {
		
		$nombre=$_POST['nombre'];

		$query = $cnnPDO->prepare('SELECT * from registro WHERE nombre =:nombre');
		$query->bindParam(':nombre', $nombre);
		
		$query->execute(); 
		$count=$query->rowCount();
		$campo = $query->fetch();

		if($count)	{	
			$GLOBALS['$nombre'] = $campo['nombre'];
			$GLOBALS['$direccion'] = $campo['direccion'];
			$GLOBALS['$descripcion'] = $campo['descripcion'];
			$GLOBALS['$cita'] = $campo['cita'];	
			$GLOBALS['$horarios'] = $campo['horarios'];	
			$GLOBALS['$correo'] = $campo['correo'];	
			$GLOBALS['$telefono'] = $campo['telefono'];	
			$GLOBALS['$imagen'] = $campo['imagen'];	

			echo '<div class="alert alert-info" role="alert">Datos encontrados</div>';
			//echo $campo['codigo'];	
		}else{
			$GLOBALS['$nombre'] = "";
			echo '<div class="alert alert-warning" role="alert">Datos no encontrados</div>';
		}

	}
	# Termina Código de BUSCAR
	?>


	<?php
	# Inicia Código de EDITAR o MODIFICAR

	if (isset($_POST['editar'])) 
	{  
		$nombre=$_POST['nombre'];
		$direccion=$_POST['direccion'];
		$descripcion=$_POST['descripcion'];
		$cita=$_POST['cita'];
		$horarios=$_POST['horarios'];

		if (!empty($nombre) && !empty($direccion) && !empty($descripcion) && !empty($cita) && !empty($horarios))
		{  
			$sql = $cnnPDO->prepare(
				'UPDATE registro SET direccion = :direccion, descripcion = :descripcion, cita = :cita, horarios = :horarios WHERE nombre = :nombre'
			);
			
			$sql->bindParam(':nombre',$nombre);
			$sql->bindParam(':direccion',$direccion);
			$sql->bindParam(':descripcion',$descripcion);
			$sql->bindParam(':cita',$cita);
			$sql->bindParam(':horarios',$horarios);
			$sql->execute();
			unset($sql);
			unset($cnnPDO);
			echo '<div class="alert alert-info" role="alert">Datos modificados</div>';

		}
	}
	# Termina Código de EDITAR o MODIFICAR
	?>


	<?php
	# Código de ELIMINAR

	if (isset($_POST['eliminar'])) {
		
		$nombre=$_POST['nombre'];

		if (!empty($nombre)){
			$query = $cnnPDO->prepare('DELETE from registro WHERE nombre =:nombre');
			$query->bindParam(':nombre', $nombre);
			
			$query->execute(); 
			echo '<div class="alert alert-danger" role="alert">Datos eliminados</div>';
		}
	}
	# Termina Código de ELIMINAR
	?>





  <div class="row">
    <div class="col">
            <label for="imagen"><h3>Sube la imagen de tu establecimiento.</h3></label>
            <img src="./images/establecimiento.webp" alt="">
            <input type="file" id="imagen" name="imagen" accept="image/*">
    </div>
    <div class="col">
      <div class="input-field mt-5 mb-5"> <span class="far  p-2"></span> <input required name="nombre" type="text"  placeholder="Nombre Del Establecimiento:" value="<?php echo $GLOBALS['$nombre'];?>"> </div>

      <div class="input-field mt-5 mb-5"> <span class="far p-2"></span> <input required name="direccion" type="text"  placeholder="Lugar Del Establecimiento:" value="<?php echo $GLOBALS['$direccion'];?>"> </div>

      <div class="input-field mt-5 mb-5"> <span class="far my-4 mb-4 p-2"></span> <input required name="descripcion" type="text"  placeholder="Descripcion Del Establecimiento:" value="<?php echo $GLOBALS['$descripcion'];?>"> </div>

      <div class="input-field mt-5 mb-5"> <span class="far p-2"></span> <input required name="cita" type="text"  placeholder="Tipo  De Cita:" value="<?php echo $GLOBALS['$cita'];?>"> </div>
    </div>
    
    <div class="col">

      <div class="input-field mt-5 mb-5"> <span class="far p-2"></span> <input required name="horarios" type="text"  placeholder="Horarios:" value="<?php echo $GLOBALS['$horarios'];?>"> </div>

      <div class="input-field mt-5 mb-5"> <span class="far p-2"></span> <input required name="correo" type="text"  placeholder="Correo:" value="<?php echo $GLOBALS['$correo'];?>"> </div>

      <div class="input-field mt-5 mb-2"> <span class="far p-2"></span> <input required name="telefono" type="text"  placeholder="Telefono:" value="<?php echo $GLOBALS['$telefono'];?>"> </div>
    </div>

    <div class="d-flex align-items-start">
        </div> <button class="btn btn-block text-center my-1" name="registrar" type="submit">Guardar</button>
    </div>
    <div class="d-flex align-items-start">
        </div> <button class="btn btn-block text-center my-1" name="buscar" type="submit">Buscar</button>
    </div>    <div class="d-flex align-items-start">
        </div> <button class="btn btn-block text-center my-1" name="editar" type="submit">Editar</button>
    </div>
    <div class="d-flex align-items-start">
        </div> <button class="btn btn-block text-center my-1" name="eliminar" type="submit">Eliminar</button>
    </div>
    <div class="d-flex align-items-start">
        </div> <a href="vista_dueño.php" class="btn btn-block text-center my-1" name="registrar" type="submit">Realizar otro registro</a>
    </div>


  
</div>
</div>
</form>
</body>
</html>