<?php
$usuarios = Usuario::getAll();
?>
<!-- Usuarios -->
<div class="tab-pane fade show active" id="pills-usuarios">
    <div class="mb-3 text-end">
        <a href="admin/acciones_admin/usuario/create_usuario.php" class="btn btn-success">
            <i class="fas fa-user-plus"></i> Crear Usuario
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered table-striped">
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
                        <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                        <td><?= htmlspecialchars($usuario['email']) ?></td>
                        <td><?= ucfirst($usuario['rol']) ?></td>
                        <td>
                            <a href="admin/acciones_admin/usuario/editar_usuario.php?id=<?= $usuario['id_usuario'] ?>" 
                               class="btn btn-warning btn-sm" 
                               data-bs-toggle="tooltip" data-bs-placement="top" title="Editar Usuario">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="admin/acciones_admin/usuario/view_usuario.php?id=<?= $usuario['id_usuario'] ?>" 
                               class="btn btn-info btn-sm" 
                               data-bs-toggle="tooltip" data-bs-placement="top" title="Ver Usuario">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="../controllers/usuarioController.php?action=delete&id_usuario=<?= $usuario['id_usuario'] ?>" 
                               class="btn btn-danger btn-sm" 
                               onclick="return confirm('¿Eliminar usuario?')"
                               data-bs-toggle="tooltip" data-bs-placement="top" title="Eliminar Usuario">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Script de Bootstrap para Tooltip -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        var tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    });
</script>
