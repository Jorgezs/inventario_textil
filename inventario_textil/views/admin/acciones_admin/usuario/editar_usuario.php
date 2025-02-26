<?php
session_start();
require_once '../../../../models/Usuario.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../views/login.php');
    exit();
}

if (isset($_GET['id'])) {
    $id_usuario = $_GET['id'];
    $usuario = Usuario::getById($id_usuario);

    if (!$usuario) {
        echo "Usuario no encontrado.";
        exit();
    }
} else {
    echo "ID de usuario no proporcionado.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password']; // La contraseña puede ser opcional, si no la cambian.
    $rol = $_POST['rol'];

    // Si la contraseña está vacía, no la actualizamos
    if (empty($password)) {
        $password = $usuario['password']; // Mantener la contraseña anterior
    }

    // Llamar al método de actualización
    $result = Usuario::update($id_usuario, $nombre, $email, $password, $rol);

    if ($result) {
        header('Location: ../../../../views/dashboard.php'); // Redirigir después de la actualización
        exit();
    } else {
        echo "Error al actualizar el usuario.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <!-- Agrega aquí los links a tus hojas de estilo CSS -->
</head>
<body>
    <h2>Editar Usuario</h2>
    <form method="POST">
        <div>
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>
        </div>
        <div>
            <label for="password">Contraseña (deja en blanco si no quieres cambiarla):</label>
            <input type="password" id="password" name="password">
        </div>
        <div>
            <label for="rol">Rol:</label>
            <select id="rol" name="rol" required>
                <option value="admin" <?= $usuario['rol'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
                <option value="user" <?= $usuario['rol'] === 'user' ? 'selected' : '' ?>>Usuario</option>
            </select>
        </div>
        <button type="submit">Actualizar Usuario</button>
    </form>
</body>
</html>
