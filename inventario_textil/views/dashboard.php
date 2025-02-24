<?php 
session_start();
require_once('../models/Producto.php');
require_once('../models/Usuario.php'); // Incluir el modelo de Usuario

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$productos = Producto::getAll();
$usuarios = Usuario::getAll(); // Obtener todos los usuarios
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <!-- Vincula Bootstrap 5 -->
    <link rel="stylesheet" href="../public/styles.css">
    <script src="../public/script.js"></script>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Admin Panel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto"> <!-- Clase ms-auto para mover el botón a la derecha -->
                <li class="nav-item">
                    <a href="../controllers/authController.php?logout=true" class="btn btn-danger">Cerrar sesión</a>
                </li>
            </ul>
        </div>
    </div>
</nav>


<div class="container mt-4">
    <h1 class="text-center">Bienvenido, <?= $_SESSION['user_name'] ?? 'Administrador' ?></h1> <!-- Nombre del usuario aquí -->


        <!-- Pestañas para cambiar entre productos y usuarios -->
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Productos</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Usuarios</button>
            </li>
        </ul>

        <div class="tab-content" id="pills-tabContent">
            <!-- Productos -->
            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                <div class="mb-2 text-end">
                    <button onclick="window.location.href='../views/create_producto.php';" class="btn btn-primary mb-2">Agregar Nuevo Producto</button>
                </div>
                <table class="table table-striped table-bordered">
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
                                    <a href="../views/edit_producto.php?id=<?= $producto['id_producto'] ?>" class="btn btn-warning btn-sm">Editar</a> 
                                    <a href="../controllers/productoController.php?action=delete&id_producto=<?= $producto['id_producto'] ?>" 
                                       class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este producto?')">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Usuarios -->
            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
                <div class="mb-2 text-end">
                    <button onclick="window.location.href='../views/create_usuario.php';" class="btn btn-primary mb-2">Crear Nuevo Usuario</button>
                </div>
                <table class="table table-striped table-bordered">
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
                                <td><?= $usuario['nombre'] ?></td>
                                <td><?= $usuario['email'] ?></td>
                                <td><?= ucfirst($usuario['rol']) ?></td>
                                <td>
                                    <a href="../views/edit_usuario.php?id=<?= $usuario['id_usuario'] ?>" class="btn btn-warning btn-sm">Editar</a>
                                    <a href="../controllers/authController.php?action=delete&id_usuario=<?= $usuario['id_usuario'] ?>" 
                                       class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
