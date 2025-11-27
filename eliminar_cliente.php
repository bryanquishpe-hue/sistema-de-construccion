<?php
session_start();
require_once("../conexion.php");

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: ../login.php");
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $stmt = $conn->prepare("DELETE FROM clientes WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: ../dashboard.php?msg=cliente_eliminado");
        exit();
    } else {
        echo "<div class='alert alert-danger m-4'>❌ Error al eliminar cliente.</div>";
    }
} else {
    echo "<div class='alert alert-warning m-4'>⚠️ ID inválido.</div>";
}
?>

