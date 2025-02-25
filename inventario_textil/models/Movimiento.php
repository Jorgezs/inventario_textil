<?php
require_once('../config/database.php');

class Movimiento {
    // Función para obtener los movimientos de inventario
    public static function obtenerMovimientos() {
        global $pdo;

        $query = "
            SELECT m.id_movimiento, p.nombre AS producto, u.nombre AS usuario, m.tipo_movimiento, m.cantidad, m.fecha_movimiento, m.descripcion
            FROM movimientos_inventario m
            JOIN productos p ON m.id_producto = p.id_producto
            JOIN usuarios u ON m.id_usuario = u.id_usuario
            ORDER BY m.fecha_movimiento DESC
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Función para registrar un movimiento (ya la tienes)
    public static function registrarMovimiento($id_producto, $id_usuario, $tipo_movimiento, $cantidad, $descripcion) {
        global $pdo;

        $query = 'INSERT INTO movimientos_inventario (id_producto, id_usuario, tipo_movimiento, cantidad, descripcion) VALUES (:id_producto, :id_usuario, :tipo_movimiento, :cantidad, :descripcion)';
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->bindParam(':tipo_movimiento', $tipo_movimiento, PDO::PARAM_STR);
        $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
        $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        $stmt->execute();
    }
}

?>
