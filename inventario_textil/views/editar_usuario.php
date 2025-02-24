<?php
require_once('../models/Usuario.php');

// Verificar si el parámetro ID existe
if (isset($_GET['id'])) {
    $usuario = Usuario::getById($_GET['id']);
}
?>

<h2>Editar Usuario</h2>

<form action="../controllers/usuarioController.php" method="POST">
    <input type="hidden" name="id_usuario" value="<?php echo $usuario['id_usuario']; ?>">
    <input type="text" name="nombre" value="<?php echo $usuario['nombre']; ?>" required><br>
    <input type="email" name="email" value="<?php echo $usuario['email']; ?>" required><br>
    <select name="rol" required>
        <option value="admin" <?php echo ($usuario['rol'] == 'admin') ? 'selected' : ''; ?>>Administrador</option>
        <option value="usuario" <?php echo ($usuario['rol'] == 'usuario') ? 'selected' : ''; ?>>Usuario</option>
    </select><br>
    <button type="submit" name="action" value="update">Actualizar Usuario</button>
</form>
