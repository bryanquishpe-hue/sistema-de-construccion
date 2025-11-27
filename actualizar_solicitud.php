<?php
session_start();
include("conexion.php");

if ($_SESSION['rol'] != 'administrador') {
    header("Location: login.php");
    exit();
}

$solicitud_id = $_POST['solicitud_id'];
$estado_admin = $_POST['estado_admin'];
$constructor_id = $_POST['constructor_id'];

$sql = "UPDATE solicitudes_trabajo SET estado_admin=?, constructor_id=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sii", $estado_admin, $constructor_id, $solicitud_id);

if ($stmt->execute()) {
    header("Location: gestionar_solicitudes.php?msg=ok");
} else {
    echo "<div class='alert alert-danger'>❌ Error al actualizar solicitud.</div>";
}
?>
