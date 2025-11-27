<?php
session_start();
include("conexion.php");

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'cliente') {
    header("Location: login.php");
    exit();
}

$cliente_id = $_SESSION['id'];
$mensaje = "";

// Actualizar perfil
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];

    $sql = "UPDATE clientes SET nombre=?, telefono=?, direccion=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $nombre, $telefono, $direccion, $cliente_id);

    if ($stmt->execute()) {
        $_SESSION['usuario'] = $nombre;
        $mensaje = "<div class='alert alert-success'>✅ Perfil actualizado correctamente.</div>";
    } else {
        $mensaje = "<div class='alert alert-danger'>❌ Error al actualizar perfil.</div>";
    }
}

// Obtener datos actuales
$sql = "SELECT * FROM clientes WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cliente_id);
$stmt->execute();
$cliente = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3>✏️ Editar Perfil</h3>
    <?php echo $mensaje; ?>
    <form method="POST" class="card p-4 shadow">
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" value="<?php echo $cliente['nombre']; ?>" required>
        </div>
        <div class="mb-3">
            <label>Teléfono</label>
            <input type="text" name="telefono" class="form-control" value="<?php echo $cliente['telefono']; ?>">
        </div>
        <div class="mb-3">
            <label>Dirección</label>
            <input type="text" name="direccion" class="form-control" value="<?php echo $cliente['direccion']; ?>">
        </div>
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="cliente.php" class="btn btn-secondary">Volver</a>
    </form>
</div>
</body>
</html>
