<?php
session_start();
include("conexion.php");

$pago_id = $_GET['id'];
$metodo = $_POST['metodo_pago'];
$cliente_id = $_SESSION['id'];

// Guardar comprobante si se subió
$archivo = null;
if (isset($_FILES['comprobante']) && $_FILES['comprobante']['error'] == 0) {
    $nombreArchivo = 'comprobante_' . $pago_id . '_' . time() . '.' . pathinfo($_FILES['comprobante']['name'], PATHINFO_EXTENSION);
    move_uploaded_file($_FILES['comprobante']['tmp_name'], 'comprobantes/' . $nombreArchivo);
    $archivo = $nombreArchivo;
}

// Actualizar pago
$sql = "UPDATE pagos SET estado='confirmado', metodo_pago=?, comprobante=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $metodo, $archivo, $pago_id);
$stmt->execute();

// Generar factura
$sqlFactura = "INSERT INTO facturas (pago_id, cliente_id, total, estado, archivo_pdf) 
               SELECT id, cliente_id, monto, 'emitida', CONCAT('factura_', id, '.pdf') 
               FROM pagos WHERE id=?";
$stmtFactura = $conn->prepare($sqlFactura);
$stmtFactura->bind_param("i", $pago_id);
$stmtFactura->execute();

header("Location: cliente.php?msg=pago_confirmado");
?>

