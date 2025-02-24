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
    <title>Crear Usuario</title>
</head>
<body>
    <h1>Crear Nuevo Usuario</h1>
    <form action="../controllers/authController.php" method="POST">
    <input type="text" name="nombre" placeholder="Nombre del Usuario" required><br>
    <input type="email" name="email" placeholder="Correo Electrónico" required><br>
    <input type="password" name="password" placeholder="Contraseña" required><br>
    <select name="rol" required>
        <option value="admin">Administrador</option>
        <option value="usuario">Usuario</option>
    </select><br>
    <button type="submit" name="action" value="register">Crear Usuario</button>
</form>

</body>
</html>
