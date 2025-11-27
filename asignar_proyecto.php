<?php
session_start();
include("conexion.php");

// Verificar que sea administrador
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'administrador') {
    header("Location: login.php");
    exit();
}

$mensaje = "";

// Guardar asignación
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $proyecto_id = $_POST['proyecto_id'];
    $constructor_id = $_POST['constructor_id'];

    $sql = "UPDATE proyectos SET constructor_id=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $constructor_id, $proyecto_id);

    if ($stmt->execute()) {
        $mensaje = "<div class='alert alert-success'>✅ Proyecto asignado correctamente.</div>";
    } else {
        $mensaje = "<div class='alert alert-danger'>❌ Error al asignar proyecto.</div>";
    }
}

// Obtener proyectos
$sqlProyectos = "SELECT id, nombre FROM proyectos";
$resultProyectos = $conn->query($sqlProyectos);

// Obtener constructores
$sqlConstructores = "SELECT id, nombre FROM usuarios WHERE rol='constructor'";
$resultConstructores = $conn->query($sqlConstructores);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignar Proyecto a Constructor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3>🏗️ Asignar Proyecto a Constructor</h3>
    <?php echo $mensaje; ?>
    <form method="POST" class="card p-4 shadow">
        <div class="mb-3">
            <label>Proyecto</label>
            <select name="proyecto_id" class="form-select" required>
                <option value="">Seleccione un proyecto</option>
                <?php while ($row = $resultProyectos->fetch_assoc()) { ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Constructor</label>
            <select name="constructor_id" class="form-select" required>
                <option value="">Seleccione un constructor</option>
                <?php while ($row = $resultConstructores->fetch_assoc()) { ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                <?php } ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Asignar</button>
        <a href="dashboard.php" class="btn btn-secondary">Volver</a>
    </form>
</div>
</body>
</html>
