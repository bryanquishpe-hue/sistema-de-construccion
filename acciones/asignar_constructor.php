<?php
session_start();
require_once("../conexion.php");

// 🔐 Validar que solo el administrador pueda asignar
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: ../login.php");
    exit();
}

// 🔎 Verificar que llegan datos
if (!isset($_POST['solicitud_id'], $_POST['constructor_id'], $_POST['estado_admin'])) {
    die("Error: Datos incompletos.");
}

$solicitud_id = intval($_POST['solicitud_id']);
$constructor_id = intval($_POST['constructor_id']);
$estado_admin = $_POST['estado_admin'];

// 🔹 Validación de estado permitido
$estados_validos = ['activado', 'rechazado'];

if (!in_array($estado_admin, $estados_validos)) {
    die("Error: Estado no permitido.");
}

// =========================================================
// 🔷 Consulta para actualizar la solicitud
// =========================================================

$sql = "UPDATE solicitudes_trabajo 
        SET constructor_id = ?, estado_admin = ?
        WHERE id = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Error en prepare: " . $conn->error);
}

$stmt->bind_param("isi", $constructor_id, $estado_admin, $solicitud_id);

if ($stmt->execute()) {

    if ($estado_admin === "activado") {
        $conn->query("UPDATE solicitudes_trabajo 
                      SET estado_cliente = 'activado' 
                      WHERE id = $solicitud_id");
    }

    header("Location: ../administrador.php?msg=asignado");
    exit();
} else {
    die("Error al actualizar: " . $stmt->error);
}

$stmt->close();
$conn->close();
?>

