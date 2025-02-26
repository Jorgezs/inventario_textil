<?php
$usuarios = Usuario::getAll();
?>
<!-- Usuarios -->
<div class="tab-pane fade show active" id="pills-usuarios">
    <div class="mb-2 text-end">
        <a href="admin/acciones_admin/usuario/create_usuario.php" class="btn btn-success"><i class="fas fa-user-plus"></i> Crear Usuario</a>
    </div>
    <table class="table table-hover table-bordered">
        <thead class="table-dark">
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
                    <td><?= $usuario['id_usuario'] ?></td>
                    <td><?= $usuario['nombre'] ?></td>
                    <td><?= $usuario['email'] ?></td>
                    <td><?= ucfirst($usuario['rol']) ?></td>
                    <td>
                        <a href="admin/acciones_admin/usuario/editar_usuario.php?id=<?= $usuario['id_usuario'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                        <a href="admin/acciones_admin/usuario/view_usuario.php?id=<?= $usuario['id_usuario'] ?>" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                        <a href="../controllers/usuarioController.php?action=delete&id_usuario=<?= $usuario['id_usuario'] ?>" 
   class="btn btn-danger btn-sm" 
   onclick="return confirm('¿Eliminar usuario?')">
   <i class="fas fa-trash"></i>
</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
