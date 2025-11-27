<?php
include("conexion.php");

$mensaje = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $texto = trim($_POST['mensaje']);

    $sql = "INSERT INTO contacto (nombre, email, mensaje) VALUES (?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nombre, $email, $texto);

    if ($stmt->execute()) {
        $mensaje = "<div class='alert alert-success text-center'>✅ Mensaje enviado correctamente.</div>";
    } else {
        $mensaje = "<div class='alert alert-danger text-center'>❌ Error al enviar mensaje.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contacto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="text-center mb-4">📩 Contáctanos</h2>
    <?php if (!empty($mensaje)) echo $mensaje; ?>
    <form method="POST" class="card p-4 shadow">
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Correo</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Mensaje</label>
            <textarea name="mensaje" class="form-control" rows="4" required></textarea>
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Enviar Mensaje</button>
        </div>
    </form>
</div>
</body>
</html>
