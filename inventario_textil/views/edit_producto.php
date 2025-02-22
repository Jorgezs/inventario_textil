<?php
// views/edit_producto.php

echo "<h1>Editar Producto</h1>";
echo "<form method='POST' action='../controllers/productoController.php?action=update'>
    <input type='hidden' name='id_producto' value='" . $producto['id_producto'] . "'>
    Nombre: <input type='text' name='nombre' value='" . $producto['nombre'] . "' required><br>
    Descripción: <input type='text' name='descripcion' value='" . $producto['descripcion'] . "' required><br>
    Categoría: <input type='number' name='id_categoria' value='" . $producto['id_categoria'] . "' required><br>
    Color: <input type='text' name='color' value='" . $producto['color'] . "' required><br>
    Talla: <input type='text' name='talla' value='" . $producto['talla'] . "' required><br>
    Precio: <input type='number' name='precio' value='" . $producto['precio'] . "' required><br>
    Stock: <input type='number' name='stock' value='" . $producto['stock'] . "' required><br>
    <input type='submit' value='Actualizar Producto'>
</form>";
?>
