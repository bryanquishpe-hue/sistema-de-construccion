<?php
require_once("../auth_check.php");
require_once("../conexion.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header("Location: ../dashboard.php"); exit(); }

$nombre  = trim($_POST['nombre'] ?? '');
$email   = trim($_POST['email'] ?? '');
$pass    = password_hash($_POST['password'] ?? 'Constructor123!', PASSWORD_DEFAULT);
$rol     = 'constructor';

$sql = "INSERT INTO usuarios (nombre, email, password, rol, fecha_registro) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $nombre, $email, $pass, $rol);

if ($stmt->execute()) {
    header("Location: ../dashboard.php?msg=constructor_creado");
} else {
    header("Location: ../dashboard.php?err=constructor_error");
}
