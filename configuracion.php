<?php
session_start();
include("conexion.php");

// Verificar sesión y rol
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'administrador') {
    header("Location: login.php");
    exit();
}

// Mensaje de confirmación
$mensaje = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ejemplo: actualización de configuración
    $tema = $_POST['tema'];
    $notificaciones = isset($_POST['notificaciones']) ? 1 : 0;

    // Aquí podrías guardar en una tabla "configuracion" o en variables globales
    $mensaje = "<div class='alert alert-success text-center'>✅ Configuración actualizada correctamente.</div>";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Configuración - Panel Admin</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .navbar { background: linear-gradient(90deg, #004080, #ff0000); }
        .navbar-brand, .nav-link { color: #fff !important; font-weight: bold; }
        .card { box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
    </style>
</head>
<body>

<!-- 🔹 Barra superior -->
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard.php">Panel Admin</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><span class="nav-link">👤 <?php echo $_SESSION['usuario']; ?> (<?php echo $_SESSION['rol']; ?>)</span></li>
        <li class="nav-item"><a class="nav-link" href="logout.php">🚪 Cerrar Sesión</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- 🔹 Contenido -->
<div class="container mt-4">
    <h2 class="mb-4">⚙️ Configuración del Sistema</h2>

    <!-- Mensaje dinámico -->
    <?php if (!empty($mensaje)) echo $mensaje; ?>

    <div class="row">
        <!-- Configuración general -->
        <div class="col-md-6 mb-4">
            <div class="card p-4">
                <h5 class="card-title">Preferencias Generales</h5>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label">Tema del sistema</label>
                        <select name="tema" class="form-select">
                            <option value="claro">Claro</option>
                            <option value="oscuro">Oscuro</option>
                        </select>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="notificaciones" id="notificaciones">
                        <label class="form-check-label" for="notificaciones">Activar notificaciones por correo</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </form>
            </div>
        </div>

        <!-- Configuración de perfil -->
        <div class="col-md-6 mb-4">
            <div class="card p-4">
                <h5 class="card-title">Perfil del Administrador</h5>
                <p><strong>Nombre:</strong> <?php echo $_SESSION['usuario']; ?></p>
                <p><strong>Rol:</strong> <?php echo $_SESSION['rol']; ?></p>
                <a href="editar_perfil.php" class="btn btn-secondary">✏️ Editar Perfil</a>
                <a href="cambiar_password.php" class="btn btn-warning">🔑 Cambiar Contraseña</a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
