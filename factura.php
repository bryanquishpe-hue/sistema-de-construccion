<?php
session_start();
include("conexion.php");

// Verificar que sea administrador
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'administrador') {
    header("Location: login.php");
    exit();
}

// Obtener facturas
$sql = "SELECT f.id, f.fecha, f.total, f.estado, c.nombre AS cliente, p.monto AS pago
        FROM facturas f
        JOIN clientes c ON f.cliente_id = c.id
        JOIN pagos p ON f.pago_id = p.id
        ORDER BY f.fecha DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Facturación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h3>🧾 Facturación</h3>
    <table class="table table-bordered table-hover">
        <thead><tr><th>ID</th><th>Cliente</th><th>Pago</th><th>Total</th><th>Fecha</th><th>Estado</th><th>Acciones</th></tr></thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['cliente']; ?></td>
                    <td>$<?php echo $row['pago']; ?></td>
                    <td>$<?php echo $row['total']; ?></td>
                    <td><?php echo $row['fecha']; ?></td>
                    <td>
                        <?php
                            if ($row['estado'] == 'emitida') echo "<span class='badge bg-warning'>Emitida</span>";
                            elseif ($row['estado'] == 'enviada') echo "<span class='badge bg-info'>Enviada</span>";
                            else echo "<span class='badge bg-success'>Pagada</span>";
                        ?>
                    </td>
                    <td>
                        <a href="ver_factura.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-secondary">🔍 Ver</a>
                        <a href="descargar_factura.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-success">⬇️ PDF</a>
                        <a href="enviar_factura.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">📤 Enviar</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
