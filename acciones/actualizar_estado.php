<?php
session_start();
require_once("../conexion.php");

// 🔐 SOLO ADMINISTRADORES
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: ../login.php");
    exit();
}

// 📌 Validar datos enviados
if (!isset($_POST['solicitud_id'], $_POST['estado_admin'])) {
    die("Error: Datos incompletos.");
}

$solicitud_id = intval($_POST['solicitud_id']);
$estado_admin = $_POST['estado_admin'];

// 📌 Validar valores permitidos
$estados_validos = ["pendiente", "activado", "rechazado", "finalizado"];

if (!in_array($estado_admin, $estados_validos)) {
    die("Error: Estado no permitido.");
}

// ======================================================
// 🔷 Actualizar estado administrativo
// ======================================================
$sql = "UPDATE solicitudes_trabajo 
        SET estado_admin = ?
        WHERE id = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Error en prepare: " . $conn->error);
}

$stmt->bind_param("si", $estado_admin, $solicitud_id);

if ($stmt->execute()) {

    // ======================================================
    // 🔹 Reglas automáticas adicionales
    // ======================================================

    if ($estado_admin === "activado") {
        // Administrador aprueba la solicitud
        $conn->query("UPDATE solicitudes_trabajo 
                      SET estado_cliente = 'activado'
                      WHERE id = $solicitud_id");
    }

    if ($estado_admin === "rechazado") {
        // Solicitud rechazada por administrador
        $conn->query("UPDATE solicitudes_trabajo 
                      SET estado_cliente = 'rechazado'
                      WHERE id = $solicitud_id");
    }

    if ($estado_admin === "finalizado") {
        // Proyecto terminado
        $conn->query("UPDATE solicitudes_trabajo 
                      SET estado_cliente = 'finalizado'
                      WHERE id = $solicitud_id");
    }

    header("Location: ../administrador.php?msg=estado_actualizado");
    exit();

} else {
    die("Error al actualizar el estado: " . $stmt->error);
}

$stmt->close();
$conn->close();
?>
