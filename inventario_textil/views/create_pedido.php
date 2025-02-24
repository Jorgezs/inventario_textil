<?php
session_start();
require_once '../models/Producto.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$productos = Producto::getAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realizar Pedido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Realizar Pedido</h2>
    <form id="pedidoForm">
        <table class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Stock</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?= $producto['nombre'] ?></td>
                        <td><?= $producto['stock'] ?></td>
                        <td>
                            <input type="number" class="form-control cantidad" data-id="<?= $producto['id_producto'] ?>" data-stock="<?= $producto['stock'] ?>" min="1" max="<?= $producto['stock'] ?>" value="1">
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button type="submit" class="btn btn-primary">Hacer Pedido</button>
    </form>
</div>

<script>
document.getElementById('pedidoForm').addEventListener('submit', function(event) {
    event.preventDefault();
    let productos = [];
    document.querySelectorAll('.cantidad').forEach(input => {
        let id_producto = input.getAttribute('data-id');
        let cantidad = parseInt(input.value);
        let stock = parseInt(input.getAttribute('data-stock'));
        
        if (cantidad > 0 && cantidad <= stock) {
            productos.push({ id_producto, cantidad });
        }
    });

    if (productos.length === 0) {
        alert("Seleccione al menos un producto válido.");
        return;
    }

    fetch('../controllers/pedidoController.php', {
        method: 'POST',
        body: new URLSearchParams({ productos: JSON.stringify(productos) }),
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert("Error: " + data.error);
        } else {
            alert("Pedido realizado con éxito");
            location.reload();
        }
    });
});
</script>

</body>
</html>
