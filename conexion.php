<?php
$host = "localhost";
$user = "root";   // tu usuario MySQL
$pass = "Bryan1970@";       // tu contraseña MySQL
$db   = "sistema_usuarios";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
