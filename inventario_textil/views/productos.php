<?php
// views/productos.php

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

echo "<h1>Productos</h1>";

// Mostrar los productos
foreach ($productos as $producto) {
    echo "<p>" . $producto['nombre'] . " - " . $producto['precio'] . " <a href='../controllers/productoController.php?action=editForm&id=" . $producto['id_producto'] . "'>Editar</a> | 
    <a href='../controllers/productoController.php?action=delete&id=" . $producto['id_producto'] . "'>Eliminar</a></p>";
}

// Enlace para crear un nuevo producto
echo "<a href='../controllers/productoController.php?action=createForm'>Agregar Producto</a>";

?>
