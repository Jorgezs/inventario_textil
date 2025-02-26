<?php
// models/Producto.php

require_once __DIR__ . '/../config/database.php';

class Producto {
    // Obtener todos los productos
    public static function getAll() {
        global $pdo;
        $query = "SELECT * FROM productos";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un producto por su ID
    public static function getById($id_producto) {
        global $pdo;
        $query = "SELECT * FROM productos WHERE id_producto = :id_producto";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear un nuevo producto
    public static function create($nombre, $descripcion, $id_categoria, $color, $talla, $precio, $stock) {
        global $pdo;
        $query = "INSERT INTO productos (nombre, descripcion, id_categoria, color, talla, precio, stock) 
                  VALUES (:nombre, :descripcion, :id_categoria, :color, :talla, :precio, :stock)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        $stmt->bindParam(':id_categoria', $id_categoria, PDO::PARAM_INT);
        $stmt->bindParam(':color', $color, PDO::PARAM_STR);
        $stmt->bindParam(':talla', $talla, PDO::PARAM_STR);
        $stmt->bindParam(':precio', $precio, PDO::PARAM_STR);
        $stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
        $stmt->execute();
    }

    // Actualizar un producto
    public static function update($id_producto, $nombre, $descripcion, $id_categoria, $color, $talla, $precio, $stock) {
        global $pdo;
        $query = "UPDATE productos SET nombre = :nombre, descripcion = :descripcion, id_categoria = :id_categoria, 
                  color = :color, talla = :talla, precio = :precio, stock = :stock WHERE id_producto = :id_producto";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        $stmt->bindParam(':id_categoria', $id_categoria, PDO::PARAM_INT);
        $stmt->bindParam(':color', $color, PDO::PARAM_STR);
        $stmt->bindParam(':talla', $talla, PDO::PARAM_STR);
        $stmt->bindParam(':precio', $precio, PDO::PARAM_STR);
        $stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
        $stmt->execute();
    }

    // Eliminar un producto
    public static function delete($id_producto) {
        global $pdo;
        $query = "DELETE FROM productos WHERE id_producto = :id_producto";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
        $stmt->execute();
    }
}
?>
