<?php
session_start();
include("conexion.php");

$cliente_id = $_SESSION['id'];
$proyecto_id = $_POST['proyecto_id'];
$descripcion = $_POST['descripcion'];

$sql = "INSERT INTO solicitudes_trabajo (cliente_id, proyecto_id, descripcion) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $cliente_id, $proyecto_id, $descripcion);
$stmt->execute();

header("Location: cliente.php?msg=solicitud_enviada");
?>
