<?php
session_start();
include("conexion.php");

// Verificar sesión y rol
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'cliente') {
    header("Location: login.php");
    exit();
}

$cliente = $_SESSION['usuario'];
$cliente_id = $_SESSION['id'];
$email = $_SESSION['email'];

// 🔹 PERFIL DEL CLIENTE
$sqlPerfil = "SELECT * FROM clientes WHERE id=?";
$stmtPerfil = $conn->prepare($sqlPerfil);
$stmtPerfil->bind_param("i", $cliente_id);
$stmtPerfil->execute();
$perfil = $stmtPerfil->get_result()->fetch_assoc();

// 🔹 PROYECTOS DEL CLIENTE
$sqlProyectos = "SELECT * FROM proyectos WHERE cliente_id=?";
$stmt = $conn->prepare($sqlProyectos);
$stmt->bind_param("i", $cliente_id);
$stmt->execute();
$resultProyectos = $stmt->get_result();

// 🔹 PAGOS DEL CLIENTE
$sqlPagos = "SELECT pa.id, pa.monto, pa.estado, pa.fecha, p.nombre AS proyecto
             FROM pagos pa
             JOIN proyectos p ON pa.proyecto_id = p.id
             WHERE pa.cliente_id=?";
$stmt2 = $conn->prepare($sqlPagos);
$stmt2->bind_param("i", $cliente_id);
$stmt2->execute();
$resultPagos = $stmt2->get_result();

// 🔹 MENSAJES DEL CLIENTE (tabla contacto)
$sqlMensajes = "SELECT * FROM contacto WHERE email=?";
$stmt3 = $conn->prepare($sqlMensajes);
$stmt3->bind_param("s", $email);
$stmt3->execute();
$resultMensajes = $stmt3->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .navbar { background: linear-gradient(90deg, #004080, #ff0000); }
        .navbar-brand, .nav-link { color: #fff !important; font-weight: bold; }
        .card { box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
    </style>
</head>
<body>

<!-- 🔹 Barra superior -->
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Panel Cliente</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><span class="nav-link">👤 <?php echo $cliente; ?></span></li>
        <li class="nav-item"><a class="nav-link" href="logout.php">🚪 Cerrar Sesión</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- 🔹 Contenido -->
<div class="container mt-4">
    <h2 class="mb-4">Bienvenido <?php echo $cliente; ?></h2>

    <!-- Perfil -->
    <div class="card p-3 mb-4">
        <h5 class="card-title">👤 Mi Perfil</h5>
        <p><strong>Nombre:</strong> <?php echo $perfil['nombre']; ?></p>
        <p><strong>Email:</strong> <?php echo $perfil['email']; ?></p>
        <p><strong>Teléfono:</strong> <?php echo $perfil['telefono']; ?></p>
        <p><strong>Dirección:</strong> <?php echo $perfil['direccion']; ?></p>
        <p><strong>Estado:</strong> <?php echo ucfirst($perfil['estado']); ?></p>
        <a href="editar_perfil.php" class="btn btn-secondary btn-sm">✏️ Editar Perfil</a>
        <a href="cambiar_password.php" class="btn btn-warning btn-sm">🔑 Cambiar Contraseña</a>
    </div>

    <div class="row">
        <!-- Proyectos -->
        <div class="col-md-6 mb-4">
            <div class="card p-3">
                <h5 class="card-title">📂 Mis Proyectos</h5>
                <table class="table table-sm table-striped">
                    <thead><tr><th>ID</th><th>Nombre</th><th>Descripción</th><th>Estado</th></tr></thead>
                    <tbody>
                        <?php while ($row = $resultProyectos->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['nombre']; ?></td>
                                <td><?php echo $row['descripcion']; ?></td>
                                <td><?php echo ucfirst($row['estado']); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagos -->
        <div class="col-md-6 mb-4">
            <div class="card p-3">
                <h5 class="card-title">💳 Mis Pagos</h5>
                <table class="table table-sm table-striped">
                    <thead><tr><th>ID</th><th>Proyecto</th><th>Monto</th><th>Estado</th><th>Fecha</th><th>Acciones</th></tr></thead>
                    <tbody>
                        <?php while ($row = $resultPagos->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['proyecto']; ?></td>
                                <td>$<?php echo $row['monto']; ?></td>
                                <td><?php echo $row['estado']=='confirmado' ? "<span class='badge bg-success'>Confirmado</span>" : "<span class='badge bg-warning'>Pendiente</span>"; ?></td>
                                <td><?php echo $row['fecha']; ?></td>
                                <td>
                                    <?php if ($row['estado']=='pendiente') { ?>
                                        <a href="confirmar_pago.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">✔️ Confirmar</a>
                                    <?php } ?>
                                    <a href="factura.php?pago_id=<?php echo $row['id']; ?>" class="btn btn-secondary btn-sm">🧾 Factura</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Mensajes -->
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card p-3">
                <h5 class="card-title">📩 Mis Mensajes</h5>
                <table class="table table-sm table-striped">
                    <thead><tr><th>ID</th><th>Mensaje</th><th>Fecha</th><th>Estado</th></tr></thead>
                    <tbody>
                        <?php while ($row = $resultMensajes->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['mensaje']; ?></td>
                                <td><?php echo $row['fecha']; ?></td>
                                <td><?php echo $row['leido'] ? "<span class='badge bg-success'>Leído</span>" : "<span class='badge bg-warning'>No leído</span>"; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <a href="contacto.php" class="btn btn-success">➕ Enviar nuevo mensaje</a>
            </div>
        </div>
    </div>
</div>
<form method="POST" action="confirmar_pago.php?id=<?php echo $row['id']; ?>" enctype="multipart/form-data">
    <select name="metodo_pago" class="form-select mb-2" required>
        <option value="efectivo">Efectivo</option>
        <option value="transferencia">Transferencia</option>
    </select>
    <label>Subir comprobante (PDF o imagen)</label>
    <input type="file" name="comprobante" class="form-control mb-2" accept=".pdf,.jpg,.jpeg,.png">
    <button type="submit" class="btn btn-primary btn-sm">✔️ Confirmar Pago</button>
</form>

</body>
</html>
