<?php
require_once("../auth_check.php");
require_once("../conexion.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header("Location: ../dashboard.php"); exit(); }

$nombre   = trim($_POST['nombre'] ?? '');
$email    = trim($_POST['email'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$direccion= trim($_POST['direccion'] ?? '');
$estado   = trim($_POST['estado'] ?? 'activo');

$sql = "INSERT INTO clientes (nombre, email, telefono, direccion,fecha_registro, estado)
        VALUES (?, ?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $nombre, $email, $telefono, $direccion, $estado);

if ($stmt->execute()) {
    header("Location: ../dashboard.php?msg=cliente_creado");
} else {
    header("Location: ../dashboard.php?err=cliente_error");
}
