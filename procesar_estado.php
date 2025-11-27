<?php
include("../conexion.php");

$id_solicitud = $_POST["id_solicitud"];
$estado_admin = $_POST["estado_admin"];

$sql = "UPDATE solicitudes_trabajo
        SET estado_admin = ?
        WHERE id_solicitud = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("si", $estado_admin, $id_solicitud);

if ($stmt->execute()) {
    echo "<script>alert('Estado actualizado correctamente'); 
          window.location='admin_estado.php';</script>";
} else {
    echo "<script>alert('Error al actualizar'); history.back();</script>";
}
