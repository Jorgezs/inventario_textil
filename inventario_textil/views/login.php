<?php

require_once('../controllers/authController.php');

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $authController = new AuthController();
    $loginResult = $authController->login($email, $password);

    if ($loginResult) {
        // Si el login es exitoso, redirigir al panel de administración
        header('Location: ../views/dashboard.php');
        exit();
    } else {
        $error_message = 'Credenciales incorrectas.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<style>
    body {
        background-color: #f8f9fa;
    }

    .login-container {
        max-width: 400px;
        margin: 80px auto;
        padding: 30px;
        background: white;
        border-radius: 10px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }

    .form-control:focus {
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }
</style>

<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="../public/styles.css">
</head>

<body>
    <div class="container">
        <div class="login-container">
            <div class="align-center text-center">
                <img src="../public/assets/login.png" height="200">
            </div>
            <form method="POST" action="login.php">
                <div class="mb-3">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input type="email" class="form-control" name="email" id="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" name="password" id="password" required>
                </div>
                <?php if (isset($error_message)) { ?>
                    <div class="alert alert-danger text-center"><?php echo $error_message; ?></div>
                <?php } ?>
                <button type="submit" class="btn btn-primary w-100">Iniciar sesión</button>
            </form>
            <p class="mt-3 text-center">
                <a href="#">¿Olvidaste tu contraseña?</a>
            </p>
        </div>
    </div>
</body>
<script src="../public/script.js"></script>

</html>