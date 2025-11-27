$sql = "SELECT s.*, c.nombre AS cliente, p.nombre AS proyecto
        FROM solicitudes_trabajo s
        JOIN clientes c ON s.cliente_id = c.id
        LEFT JOIN proyectos p ON s.proyecto_id = p.id";
$result = $conn->query($sql);
<?php
// Obtener lista de constructores
$sqlConstructores = "SELECT id, nombre FROM usuarios WHERE rol='constructor'";
$constructores = $conn->query($sqlConstructores);
?>

<?php while ($row = $result->fetch_assoc()) { ?>
    <form method="POST" action="actualizar_solicitud.php" class="card p-3 mb-3">
        <input type="hidden" name="solicitud_id" value="<?php echo $row['id']; ?>">
        <h5>📝 Solicitud #<?php echo $row['id']; ?> de <?php echo $row['cliente']; ?></h5>
        <p><strong>Proyecto:</strong> <?php echo $row['proyecto']; ?></p>
        <p><strong>Descripción:</strong> <?php echo $row['descripcion']; ?></p>
        <p><strong>Estado actual:</strong> <?php echo ucfirst($row['estado_admin']); ?></p>

        <div class="row">
            <div class="col-md-6 mb-2">
                <label>Estado administrativo</label>
                <select name="estado_admin" class="form-select" required>
                    <option value="pendiente" <?php if ($row['estado_admin']=='pendiente') echo 'selected'; ?>>Pendiente</option>
                    <option value="activado" <?php if ($row['estado_admin']=='activado') echo 'selected'; ?>>Activado</option>
                    <option value="finalizado" <?php if ($row['estado_admin']=='finalizado') echo 'selected'; ?>>Finalizado</option>
                </select>
            </div>
            <div class="col-md-6 mb-2">
                <label>Asignar Constructor</label>
                <select name="constructor_id" class="form-select" required>
                    <option value="">Seleccione</option>
                    <?php while ($c = $constructores->fetch_assoc()) { ?>
                        <option value="<?php echo $c['id']; ?>" <?php if ($row['constructor_id']==$c['id']) echo 'selected'; ?>>
                            <?php echo $c['nombre']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Actualizar</button>
    </form>
<?php } ?>

