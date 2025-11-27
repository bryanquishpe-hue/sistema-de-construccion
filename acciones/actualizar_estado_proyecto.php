<?php
session_start();
require_once("../conexion.php");

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: ../login.php");
    exit();
}

$id = intval($_POST['id']);
$estado = trim($_POST['estado']);

if ($id > 0 && in_array($estado, ['activo', 'pendiente', 'finalizado'])) {
    $stmt = $conn->prepare("UPDATE proyectos SET estado = ? WHERE id = ?");
    $stmt->bind_param("si", $estado, $id);
    $stmt->execute();
    header("Location: ../reportes.php?msg=estado_actualizado");
    exit();
} else {
    header("Location: ../reportes.php?msg=error");
    exit();
}
?>
