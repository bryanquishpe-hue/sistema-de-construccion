<?php
include("conexion.php");

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre   = trim($_POST['nombre']);
    $email    = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $rol      = $_POST['rol'];

    $sql = "INSERT INTO usuarios (nombre, email, password, rol) VALUES (?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $nombre, $email, $password, $rol);

    if ($stmt->execute()) {
        $mensaje = "<div class='alert alert-success text-center'>✅ Usuario registrado correctamente.</div>";
    } else {
        $mensaje = "<div class='alert alert-danger text-center'>❌ Error: " . $conn->error . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuarios</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('https://picsum.photos/1920/1080?random=30') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card {
            width: 400px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.5);
            background-color: rgba(255,255,255,0.92); /* Transparencia para ver el fondo */
        }
        .btn-primary {
            background-color: #004080;
            border: none;
        }
        .btn-primary:hover {
            background-color: #00264d;
        }
        .form-label {
            font-weight: bold;
            color: #004080;
        }
    </style>
</head>
<body>
    <div class="card p-4">
        <h3 class="text-center mb-3" style="color:#004080;">Registro de Usuario</h3>
        
        <!-- Mensajes dinámicos -->
        <?php if (!empty($mensaje)) echo $mensaje; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control" placeholder="Tu nombre completo" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="ejemplo@correo.com" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input type="password" name="password" class="form-control" placeholder="********" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Rol</label>
                <select name="rol" class="form-select" required>
                    <option value="administrador">Administrador</option>
                    <option value="cliente">Cliente</option>
                    <option value="constructor">Constructor</option>
                </select>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Registrar</button>
            </div>
        </form>
        <p class="text-center mt-3">
            ¿Ya tienes cuenta? <a href="login.php" class="text-danger">Inicia sesión aquí</a>
        </p>
    </div>
</body>
</html>

