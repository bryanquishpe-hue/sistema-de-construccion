<?php
session_start();
include("conexion.php");

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'cliente') {
    header("Location: login.php");
    exit();
}

$cliente_id = $_SESSION['id'];

// Contar pagos
$sql = "SELECT estado, COUNT(*) as total FROM pagos WHERE cliente_id=? GROUP BY estado";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cliente_id);
$stmt->execute();
$result = $stmt->get_result();

$confirmados = 0;
$pendientes = 0;
while ($row = $result->fetch_assoc()) {
    if ($row['estado'] == 'confirmado') $confirmados = $row['total'];
    if ($row['estado'] == 'pendiente') $pendientes = $row['total'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gráfico de Pagos</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3>📊 Estado de Pagos</h3>
    <canvas id="graficoPagos" width="400" height="200"></canvas>
</div>
<script>
new Chart(document.getElementById('graficoPagos'), {
  type: 'doughnut',
  data: {
    labels: ['Confirmados', 'Pendientes'],
    datasets: [{
      data: [<?php echo $confirmados; ?>, <?php echo $pendientes; ?>],
      backgroundColor: ['#28a745','#ffc107']
    }]
  }
});
</script>
</body>
</html>
