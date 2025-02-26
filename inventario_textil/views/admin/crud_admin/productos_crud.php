<?php
// productos_crud.php

// Obtener productos
$productos = Producto::getAll();
?>
<div class="tab-pane fade show active" id="pills-productos">
    <div class="mb-2 text-end">
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalAgregar">
            <i class="fas fa-plus"></i> Agregar Producto
        </button>
    </div>
    <table id="productosTable" class="table table-hover table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $producto): ?>
                <tr>
                    <td><?= $producto['id_producto'] ?></td>
                    <td><?= $producto['nombre'] ?></td>
                    <td><?= $producto['descripcion'] ?></td>
                    <td><?= $producto['precio'] ?></td>
                    <td><?= $producto['stock'] ?></td>
                    <td>
                    <a href="admin/acciones_admin/producto/view_producto.php?id=<?= $producto['id_producto'] ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="editar_producto.php?id=<?= $producto['id_producto'] ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="eliminar_producto.php?id=<?= $producto['id_producto'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar producto?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
