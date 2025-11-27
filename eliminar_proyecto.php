<?php
session_start();
include("conexion.php");

// Verificar que sea administrador
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'administrador') {
    header("Location: login.php");
    exit();
}

// Validar ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger text-center mt-5'>❌ ID de proyecto inválido.</div>";
    exit();
}

$proyecto_id = intval($_GET['id']);
$mensaje = "";

// Eliminar proyecto
$sql = "DELETE FROM proyectos WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $proyecto_id);

if ($stmt->execute()) {
    $mensaje = "<div class='alert alert-success text-center mt-5'>✅ Proyecto eliminado correctamente.</div>";
} else {
    $mensaje = "<div class='alert alert-danger text-center mt-5'>❌ Error al eliminar proyecto.</div>";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Proyecto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3>🗑️ Eliminar Proyecto</h3>
    <?php echo $mensaje; ?>
    <a href="proyectos.php" class="btn btn-secondary mt-3">⬅️ Volver a Proyectos</a>
</div>
</body>
</html>
