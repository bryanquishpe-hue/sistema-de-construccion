<?php
session_start();
include("conexion.php");

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'cliente') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['comprobante'])) {
    $pago_id = intval($_POST['pago_id']);
    $archivo = $_FILES['comprobante'];

    $nombre = basename($archivo['name']);
    $ruta = "comprobantes/" . $nombre;
    move_uploaded_file($archivo['tmp_name'], $ruta);

    $sql = "UPDATE pagos SET estado='confirmado' WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $pago_id);
    $stmt->execute();

    header("Location: cliente.php");
    exit();
}
?>
