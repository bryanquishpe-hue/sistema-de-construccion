<?php
session_start();
require_once("conexion.php");

// 🔐 SEGURIDAD: SOLO ADMINISTRADORES
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: login.php");
    exit();
}

/* ==========================================================
   🔷 CONSULTAS PRINCIPALES
========================================================== */

// 🔹 Reporte de ventas
$sqlVentas = "SELECT SUM(total) AS total_ventas, COUNT(*) AS cantidad_facturas     
              FROM facturas WHERE estado='emitida'";
$ventas = $conn->query($sqlVentas)->fetch_assoc();

// 🔹 Solicitudes pendientes
$sqlSolicitudes = "SELECT st.id, c.nombre AS cliente, p.nombre AS proyecto, st.descripcion, st.estado_admin
                   FROM solicitudes_trabajo st
                   JOIN clientes c ON st.cliente_id = c.id
                   JOIN proyectos p ON st.proyecto_id = p.id
                   WHERE st.estado_admin = 'pendiente'";
$resultSolicitudes = $conn->query($sqlSolicitudes);

// 🔹 Constructores
$sqlConstructores = "SELECT id, nombre FROM usuarios WHERE rol='constructor'";
$constructores = $conn->query($sqlConstructores);

// 🔹 Respuestas del constructor
$sqlRespuestas = "SELECT st.id, c.nombre AS cliente, u.nombre AS constructor, st.respuesta_constructor
                  FROM solicitudes_trabajo st
                  JOIN clientes c ON st.cliente_id = c.id
                  JOIN usuarios u ON st.constructor_id = u.id
                  WHERE st.estado_cliente = 'respondido'";
$respuestas = $conn->query($sqlRespuestas);

// 🔹 Clientes registrados
$clientes = $conn->query("SELECT * FROM clientes");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .navbar { background: linear-gradient(90deg, #004080, #ff0000); }
        .navbar-brand, .nav-link { color: white !important; font-weight: bold; }
        .card { box-shadow: 0px 5px 15px rgba(0,0,0,0.2); border-radius: 12px; }
        h3, h5 { color: #004080; }
    </style>
</head>
<body>

<!-- ==========================================================
     🔷 NAVBAR
========================================================== -->
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Panel Administrador</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
            <span class="nav-link">👤 <?= $_SESSION['usuario'] ?> (Administrador)</span>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="logout.php">🚪 Cerrar Sesión</a>
        </li>
      </ul>
    </div>
  </div>
</nav>


<div class="container mt-4">
<h3 class="mb-4">📊 Panel del Administrador</h3>


<!-- ==========================================================
     🔷 SECCIÓN 1 : REPORTE DE VENTAS
========================================================== -->
<div class="card p-3 mb-4">
    <h5>🧾 Reporte de Ventas</h5>

    <p><strong>Total vendido:</strong> $<?= number_format($ventas['total_ventas'], 2) ?></p>
    <p><strong>Facturas emitidas:</strong> <?= $ventas['cantidad_facturas'] ?></p>

    <a href="reportes.php" class="btn btn-secondary btn-sm">📈 Ver Detalles</a>
</div>


<!-- ==========================================================
     🔷 SECCIÓN 2 : SOLICITUDES PENDIENTES
========================================================== -->
<div class="card p-3 mb-4">
    <h5>📩 Solicitudes de Trabajo Pendientes</h5>

    <p class="text-muted">🔎 Revisar información del cliente y proyecto asociado.</p>

    <?php if ($resultSolicitudes->num_rows > 0): ?>
        <table class="table table-bordered table-hover table-sm">
            <thead class="table-primary">
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Proyecto</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $resultSolicitudes->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['cliente'] ?></td>
                    <td><?= $row['proyecto'] ?></td>
                    <td><?= $row['descripcion'] ?></td>
                    <td>
                        <form method="POST" action="acciones/asignar_constructor.php" class="d-flex gap-2">
                            <input type="hidden" name="solicitud_id" value="<?= $row['id'] ?>">

                            <select name="constructor_id" class="form-select form-select-sm" required>
                                <option value="">Constructor</option>
                                <?php mysqli_data_seek($constructores, 0);
                                while ($c = $constructores->fetch_assoc()): ?>
                                    <option value="<?= $c['id'] ?>"><?= $c['nombre'] ?></option>
                                <?php endwhile; ?>
                            </select>

                            <select name="estado_admin" class="form-select form-select-sm" required>
                                <option value="activado">Activar</option>
                                <option value="rechazado">Rechazar</option>
                            </select>

                            <button type="submit" class="btn btn-success btn-sm">Asignar</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-secondary text-center">No existen solicitudes pendientes.</div>
    <?php endif; ?>
</div>


<!-- ==========================================================
     🔷 SECCIÓN 3 : RESPUESTAS DEL CONSTRUCTOR
========================================================== -->
<div class="card p-3 mb-4">
    <h5>📨 Respuestas del Constructor</h5>

    <?php if ($respuestas->num_rows > 0): ?>
        <?php while ($r = $respuestas->fetch_assoc()): ?>
            <div class="border p-3 mb-2 rounded">
                <p><strong>Cliente:</strong> <?= $r['cliente'] ?></p>
                <p><strong>Constructor:</strong> <?= $r['constructor'] ?></p>
                <p><strong>Respuesta:</strong> <?= $r['respuesta_constructor'] ?></p>

                <form method="POST" action="enviar_respuesta_cliente.php">
                    <input type="hidden" name="solicitud_id" value="<?= $r['id'] ?>">
                    <textarea name="mensaje_cliente" class="form-control mb-2" placeholder="Mensaje para el cliente" required></textarea>
                    <button class="btn btn-success btn-sm">📤 Enviar al Cliente</button>
                </form>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="text-muted">No hay respuestas nuevas.</p>
    <?php endif; ?>
</div>


<!-- ==========================================================
     🔷 SECCIÓN 4 : CLIENTES REGISTRADOS
========================================================== -->
<div class="card p-3 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5>👥 Clientes Registrados</h5>
        <a href="administrador/agregar_cliente.php" class="btn btn-primary btn-sm">➕ Agregar Cliente</a>
    </div>

    <table class="table table-bordered table-sm">
        <thead class="table-light">
            <tr>
                <th>Nombre</th><th>Email</th><th>Teléfono</th><th>Dirección</th><th>Estado</th><th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($c = $clientes->fetch_assoc()): ?>
            <tr>
                <td><?= $c['nombre'] ?></td>
                <td><?= $c['email'] ?></td>
                <td><?= $c['telefono'] ?></td>
                <td><?= $c['direccion'] ?></td>
                <td><?= $c['estado'] ?></td>
                <td>
                    <a href="administrador/editar_cliente.php?id=<?= $c['id'] ?>" class="btn btn-warning btn-sm">✏️ Editar</a>
                    <a href="administrador/eliminar_cliente.php?id=<?= $c['id'] ?>" 
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('¿Eliminar cliente?')">🗑️ Eliminar</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</div> <!-- container -->
</body>
</html>


