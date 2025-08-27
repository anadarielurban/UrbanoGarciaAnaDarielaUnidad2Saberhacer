<?php
ob_start();

// Establecer conexión a la base de datos con MySQLi
$host = 'localhost';
$dbname = 'db_citas_managger';
$username = 'root';
$password = 'Timoteo1.';

$conn = new mysqli($host, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Establecer el conjunto de caracteres a utf8
$conn->set_charset("utf8");
?>