<form method="POST" action="procesar_solicitud.php" class="card p-3 mb-4">
    <h5>📩 Solicitar Trabajo</h5>
    <select name="proyecto_id" class="form-select mb-2" required>
        <option value="">Seleccione proyecto</option>
        <?php
        $sql = "SELECT id, nombre FROM proyectos WHERE cliente_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_SESSION['id']);
        $stmt->execute();
        $proyectos = $stmt->get_result();
        while ($p = $proyectos->fetch_assoc()) {
            echo "<option value='{$p['id']}'>{$p['nombre']}</option>";
        }
        ?>
    </select>
    <textarea name="descripcion" class="form-control mb-2" placeholder="Describa el trabajo solicitado" required></textarea>
    <button type="submit" class="btn btn-primary">Enviar Solicitud</button>
</form>

