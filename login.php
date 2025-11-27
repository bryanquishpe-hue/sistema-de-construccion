<?php
session_start();
include("conexion.php");

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM usuarios WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        if (password_verify($password, $usuario['password'])) {
            // Guardar datos en sesión
            $_SESSION['usuario'] = $usuario['nombre'];
            $_SESSION['rol'] = $usuario['rol'];
            $_SESSION['id'] = $usuario['id'];
            $_SESSION['email'] = $usuario['email'];

            // Redirección según rol
            if ($usuario['rol'] == 'administrador') {
                header("Location: dashboard.php");
            } elseif ($usuario['rol'] == 'cliente') {
                header("Location: cliente.php");
            } elseif ($usuario['rol'] == 'constructor') {
                header("Location: constructor.php");
            } else {
                // Rol desconocido → redirigir a login
                header("Location: login.php");
            }
            exit();
        } else {
            $mensaje = "<div class='alert alert-danger text-center'>Contraseña incorrecta ❌</div>";
        }
    } else {
        $mensaje = "<div class='alert alert-warning text-center'>Usuario no encontrado ⚠️</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - ServiConstruccion</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('https://picsum.photos/1920/1080?random=20') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card {
            width: 350px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.5);
            background-color: rgba(255,255,255,0.9);
        }
        .btn-primary {
            background-color: #004080;
            border: none;
        }
        .btn-primary:hover {
            background-color: #00264d;
        }
    </style>
</head>
<body>
    <div class="card p-4">
        <h3 class="text-center mb-3" style="color:#004080;">Acceso al Sistema</h3>
        
        <!-- Mensajes dinámicos -->
        <?php if (!empty($mensaje)) echo $mensaje; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="ejemplo@correo.com" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input type="password" name="password" class="form-control" placeholder="********" required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
            </div>
        </form>
        <p class="text-center mt-3">
            ¿No tienes cuenta? <a href="registro.php" class="text-danger">Regístrate aquí</a>
        </p>
    </div>
</body>
</html>
