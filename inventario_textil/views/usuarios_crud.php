<?php
// Incluir el modelo de Usuario para realizar operaciones CRUD
require_once('../models/Usuario.php');

// Obtener todos los usuarios
$usuarios = Usuario::getAll();
?>

<h2>Gestión de Usuarios</h2>

<!-- Botón para agregar un nuevo usuario -->
<a href="crear_usuario.php">Crear Nuevo Usuario</a>

<!-- Tabla para mostrar los usuarios -->
<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Rol</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($usuarios as $usuario): ?>
            <tr>
                <td><?php echo $usuario['id_usuario']; ?></td>
                <td><?php echo $usuario['nombre']; ?></td>
                <td><?php echo $usuario['email']; ?></td>
                <td><?php echo $usuario['rol']; ?></td>
                <td>
                    <a href="editar_usuario.php?id=<?php echo $usuario['id_usuario']; ?>">Editar</a> | 
                    <a href="../controllers/usuarioController.php?action=delete&id=<?php echo $usuario['id_usuario']; ?>" onclick="return confirm('¿Estás seguro de eliminar este usuario?');">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
