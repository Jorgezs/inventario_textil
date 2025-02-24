<?php
require_once('../config/database.php');

class Movimiento {
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
