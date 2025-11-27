<?php
session_start();
include("conexion.php");

// Verificar sesión y rol
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'administrador') {
    header("Location: login.php");
    exit();
}

// Validar ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID de usuario inválido.</div>";
    exit();
}

$usuario_id = intval($_GET['id']);
$mensaje = "";

// Actualizar usuario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $rol = $_POST['rol'];
    $estado = $_POST['estado'];

    $sql = "UPDATE usuarios SET nombre=?, email=?, rol=?, estado=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $nombre, $email, $rol, $estado, $usuario_id);

    if ($stmt->execute()) {
        $mensaje = "<div class='alert alert-success text-center'>✅ Usuario actualizado correctamente.</div>";
    } else {
        $mensaje = "<div class='alert alert-danger text-center'>❌ Error al actualizar el usuario.</div>";
    }
}

// Obtener datos actuales
$sql = "SELECT * FROM usuarios WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3>✏️ Editar Usuario</h3>
    <?php echo $mensaje; ?>
    <form method="POST" class="card p-4 shadow">
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" value="<?php echo $usuario['nombre']; ?>" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo $usuario['email']; ?>" required>
        </div>
        <div class="mb-3">
            <label>Rol</label>
            <select name="rol" class="form-select" required>
                <option value="administrador" <?php if ($usuario['rol']=='administrador') echo 'selected'; ?>>Administrador</option>
                <option value="cliente" <?php if ($usuario['rol']=='cliente') echo 'selected'; ?>>Cliente</option>
                <option value="constructor" <?php if ($usuario['rol']=='constructor') echo 'selected'; ?>>Constructor</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Estado</label>
            <select name="estado" class="form-select" required>
                <option value="activo" <?php if ($usuario['estado']=='activo') echo 'selected'; ?>>Activo</option>
                <option value="inactivo" <?php if ($usuario['estado']=='inactivo') echo 'selected'; ?>>Inactivo</option>
                <option value="pendiente" <?php if ($usuario['estado']=='pendiente') echo 'selected'; ?>>Pendiente</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="usuarios.php" class="btn btn-secondary">Volver</a>
    </form>
</div>
</body>
</html>
