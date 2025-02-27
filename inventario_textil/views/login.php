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
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg,rgb(66, 67, 73),rgb(124, 94, 160));
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        .form-control:focus {
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.7);
            border-color: #007bff;
        }
        .btn-primary {
            background: #007bff;
            border: none;
            transition: 0.3s;
        }
        .btn-primary:hover {
            background: #0056b3;
        }
        .login-container img {
            max-height: 80px;
            margin-bottom: 15px;
        }
        /* Estilos de la pantalla splash */
        .splash-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            z-index: 9999;
            opacity: 1;
            transition: opacity 1s ease-out; /* Transición para desvanecer */
        }
        .splash-screen img {
            max-width: 200px;  /* Tamaño máximo de la imagen */
            animation: fadeIn 1.5s ease-out;  /* Animación de aparición */
        }
        .splash-screen h1 {
            color: white;
            font-size: 2rem;
            margin-top: 20px;
            opacity: 0;
            animation: fadeInText 1.5s ease-out 0.5s forwards; /* Animación para el texto */
        }
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: scale(0.5);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }
        @keyframes fadeInText {
            0% {
                opacity: 0;
                transform: translateY(10px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>

    <!-- Pantalla splash -->
    <div class="splash-screen" id="splash-screen">
        <img src="../public/assets/textil3.jpeg" alt="Splash Image"> <!-- Reemplaza con tu imagen -->
        <h1>Bienvenido al Inventario Textil</h1> <!-- Texto de bienvenida -->
    </div>

    <div class="login-container">
        <img src="../public/assets/login.png" alt="Login Icon">
        <h3 class="mb-3">Iniciar Sesión</h3>
        <form method="POST" action="login.php">
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Correo electrónico" required>
                </div>
            </div>
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Contraseña" required>
                </div>
            </div>
            <?php if (isset($error_message)) { ?>
                <div class="alert alert-danger text-center"> <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?></div>
            <?php } ?>
            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-sign-in-alt"></i> Iniciar sesión</button>
        </form>
        <p class="mt-3">
            <a href="#" class="text-decoration-none"><i class="fas fa-question-circle"></i> ¿Olvidaste tu contraseña?</a>
        </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Al cargar la página, se oculta la pantalla splash con animación
        window.onload = function() {
            setTimeout(function() {
                document.getElementById('splash-screen').style.opacity = '0';
                setTimeout(function() {
                    document.getElementById('splash-screen').style.display = 'none';
                }, 1000);  // Tiempo para desvanecer la splash antes de ocultarla
            }, 1500); // Duración de la pantalla splash antes de comenzar el fade-out
        };
    </script>
</body>
</html>
