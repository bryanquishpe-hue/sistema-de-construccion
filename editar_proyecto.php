<?php
session_start();
include("conexion.php");

// Verificar sesión y rol
if (!isset($_SESSION['usuario']) || ($_SESSION['rol'] != 'administrador' && $_SESSION['rol'] != 'constructor')) {
    header("Location: login.php");
    exit();
}

// Validar ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID de proyecto inválido.</div>";
    exit();
}

$proyecto_id = intval($_GET['id']);
$mensaje = "";

// Actualizar proyecto
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $estado = $_POST['estado'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];

    $sql = "UPDATE proyectos SET nombre=?, descripcion=?, estado=?, fecha_inicio=?, fecha_fin=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $nombre, $descripcion, $estado, $fecha_inicio, $fecha_fin, $proyecto_id);

    if ($stmt->execute()) {
        $mensaje = "<div class='alert alert-success text-center'>✅ Proyecto actualizado correctamente.</div>";
    } else {
        $mensaje = "<div class='alert alert-danger text-center'>❌ Error al actualizar el proyecto.</div>";
    }
}

// Obtener datos actuales
$sql = "SELECT * FROM proyectos WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $proyecto_id);
$stmt->execute();
$proyecto = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Proyecto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3>✏️ Editar Proyecto</h3>
    <?php echo $mensaje; ?>
    <form method="POST" class="card p-4 shadow">
        <div class="mb-3">
            <label>Nombre del Proyecto</label>
            <input type="text" name="nombre" class="form-control" value="<?php echo $proyecto['nombre']; ?>" required>
        </div>
        <div class="mb-3">
            <label>Descripción</label>
            <textarea name="descripcion" class="form-control" rows="3" required><?php echo $proyecto['descripcion']; ?></textarea>
        </div>
        <div class="mb-3">
            <label>Estado</label>
            <select name="estado" class="form-select" required>
                <option value="pendiente" <?php if ($proyecto['estado']=='pendiente') echo 'selected'; ?>>Pendiente</option>
                <option value="activo" <?php if ($proyecto['estado']=='activo') echo 'selected'; ?>>Activo</option>
                <option value="finalizado" <?php if ($proyecto['estado']=='finalizado') echo 'selected'; ?>>Finalizado</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Fecha de Inicio</label>
            <input type="date" name="fecha_inicio" class="form-control" value="<?php echo $proyecto['fecha_inicio']; ?>" required>
        </div>
        <div class="mb-3">
            <label>Fecha de Fin</label>
            <input type="date" name="fecha_fin" class="form-control" value="<?php echo $proyecto['fecha_fin']; ?>">
        </div>
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="proyectos.php" class="btn btn-secondary">Volver</a>
    </form>
</div>
</body>
</html>
