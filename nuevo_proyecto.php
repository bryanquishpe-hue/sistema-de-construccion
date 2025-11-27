<?php
session_start();
include("conexion.php");

// Verificar que sea administrador
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'administrador') {
    header("Location: login.php");
    exit();
}

$mensaje = "";

// Guardar proyecto
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $estado = $_POST['estado'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $cliente_id = $_POST['cliente_id'];
    $constructor_id = $_POST['constructor_id'];

    $sql = "INSERT INTO proyectos (nombre, descripcion, estado, fecha_inicio, fecha_fin, cliente_id, constructor_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $nombre, $descripcion, $estado, $fecha_inicio, $fecha_fin, $cliente_id, $constructor_id);

    if ($stmt->execute()) {
        $mensaje = "<div class='alert alert-success text-center'>✅ Proyecto creado correctamente.</div>";
    } else {
        $mensaje = "<div class='alert alert-danger text-center'>❌ Error al crear proyecto.</div>";
    }
}

// Obtener clientes
$sqlClientes = "SELECT id, nombre FROM clientes";
$resultClientes = $conn->query($sqlClientes);

// Obtener constructores
$sqlConstructores = "SELECT id, nombre FROM usuarios WHERE rol='constructor'";
$resultConstructores = $conn->query($sqlConstructores);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Proyecto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .card { box-shadow: 0 5px 15px rgba(0,0,0,0.2); border-radius: 10px; }
        h3 { color: #004080; }
    </style>
</head>
<body>
<div class="container mt-5">
    <h3>➕ Crear Nuevo Proyecto</h3>
    <?php echo $mensaje; ?>
    <form method="POST" class="card p-4 shadow">
        <div class="mb-3">
            <label>Nombre del Proyecto</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Descripción</label>
            <textarea name="descripcion" class="form-control" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label>Estado</label>
            <select name="estado" class="form-select" required>
                <option value="pendiente">Pendiente</option>
                <option value="activo">Activo</option>
                <option value="finalizado">Finalizado</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Fecha de Inicio</label>
            <input type="date" name="fecha_inicio" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Fecha de Fin</label>
            <input type="date" name="fecha_fin" class="form-control">
        </div>
        <div class="mb-3">
            <label>Cliente</label>
            <select name="cliente_id" class="form-select" required>
                <option value="">Seleccione un cliente</option>
                <?php while ($row = $resultClientes->fetch_assoc()) { ?>
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
        <button type="submit" class="btn btn-primary">Guardar Proyecto</button>
        <a href="proyectos.php" class="btn btn-secondary">Volver</a>
    </form>
</div>
</body>
</html>
