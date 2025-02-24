<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario</title>
    <!-- Aquí se vincula tu archivo de estilos personalizado (styles.css) -->
    <link rel="stylesheet" href="../public/styles.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Crear Nuevo Usuario</h1>

        <!-- Formulario para crear un nuevo usuario -->
        <form action="../controllers/authController.php" method="POST">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Usuario</label>
                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre del Usuario" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Correo Electrónico" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
            </div>

            <div class="mb-3">
                <label for="rol" class="form-label">Rol</label>
                <select class="form-select" id="rol" name="rol" required>
                    <option value="admin">Administrador</option>
                    <option value="usuario">Usuario</option>
                </select>
            </div>

            <!-- Botón para crear usuario -->
            <button type="submit" class="btn btn-primary" name="action" value="register">Crear Usuario</button>
            <a href="dashboard.php" class="btn btn-secondary ms-2">Volver al Panel</a>
        </form>
    </div>

    <!-- Aquí se vincula tu archivo de JavaScript personalizado (script.js) -->
    <script src="../public/script.js"></script>
</body>
</html>
