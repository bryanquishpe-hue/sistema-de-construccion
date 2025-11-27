<?php
session_start();
include("conexion.php");

// Verificar sesión y rol
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'administrador') {
    header("Location: login.php");
    exit();
}

// Consulta de proyectos
$sql = "SELECT id, nombre, descripcion, estado, fecha_inicio, fecha_fin FROM proyectos";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Proyectos</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .navbar { background: linear-gradient(90deg, #004080, #ff0000); }
        .navbar-brand, .nav-link { color: #fff !important; font-weight: bold; }
    </style>
</head>
<body>

<!-- 🔹 Barra superior -->
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard.php">Panel Admin</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><span class="nav-link">👤 <?php echo $_SESSION['usuario']; ?> (<?php echo $_SESSION['rol']; ?>)</span></li>
        <li class="nav-item"><a class="nav-link" href="logout.php">🚪 Cerrar Sesión</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- 🔹 Contenido -->
<div class="container mt-4">
    <h2 class="mb-4">Gestión de Proyectos</h2>

    <!-- Botón para agregar proyecto -->
    <a href="nuevo_proyecto.php" class="btn btn-success mb-3">➕ Registrar Nuevo Proyecto</a>

    <!-- Tabla de proyectos -->
    <div class="table-responsive">
        <table class="table table-striped table-hover shadow">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['nombre']; ?></td>
                        <td><?php echo $row['descripcion']; ?></td>
                        <td>
                            <?php 
                                if ($row['estado'] == 'activo') {
                                    echo "<span class='badge bg-success'>Activo</span>";
                                } elseif ($row['estado'] == 'pendiente') {
                                    echo "<span class='badge bg-warning text-dark'>Pendiente</span>";
                                } else {
                                    echo "<span class='badge bg-secondary'>Finalizado</span>";
                                }
                            ?>
                        </td>
                        <td><?php echo $row['fecha_inicio']; ?></td>
                        <td><?php echo $row['fecha_fin']; ?></td>
                        <td>
                            <a href="editar_proyecto.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">✏️ Editar</a>
                            <a href="eliminar_proyecto.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar este proyecto?');">🗑️ Eliminar</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
