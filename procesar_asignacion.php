<?php
include("../conexion.php");

$id_solicitud = $_POST['id_solicitud'];
$id_constructor = $_POST['id_constructor'];

$sql = "UPDATE solicitudes_trabajo 
        SET id_constructor = ?, estado_admin = 'activado'
        WHERE id_solicitud = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii", $id_constructor, $id_solicitud);

if ($stmt->execute()) {
    echo "<script>alert('Constructor asignado correctamente'); 
          window.location='admin_asignar.php';</script>";
} else {
    echo "<script>alert('Error al asignar'); history.back();</script>";
}
