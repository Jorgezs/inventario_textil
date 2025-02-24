<?php
session_start();
require_once '../models/Pedido.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$id_pedido = $_GET['id'] ?? null;

if (!$id_pedido) {
    echo "Pedido no encontrado.";
    exit();
}

$pedido = Pedido::obtenerPorId($id_pedido);
$productos = Pedido::obtenerProductosPorPedido($id_pedido);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles del Pedido #<?= $id_pedido ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Detalles del Pedido #<?= $id_pedido ?></h2>
        <p><strong>Estado:</strong> <?= ucfirst($pedido['estado']) ?></p>
        
        <form action="../controllers/pedidoController.php" method="POST">
            <input type="hidden" name="id_pedido" value="<?= $id_pedido ?>">
            <label for="estado">Cambiar Estado:</label>
            <select name="estado" id="estado" class="form-select">
                <option value="pendiente" <?= $pedido['estado'] == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                <option value="procesando" <?= $pedido['estado'] == 'procesando' ? 'selected' : '' ?>>Procesando</option>
                <option value="enviado" <?= $pedido['estado'] == 'enviado' ? 'selected' : '' ?>>Enviado</option>
                <option value="entregado" <?= $pedido['estado'] == 'entregado' ? 'selected' : '' ?>>Entregado</option>
                <option value="cancelado" <?= $pedido['estado'] == 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
            </select>
            <button type="submit" name="actualizar_estado" class="btn btn-primary mt-2">Actualizar</button>
        </form>

        <h3 class="mt-4">Productos</h3>
        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?= htmlspecialchars($producto['nombre']) ?></td>
                        <td><?= $producto['cantidad'] ?></td>
                        <td>$<?= number_format($producto['precio_unitario'], 2) ?></td>
                        <td>$<?= number_format($producto['cantidad'] * $producto['precio_unitario'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <a href="../admin/dashboard.php" class="btn btn-secondary">Volver</a>
    </div>
</body>
</html>
