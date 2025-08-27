<?php
session_start();
require_once "dbconexion.php";

if (isset($_POST['desactiva'])) {
    $user = $_POST['usuario'];
    $act = 'no';

    $sql = $cnnPDO->prepare('UPDATE usuarios_reg SET activo = :act WHERE username = :username');
    $sql->bindParam(':act', $act);
    $sql->bindParam(':username', $user);
    $sql->execute();
    header('location:all_usuarios.php');
    exit();
}

if (isset($_POST['reactiva'])) {
    $user = $_POST['usuario'];
    $act = 'si';

    $sql = $cnnPDO->prepare('UPDATE usuarios_reg SET activo = :act WHERE username = :username');
    $sql->bindParam(':act', $act);
    $sql->bindParam(':username', $user);
    $sql->execute();
    header('location:all_usuarios.php');
    exit();
}

if (isset($_POST['cerrar_sesion'])) {
    session_destroy();
    header('location:index .php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios Registrados</title>
    <link rel="shortcut icon" href="https://img.icons8.com/windows/32/000000/baby-calendar.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
            crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="./index.php">
            <img class="icon_logo" width="32" height="32"
                 src="https://img.icons8.com/windows/32/000000/baby-calendar.png" alt="baby-calendar"/>
            CitaManager
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="text-white">
            <?php if (isset($_SESSION['username'])) : ?>
                <h3><?php echo $_SESSION['username']; ?></h3>
            <?php endif; ?>
        </div>
        <div class="text-white">
            <?php if (isset($_SESSION['email'])) : ?>
                <h3><?php echo $_SESSION['email']; ?></h3>
            <?php endif; ?>
        </div>

        <form method="POST">
            <button class="btn text-white" name="cerrar_sesion" type="submit">Salir</button>
        </form>
    </div>
</nav>

<br><br>

<div class="box_registro_establecimiento">
    <div class="box_all_usuarios" style="width:80%;">
<div class="container text-center">
    <h1 class="display-1"><h5>USUARIOS REGISTRADOS</h5></h1>
    <br>
    <form method="POST">
        <input class="input-field type="text" name="usuario" placeholder="Ingresa el username">
        <button type="submit" name="desactiva" class="btn btn-danger bt-5 bm-5">Bloquear</button>
        <button type="submit" name="reactiva" class="btn btn-danger bt-5 bm-5">Activa</button>
    </form>
</div>

<!-- Section Para publicar USUARIOS -->

<section>
    
        <?php
        $sql = $cnnPDO->prepare("SELECT * FROM usuarios_reg");
        $sql->execute();
        ?>
        <table class='table table-dark table-striped'>
            <tr>
                <th class="text-white"><b>Nombre</b></th>
                <th class="text-white"><b>Apellido</b></th>
                <th class="text-white"><b>Username</b></th>
                <th class="text-white"><b>Email</b></th>
                <th class="text-white"><b>Telefono</b></th>
                <th class="text-white"><b>Password</b></th>
                <th class="text-white"><b>ACTIVO</b></th>
            </tr>

            <?php
            while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td class='text-white'>" . $row['nombre'] . "</td>";
                echo "<td class='text-white'>" . $row['apellido'] . "</td>";
                echo "<td class='text-white'>" . $row['username'] . "</td>";
                echo "<td class='text-white'>" . $row['email'] . "</td>";
                echo "<td class='text-white'>" . $row['telefono'] . "</td>";
                echo "<td class='text-white'>" . $row['password'] . "</td>";
                echo "<td class='text-white'>" . $row['activo'] . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</div>

</body>
</html>
