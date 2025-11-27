<?php
session_start();
include("conexion.php");

// 🔐 Verificar sesión y rol
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'constructor') {
    header("Location: login.php");
    exit();
}

$constructor = $_SESSION['usuario'];
$constructor_id = $_SESSION['id'];
$email = $_SESSION['email'];

// 🔹 Proyectos asignados
$sqlProyectos = "SELECT * FROM proyectos WHERE constructor_id=?";
$stmt = $conn->prepare($sqlProyectos);
$stmt->bind_param("i", $constructor_id);
$stmt->execute();
$resultProyectos = $stmt->get_result();

// 🔹 Mensajes del constructor
$sqlMensajes = "SELECT * FROM contacto WHERE email=?";
$stmt3 = $conn->prepare($sqlMensajes);
$stmt3->bind_param("s", $email);
$stmt3->execute();
$resultMensajes = $stmt3->get_result();

// 🔹 Información del cliente y proyecto
$sqlInfo = "SELECT p.id, p.nombre AS proyecto, p.descripcion, p.estado,
                   c.nombre AS cliente, c.email, c.telefono, c.direccion
            FROM proyectos p
            JOIN clientes c ON p.cliente_id = c.id
            WHERE p.constructor_id = ?";
$stmtInfo = $conn->prepare($sqlInfo);
$stmtInfo->bind_param("i", $constructor_id);
$stmtInfo->execute();
$resultInfo = $stmtInfo->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Constructor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .navbar { background: linear-gradient(90deg, #004080, #ff0000); }
        .navbar-brand, .nav-link { color: #fff !important; font-weight: bold; }
        .card { box-shadow: 0 5px 15px rgba(0,0,0,0.2); border-radius: 10px; }
        h2, h5 { color: #004080; }
        table thead { background-color: #004080; color: #fff; }
    </style>
</head>
<body>

<!-- 🔹 Barra superior -->
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Panel Constructor</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><span class="nav-link">👷 <?= $constructor ?></span></li>
        <li class="nav-item"><a class="nav-link" href="logout.php">🚪 Cerrar Sesión</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- 🔹 Contenido -->
<div class="container mt-4">
    <h2 class="mb-4">Bienvenido <?= $constructor ?></h2>

    <div class="row">
        <!-- 🔷 Proyectos asignados -->
        <div class="col-md-6 mb-4">
            <div class="card p-3">
                <h5 class="card-title">🏗️ Mis Proyectos Asignados</h5>
                <?php if ($resultProyectos->num_rows > 0): ?>
                <table class="table table-hover table-bordered">
                    <thead><tr><th>ID</th><th>Nombre</th><th>Descripción</th><th>Estado</th></tr></thead>
                    <tbody>
                        <?php while ($row = $resultProyectos->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><?= $row['nombre'] ?></td>
                                <td><?= $row['descripcion'] ?></td>
                                <td>
                                    <?php 
                                        if ($row['estado'] == 'activo') echo "<span class='badge bg-success'>Activo</span>";
                                        elseif ($row['estado'] == 'pendiente') echo "<span class='badge bg-warning'>Pendiente</span>";
                                        else echo "<span class='badge bg-secondary'>Finalizado</span>";
                                    ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <p class="text-muted">No tienes proyectos asignados actualmente.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- 🔷 Información del Cliente y Proyecto -->
        <div class="col-md-12 mb-4">
            <div class="card p-3">
                <h5 class="card-title">📋 Información del Cliente y Proyecto</h5>
                <?php if ($resultInfo->num_rows > 0): ?>
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Proyecto</th><th>Descripción</th><th>Estado</th>
                                <th>Cliente</th><th>Email</th><th>Teléfono</th><th>Dirección</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $resultInfo->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['proyecto'] ?></td>
                                <td><?= $row['descripcion'] ?></td>
                                <td><span class="badge bg-info"><?= ucfirst($row['estado']) ?></span></td>
                                <td><?= $row['cliente'] ?></td>
                                <td><?= $row['email'] ?></td>
                                <td><?= $row['telefono'] ?></td>
                                <td><?= $row['direccion'] ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-muted">No hay información disponible.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- 🔷 Mensajes -->
        <div class="col-md-6 mb-4">
            <div class="card p-3">
                <h5 class="card-title">📩 Mis Mensajes</h5>
                <?php if ($resultMensajes->num_rows > 0): ?>
                <table class="table table-hover table-bordered">
                    <thead><tr><th>ID</th><th>Mensaje</th><th>Fecha</th><th>Estado</th></tr></thead>
                    <tbody>
                        <?php while ($row = $resultMensajes->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><?= $row['mensaje'] ?></td>
                                <td><?= $row['fecha'] ?></td>
                                <td>
                                    <?= $row['leido'] 
                                        ? "<span class='badge bg-success'>✔️ Leído</span>" 
                                        : "<span class='badge bg-warning'>⏳ No leído</span>"; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <p class="text-muted">No tienes mensajes registrados.</p>
                <?php endif; ?>
                <a href="contacto.php" class="btn btn-success mt-2">➕ Enviar nuevo mensaje</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>

