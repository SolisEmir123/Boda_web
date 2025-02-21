<?php
// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "Emir**1904";  // Cambia esto si tu contraseña es diferente
$dbname = "boda_db";        // Cambia esto si tu base de datos tiene otro nombre

$conn = new mysqli($servername, $username, $password, $dbname);

$conn->set_charset("utf8mb4");


if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>
