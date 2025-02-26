<?php
// pedidos.php
$pedidos = Pedido::obtenerTodosLosPedidos();
?>
<!-- Pedidos -->
<div class="tab-pane fade show active" id="pills-pedidos">

    <table class="table table-hover table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID Usuario</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= $usuario['id_usuario'] ?></td>
                    <td><?= $usuario['nombre'] ?></td>
                    <td>
                        <a href="admin/acciones_admin/pedido/pedidos_admin.php?id_usuario=<?= $usuario['id_usuario'] ?>" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> Ver Pedidos
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Tabla para mostrar los pedidos de un usuario -->
<div id="pedidosUsuario" class="mt-4" style="display: none;">
    <h3>Pedidos de <span id="usuarioNombre"></span></h3>
    <table class="table table-hover table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID Pedido</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="pedidosBody">
            <!-- Aquí se llenarán los pedidos dinámicamente -->
        </tbody>
    </table>
</div>
