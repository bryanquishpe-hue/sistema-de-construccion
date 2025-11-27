<?php
session_start();
require_once("../conexion.php");

// 🔐 Seguridad: solo administradores
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: ../login.php");
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // 🔍 Verificar si el cliente tiene proyectos relacionados
    $check = $conn->prepare("SELECT COUNT(*) FROM proyectos WHERE cliente_id = ?");
    $check->bind_param("i", $id);
    $check->execute();
    $check->bind_result($total);
    $check->fetch();
    $check->close();

    if ($total > 0) {
        // ❌ No se puede eliminar porque tiene proyectos
        echo "<div class='alert alert-warning m-4'>⚠️ No puedes eliminar este cliente porque tiene proyectos asignados.</div>";
        echo "<div class='text-center'><a href='../dashboard.php' class='btn btn-secondary'>↩️ Volver al Dashboard</a></div>";
        exit();
    }

    // ✅ Eliminar cliente si no tiene proyectos
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
