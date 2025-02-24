<?php
session_start();
require_once('../models/Producto.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$producto = Producto::getById($_GET['id']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
</head>
<body>
    <h1>Editar Producto</h1>
    <form action="../controllers/productoController.php?action=edit" method="POST">
        <input type="hidden" name="id_producto" value="<?= $producto['id_producto'] ?>">
        <input type="text" name="nombre" value="<?= $producto['nombre'] ?>" required>
        <input type="text" name="descripcion" value="<?= $producto['descripcion'] ?>" required>
        <input type="number" name="id_categoria" value="<?= $producto['id_categoria'] ?>" required>
        <input type="text" name="color" value="<?= $producto['color'] ?>" required>
        <input type="text" name="talla" value="<?= $producto['talla'] ?>" required>
        <input type="number" name="precio" value="<?= $producto['precio'] ?>" required>
        <input type="number" name="stock" value="<?= $producto['stock'] ?>" required>
        <button type="submit">Guardar Cambios</button>
    </form>
    <a href="../views/dashboard.php">Volver</a>
</body>
</html>
