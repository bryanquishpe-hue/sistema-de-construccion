<?php
session_start();
require_once("../conexion.php");

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: ../login.php");
    exit();
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre    = trim($_POST['nombre'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $telefono  = trim($_POST['telefono'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $estado    = trim($_POST['estado'] ?? '');

    if ($nombre === '' || $email === '' || $estado === '') {
        $error = "❌ Debes completar los campos obligatorios.";
    } else {
        $sql = "INSERT INTO clientes (nombre, email, telefono, direccion, estado, fecha_registro) 
                VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $nombre, $email, $telefono, $direccion, $estado);

        if ($stmt->execute()) {
            header("Location: ../dashboard.php?msg=cliente_ok");
            exit();
        } else {
            $error = "❌ Error al registrar cliente.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('../assets/fondo_dashboard.jpg') no-repeat center center fixed;
            background-size: cover;
            position: relative;
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
        }
        body::before {
            content: "";
            position: absolute;
            inset: 0;
            background-color: rgba(0,0,0,0.6);
            z-index: 0;
        }
        .card-custom {
            position: relative;
            z-index: 1;
            max-width: 760px;
            margin: 80px auto;
            background-color: rgba(255,255,255,0.97);
            border-radius: 16px;
            box-shadow: 0 0 30px rgba(0,0,0,0.3);
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
        .form-control, .form-select {
            border-radius: 8px;
        }
        .alert {
            font-weight: 500;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="card card-custom">
    <div class="text-center mb-4">
        <h4 class="text-primary">➕ Registrar Nuevo Cliente</h4>
        <p class="text-muted">Completa los campos para agregar un nuevo cliente al sistema</p>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" novalidate>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Nombre <span class="text-danger">*</span></label>
                <input type="text" name="nombre" class="form-control" placeholder="Ej. Juan Pérez" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control" placeholder="Ej. juan@example.com" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Teléfono</label>
                <input type="text" name="telefono" class="form-control" placeholder="Ej. 0991234567">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Dirección</label>
                <input type="text" name="direccion" class="form-control" placeholder="Ej. Av. Amazonas 123">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Estado <span class="text-danger">*</span></label>
                <select name="estado" class="form-select" required>
                    <option value="">Seleccionar estado</option>
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                    <option value="pendiente">Pendiente</option>
                </select>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="../dashboard.php" class="btn btn-outline-secondary">↩️ Cancelar</a>
            <button type="submit" class="btn btn-primary">💾 Registrar Cliente</button>
        </div>
    </form>
</div>

</body>
</html>

