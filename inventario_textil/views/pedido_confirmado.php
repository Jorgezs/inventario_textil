<?php
session_start();
require_once('../models/Pedido.php');
require_once('../models/Producto.php');

if (!isset($_GET['id'])) {
    echo "Pedido no encontrado.";
    exit();
}

$id_pedido = $_GET['id'];
$pedido = Pedido::obtenerPorId($id_pedido);

if (!$pedido) {
    echo "Pedido no encontrado.";
    exit();
}

$stmtDetalle = $pdo->prepare("SELECT dp.*, p.nombre, p.precio 
                              FROM detalle_pedido dp 
                              JOIN productos p ON dp.id_producto = p.id_producto
                              WHERE dp.id_pedido = :id_pedido");
$stmtDetalle->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
$stmtDetalle->execute();
$productos_pedido = $stmtDetalle->fetchAll(PDO::FETCH_ASSOC);

$total_pedido = 0;
foreach ($productos_pedido as $producto) {
    $total_pedido += $producto['cantidad'] * $producto['precio'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Pedido</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<div class="container mt-5">
    <div class="card shadow-lg border-light">
        <div class="card-header bg-gradient text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0"><i class="bi bi-basket3-fill"></i> Pedido Confirmado</h3>
            <span class="badge bg-success p-2"><i class="bi bi-check-circle"></i> Confirmado</span>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <p><strong>Tu pedido ha sido confirmado con el ID:</strong> <?= $id_pedido ?></p>
                <p><strong>Estado:</strong> <?= ucfirst($pedido['estado']) ?></p>
            </div>

            <h4 class="mt-4 mb-3">Detalles del Pedido:</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead class="table-primary">
                        <tr>
                            <th><i class="bi bi-box"></i> Producto</th>
                            <th><i class="bi bi-clipboard-check"></i> Cantidad</th>
                            <th><i class="bi bi-currency-euro"></i> Precio Unitario</th>
                            <th><i class="bi bi-currency-euro"></i> Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos_pedido as $producto): ?>
                            <tr>
                                <td><?= $producto['nombre'] ?></td>
                                <td><?= $producto['cantidad'] ?></td>
                                <td><?= number_format($producto['precio'], 2) ?> €</td>
                                <td><?= number_format($producto['cantidad'] * $producto['precio'], 2) ?> €</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <h5><strong>Total del Pedido:</strong></h5>
                <h4 class="text-success"><strong><?= number_format($total_pedido, 2) ?> €</strong></h4>
            </div>

            <!-- Botón para regresar a la página principal -->
            <div class="mt-4">
                <a href="usuario.php" class="btn btn-primary"><i class="bi bi-house-door"></i> Confirmar</a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0Z6ppvbs0V1zM8wZfZmAiLbdhFzdaRgo4lOeqMZ+z5N2pY6X" crossorigin="anonymous"></script>

</body>
</html>
