<?php
session_start();
include("conexion.php");

// Solo administrador
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'administrador') {
    header("Location: login.php");
    exit();
}

// Marcar como leído
if (isset($_GET['leido'])) {
    $id = intval($_GET['leido']);
    $sql = "UPDATE contacto SET leido=1 WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// Obtener mensajes
$sql = "SELECT * FROM contacto ORDER BY fecha DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mensajes de Contacto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2 class="mb-4">📨 Mensajes de Contacto</h2>
    <table class="table table-striped table-hover shadow">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Mensaje</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['nombre']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['mensaje']; ?></td>
                    <td><?php echo $row['fecha']; ?></td>
                    <td>
                        <?php echo $row['leido'] ? "<span class='badge bg-success'>Leído</span>" : "<span class='badge bg-warning text-dark'>No leído</span>"; ?>
                    </td>
                    <td>
                        <?php if (!$row['leido']) { ?>
                            <a href="mensajes.php?leido=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">✔️ Marcar leído</a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
