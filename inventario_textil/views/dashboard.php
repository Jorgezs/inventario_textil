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
    <div class="container mt-4">
        <h1 class="text-center">Bienvenido, Administrador</h1>
        <div class="d-flex justify-content-end mb-4">
            <a href="../controllers/authController.php?logout=true" class="btn btn-danger">Cerrar sesión</a>
        </div>

        <!-- Botones para agregar productos y usuarios -->
        <div class="mb-4">
            <h2 class="h4">Agregar Producto</h2>
            <button onclick="window.location.href='../views/create_producto.php';" class="btn btn-primary mb-2">Agregar Nuevo Producto</button>
        </div>

        <div class="mb-4">
            <h2 class="h4">Administrar Usuarios</h2>
            <button onclick="window.location.href='../views/create_usuario.php';" class="btn btn-success mb-2">Crear Nuevo Usuario</button>
        </div>

        <!-- Tabla de productos -->
        <h2 class="h4 mb-3">Lista de Productos</h2>
        <table class="table table-striped table-bordered">
            <thead>
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

        <!-- Tabla de usuarios -->
        <h2 class="h4 mb-3 mt-5">Lista de Usuarios</h2>
        <table class="table table-striped table-bordered">
            <thead>
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

    <!-- Vincula el JS de Bootstrap -->
</body>
</html>
