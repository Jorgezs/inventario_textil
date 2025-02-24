<?php
session_start();
require_once('../models/Producto.php');
require_once('../models/Pedido.php');
require_once('../models/DetallePedido.php'); // Modelo para detalles de pedido

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'usuario') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productos = $_POST['productos']; // Array de productos seleccionados
    $id_usuario = $_SESSION['user_id'];

    // Crear el pedido
    $pedido = Pedido::crearPedido($id_usuario);

    foreach ($productos as $id_producto => $cantidad) {
        // Obtener el precio del producto
        $producto = Producto::getById($id_producto);
        $precio_unitario = $producto['precio'];

        // Insertar los detalles del pedido
        DetallePedido::agregarDetalle($pedido['id_pedido'], $id_producto, $cantidad, $precio_unitario);
    }

    header('Location: usuario.php');
    exit();
}

$productos = Producto::getAll(); // Obtener todos los productos
?>

<!-- Modal -->
<div class="modal fade" id="crearPedidoModal" tabindex="-1" aria-labelledby="crearPedidoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="crearPedidoModalLabel">Crear Pedido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="crear_pedido.php" method="POST">
                    <div class="mb-3">
                        <label for="productos" class="form-label">Seleccionar Productos</label>
                        <?php foreach ($productos as $producto): ?>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="productos[<?= $producto['id_producto'] ?>]" id="producto_<?= $producto['id_producto'] ?>" value="1">
                                <label class="form-check-label" for="producto_<?= $producto['id_producto'] ?>">
                                    <?= $producto['nombre'] ?> - <?= $producto['precio'] ?>€ (Stock: <?= $producto['stock'] ?>)
                                </label>
                                <input type="number" name="cantidad[<?= $producto['id_producto'] ?>]" class="form-control mt-2" placeholder="Cantidad" required>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="submit" class="btn btn-primary">Crear Pedido</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Botón para abrir el modal -->
<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#crearPedidoModal">
    Crear Pedido
</button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
