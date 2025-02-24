<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Producto</title>
</head>
<body>
    <h1>Agregar Nuevo Producto</h1>
    <form action="../controllers/productoController.php?action=create" method="POST">
        <input type="text" name="nombre" placeholder="Nombre del Producto" required><br>
        <input type="text" name="descripcion" placeholder="Descripción" required><br>
        <input type="number" name="id_categoria" placeholder="ID Categoría" required><br>
        <input type="text" name="color" placeholder="Color" required><br>
        <input type="text" name="talla" placeholder="Talla" required><br>
        <input type="number" name="precio" placeholder="Precio" required><br>
        <input type="number" name="stock" placeholder="Stock" required><br>
        <button type="submit">Agregar Producto</button>
    </form>
</body>
</html>
