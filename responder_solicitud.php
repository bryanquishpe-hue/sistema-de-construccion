$sql = "SELECT * FROM solicitudes_trabajo WHERE constructor_id=? AND estado_admin='activado'";

<form method="POST">
    <select name="estado_constructor">
        <option value="pendiente">Pendiente</option>
        <option value="listo">Listo</option>
    </select>
    <textarea name="respuesta_constructor"></textarea>
    <button type="submit">Enviar Respuesta</button>
</form>
