<?php
// views/create_producto.php

echo "<h1>Agregar Producto</h1>";
echo "<form method='POST' action='../controllers/productoController.php?action=create'>
    Nombre: <input type='text' name='nombre' required><br>
    Descripción: <input type='text' name='descripcion' required><br>
    Categoría: <input type='number' name='id_categoria' required><br>
    Color: <input type='text' name='color' required><br>
    Talla: <input type='text' name='talla' required><br>
    Precio: <input type='number' name='precio' required><br>
    Stock: <input type='number' name='stock' required><br>
    <input type='submit' value='Crear Producto'>
</form>";
?>
