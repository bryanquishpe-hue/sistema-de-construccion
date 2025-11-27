<?php
require_once("../auth_check.php");
require_once("../conexion.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header("Location: ../dashboard.php"); exit(); }

$cliente_id  = intval($_POST['cliente_id'] ?? 0);
$nombre      = trim($_POST['nombre'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$estado      = trim($_POST['estado'] ?? 'activo');

$sql = "INSERT INTO proyectos (cliente_id, nombre, descripcion, estado) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isss", $cliente_id, $nombre, $descripcion, $estado);

if ($stmt->execute()) {
    header("Location: ../dashboard.php?msg=proyecto_asignado");
} else {
    header("Location: ../dashboard.php?err=proyecto_error");
}
