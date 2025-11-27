<?php
session_start();
include("conexion.php");

// 🔐 Verificar sesión y rol
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'administrador') {
    header("Location: login.php");
    exit();
}

// 📊 Consulta de usuarios por rol
$sqlUsuarios = "SELECT rol, COUNT(*) as total FROM usuarios GROUP BY rol";
$resultUsuarios = $conn->query($sqlUsuarios);

// 📊 Consulta de proyectos por estado
$sqlProyectos = "SELECT estado, COUNT(*) as total FROM proyectos GROUP BY estado";
$resultProyectos = $conn->query($sqlProyectos);

// 🔧 Consulta de proyectos individuales
$sqlListaProyectos = "SELECT id, nombre, estado FROM proyectos ORDER BY id DESC";
$listaProyectos = $conn->query($sqlListaProyectos);

// 🔄 Convertir resultados en arrays para gráficas
$usuariosData = [];
while ($row = $resultUsuarios->fetch_assoc()) {
    $usuariosData[$row['rol']] = $row['total'];
}

$proyectosData = [];
while ($row = $resultProyectos->fetch_assoc()) {
    $proyectosData[$row['estado']] = $row['total'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes - Panel Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #f8f9fa; }
        .navbar { background: linear-gradient(90deg, #004080, #ff0000); }
        .navbar-brand, .nav-link { color: #fff !important; font-weight: bold; }
        .section-title { color: #004080; font-weight: bold; }
    </style>
</head>
<body>

<!-- 🔹 Barra superior -->
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard.php">Panel Admin</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><span class="nav-link">👤 <?= $_SESSION['usuario']; ?> (<?= $_SESSION['rol']; ?>)</span></li>
        <li class="nav-item"><a class="nav-link" href="logout.php">🚪 Cerrar Sesión</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- 🔹 Contenido -->
<div class="container mt-4">
    <h2 class="mb-4 section-title">📊 Reportes del Sistema</h2>

    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'estado_actualizado'): ?>
        <div class="alert alert-success">✅ Estado del proyecto actualizado correctamente.</div>
    <?php endif; ?>

    <div class="row">
        <!-- Usuarios por rol -->
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title">Usuarios por Rol</h5>
                    <canvas id="usuariosChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Proyectos por estado -->
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title">Proyectos por Estado</h5>
                    <canvas id="proyectosChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- 🔹 Actualizar Estado de Proyectos -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <h5 class="card-title">🛠️ Actualizar Estado de Proyectos</h5>
            <?php if ($listaProyectos->num_rows > 0): ?>
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>ID</th><th>Nombre</th><th>Estado</th><th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($p = $listaProyectos->fetch_assoc()): ?>
                    <tr>
                        <td><?= $p['id'] ?></td>
                        <td><?= $p['nombre'] ?></td>
                        <td>
                            <form method="POST" action="acciones/actualizar_estado_proyecto.php" class="d-flex">
                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                <select name="estado" class="form-select form-select-sm me-2">
                                    <option value="activo" <?= $p['estado']=='activo'?'selected':'' ?>>Activo</option>
                                    <option value="pendiente" <?= $p['estado']=='pendiente'?'selected':'' ?>>Pendiente</option>
                                    <option value="finalizado" <?= $p['estado']=='finalizado'?'selected':'' ?>>Finalizado</option>
                                </select>
                                <button type="submit" class="btn btn-success btn-sm">💾 Guardar</button>
                            </form>
                        </td>
                        <td></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p class="text-muted">No hay proyectos registrados.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- 🔹 Scripts para gráficas -->
<script>
    const usuariosCtx = document.getElementById('usuariosChart').getContext('2d');
    new Chart(usuariosCtx, {
        type: 'pie',
        data: {
            labels: <?= json_encode(array_keys($usuariosData)); ?>,
            datasets: [{
                data: <?= json_encode(array_values($usuariosData)); ?>,
                backgroundColor: ['#004080','#ff0000','#ffc107','#28a745']
            }]
        }
    });

    const proyectosCtx = document.getElementById('proyectosChart').getContext('2d');
    new Chart(proyectosCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_keys($proyectosData)); ?>,
            datasets: [{
                label: 'Cantidad',
                data: <?= json_encode(array_values($proyectosData)); ?>,
                backgroundColor: ['#28a745','#ffc107','#6c757d']
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

