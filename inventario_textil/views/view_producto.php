<?php 
session_start();
require_once('../models/Producto.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$productId = $_GET['id'];
$producto = Producto::getById($productId);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Producto</title>
    <link rel="stylesheet" href="../public/styles.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <!-- Navbar contenido aquí -->
    </nav>

    <div class="container mt-4">
        <h1>Detalles del Producto</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?= $producto['nombre'] ?></h5>
                <p class="card-text"><?= $producto['descripcion'] ?></p>
                <p class="card-text"><strong>Precio: </strong>$<?= $producto['precio'] ?></p>
                <p class="card-text"><strong>Stock: </strong><?= $producto['stock'] ?></p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
