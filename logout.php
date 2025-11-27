<?php
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cerrar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center" style="height:100vh;">
    <div class="card p-4 shadow text-center">
        <h3 class="text-danger">Sesión cerrada</h3>
        <p>Has cerrado sesión correctamente.</p>
        <a href="login.php" class="btn btn-primary">🔑 Volver al Login</a>
    </div>
</body>
</html>
