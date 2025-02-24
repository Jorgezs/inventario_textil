<?php
session_start();
require_once('../controllers/authController.php');

// Verificar si ya está logueado
if (isset($_SESSION['user_id'])) {
    // Si ya está logueado, redirigirlo al dashboard
    header('Location: ../views/dashboard.php');
    exit();
}

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
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="../public/styles.css">
</head>
<body>
    <form method="POST" action="login.php">
        <h2>Iniciar sesión xdd</h2>
        <div>
            <label for="email">Correo electrónico:</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div>
            <label for="password">Contraseña:</label>
            <input type="password" name="password" id="password" required>
        </div>
        <?php if (isset($error_message)) { ?>
            <div style="color: red;"><?php echo $error_message; ?></div>
        <?php } ?>
        <button type="submit">Iniciar sesión</button>
    </form>
</body>
</html>
