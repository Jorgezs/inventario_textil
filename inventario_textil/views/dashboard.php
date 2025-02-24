<?php
session_start();
require_once('../models/Producto.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$productos = Producto::getAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
</head>
<body>
    <h1>Bienvenido, Administrador</h1>
    <a href="../controllers/authController.php?logout=true">Cerrar sesión</a>
    <h2>Agregar Producto</h2>
<button onclick="window.location.href='../views/create_producto.php';">Agregar Nuevo Producto</button>

<h2>Administrar Usuarios</h2>
<button onclick="window.location.href='../views/create_usuario.php';">Crear Nuevo Usuario</button>


    <h2>Productos</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($productos as $producto): ?>
            <tr>
                <td><?= $producto['id_producto'] ?></td>
                <td><?= $producto['nombre'] ?></td>
                <td><?= $producto['descripcion'] ?></td>
                <td><?= $producto['precio'] ?></td>
                <td><?= $producto['stock'] ?></td>
                <td>
                    <a href="../views/edit_producto.php?id=<?= $producto['id_producto'] ?>">Editar</a> | 
                    <a href="../controllers/productoController.php?action=delete&id_producto=<?= $producto['id_producto'] ?>" onclick="return confirm('¿Estás seguro de eliminar este producto?')">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

