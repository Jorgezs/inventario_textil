<?php
session_start();
require_once('../models/Producto.php');
require_once('../models/Usuario.php'); // Incluir el modelo de Usuario

// Verificar si el usuario está autenticado y es un usuario (no admin)
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'usuario') {
    header('Location: login.php');
    exit();
}

$productos = Producto::getAll(); // Obtener todos los productos
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Usuario</title>
    <link rel="stylesheet" href="../public/styles.css">
    <script src="../public/script.js"></script>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Panel de Usuario</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a href="../controllers/authController.php?logout=true" class="btn btn-danger">Cerrar sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="text-center">Bienvenido, <?= $_SESSION['user_name'] ?></h1>

        <div class="mb-2 text-end">
            <button onclick="window.location.href='../views/productos.php';" class="btn btn-primary mb-2">Ver Productos</button>
        </div>

        <!-- Mostrar productos -->
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Stock</th>
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
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
