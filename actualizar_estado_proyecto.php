<?php
session_start();
require_once("../conexion.php");

// 🔐 Seguridad: solo administradores
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: ../login.php");
    exit();
}

// 🔎 Validar datos recibidos
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$estado = isset($_POST['estado']) ? trim($_POST['estado']) : '';

if ($id > 0 && in_array($estado, ['activo', 'pendiente', 'finalizado'])) {
    $stmt = $conn->prepare("UPDATE proyectos SET estado = ? WHERE id = ?");
    $stmt->bind_param("si", $estado, $id);

    if ($stmt->execute()) {
        header("Location: ../reportes.php?msg=estado_actualizado");
        exit();
    } else {
        echo "<div class='alert alert-danger m-4'>❌ Error al actualizar el estado.</div>";
    }
} else {
    echo "<div class='alert alert-warning m-4'>⚠️ Datos inválidos.</div>";
}
?>

