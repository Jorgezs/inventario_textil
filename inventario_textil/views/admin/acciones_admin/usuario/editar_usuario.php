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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-dark text-white text-center">
                <h3><i class="fas fa-user-edit"></i> Editar Usuario</h3>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="nombre" class="form-label"><i class="fas fa-user"></i> Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label"><i class="fas fa-envelope"></i> Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label"><i class="fas fa-lock"></i> Contraseña (opcional)</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <div class="mb-3">
                        <label for="rol" class="form-label"><i class="fas fa-user-tag"></i> Rol</label>
                        <select class="form-select" id="rol" name="rol" required>
                            <option value="admin" <?= $usuario['rol'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
                            <option value="user" <?= $usuario['rol'] === 'user' ? 'selected' : '' ?>>Usuario</option>
                        </select>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Actualizar</button>
                        <a href="../../../../views/dashboard.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
