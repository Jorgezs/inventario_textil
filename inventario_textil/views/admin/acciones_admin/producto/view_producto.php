<?php 
session_start();
require_once '../../../../models/Producto.php';

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
    <title>Detalles del Producto</title>

    <!-- Bootstrap 5 y FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../public/styles.css">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="fas fa-store"></i> Mi Tienda</a>
        </div>
    </nav>

    <!-- Contenedor Principal -->
    <div class="container mt-5">
        <h1 class="text-center mb-4"><i class="fas fa-box-open"></i> Detalles del Producto</h1>

        <div class="card shadow-lg">
            <div class="card-body">
                <h3 class="card-title text-primary"><i class="fas fa-tag"></i> <?= htmlspecialchars($producto['nombre']) ?></h3>
                <p class="card-text"><i class="fas fa-align-left"></i> <?= htmlspecialchars($producto['descripcion']) ?></p>
                <hr>
                <p class="card-text"><strong><i class="fas fa-euro-sign"></i> Precio: </strong> €<?= number_format($producto['precio'], 2) ?></p>
                <p class="card-text"><strong><i class="fas fa-boxes"></i> Stock: </strong> <?= htmlspecialchars($producto['stock']) ?></p>
            </div>
        </div>

        <!-- Botón de regreso -->
        <div class="text-center mt-4">
            <a href="../../../dashboard.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver a Productos</a>
        </div>
    </div>

    <!-- Scripts de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
