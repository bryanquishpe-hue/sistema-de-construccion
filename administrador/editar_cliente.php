<?php
require_once("../conexion.php");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$cliente = null;
$error = null;
$success = null;

if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM clientes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cliente = $result->fetch_assoc();
    $stmt->close();
}

if (!$cliente) {
    echo "<div class='alert alert-danger m-4'>❌ Cliente no encontrado.</div>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre    = trim($_POST['nombre']);
    $email     = trim($_POST['email']);
    $telefono  = trim($_POST['telefono']);
    $direccion = trim($_POST['direccion']);
    $estado    = trim($_POST['estado']);

    if ($nombre === '' || $email === '') {
        $error = "⚠️ Nombre y Email son obligatorios.";
    } else {
        $stmt = $conn->prepare("UPDATE clientes SET nombre=?, email=?, telefono=?, direccion=?, estado=? WHERE id=?");
        $stmt->bind_param("sssssi", $nombre, $email, $telefono, $direccion, $estado, $id);

        if ($stmt->execute()) {
            $success = "✅ Cliente actualizado correctamente.";
            $cliente = array_merge($cliente, $_POST);
        } else {
            $error = "❌ Error al actualizar cliente.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('../assets/fondo_dashboard.jpg') no-repeat center center fixed;
            background-size: cover;
            backdrop-filter: blur(2px);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card-custom {
            width: 100%;
            max-width: 760px;
            background-color: rgba(255, 255, 255, 0.96);
            border-radius: 16px;
            box-shadow: 0 0 30px rgba(0,0,0,0.25);
            padding: 35px;
        }
        .form-label {
            font-weight: 600;
            color: #004080;
        }
        .btn-primary {
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: scale(1.04);
        }
        .alert {
            font-weight: 500;
        }
    </style>
</head>
<body>

<div class="card card-custom">
    <h4 class="mb-4 text-center text-primary">✏️ <strong>Editar Cliente</strong></h4>

    <?php if ($error): ?>
        <div class="alert alert-danger" role="alert"><?= $error ?></div>
    <?php elseif ($success): ?>
        <div class="alert alert-success" role="alert"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST" novalidate>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($cliente['nombre']) ?>" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($cliente['email']) ?>" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" id="telefono" name="telefono" value="<?= htmlspecialchars($cliente['telefono']) ?>" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" id="direccion" name="direccion" value="<?= htmlspecialchars($cliente['direccion']) ?>" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select id="estado" name="estado" class="form-select">
                    <option value="activo" <?= $cliente['estado']=='activo'?'selected':'' ?>>Activo</option>
                    <option value="inactivo" <?= $cliente['estado']=='inactivo'?'selected':'' ?>>Inactivo</option>
                    <option value="pendiente" <?= $cliente['estado']=='pendiente'?'selected':'' ?>>Pendiente</option>
                </select>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="../dashboard.php" class="btn btn-outline-secondary">↩️ Cancelar</a>
            <button type="submit" class="btn btn-primary">💾 Guardar Cambios</button>
        </div>
    </form>
</div>

</body>
</html>





