<?php
session_start();
include("../conexion.php");

// Solo admins
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'administrador') {
    header("Location: ../login.php");
    exit();
}

// Traer solicitudes pendientes
$sqlSolicitudes = "SELECT * FROM solicitudes_trabajo WHERE estado_admin = 'pendiente'";
$pendientes = $conexion->query($sqlSolicitudes);

// Traer lista de constructores
$sqlConstructores = "SELECT id_usuario, nombre FROM usuarios WHERE rol = 'constructor'";
$constructores = $conexion->query($sqlConstructores);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignar Constructor</title>
</head>
<body>

<h2>Asignar un constructor a una solicitud pendiente</h2>

<form action="procesar_asignacion.php" method="POST">

    <label>Solicitud pendiente:</label><br>
    <select name="id_solicitud" required>
        <option value="">Seleccione</option>
        <?php while($fila = $pendientes->fetch_assoc()) { ?>
            <option value="<?= $fila['id_solicitud'] ?>">
                Solicitud #<?= $fila['id_solicitud'] ?> - <?= $fila['descripcion'] ?>
            </option>
        <?php } ?>
    </select>

    <br><br>

    <label>Asignar al constructor:</label><br>
    <select name="id_constructor" required>
        <option value="">Seleccione</option>
        <?php while($cons = $constructores->fetch_assoc()) { ?>
            <option value="<?= $cons['id_usuario'] ?>">
                <?= $cons['nombre'] ?>
            </option>
        <?php } ?>
    </select>

    <br><br>

    <button type="submit">Asignar</button>
</form>

</body>
</html>
