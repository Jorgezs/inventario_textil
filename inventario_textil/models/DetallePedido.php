<?php
require_once('../config/database.php');

class DetallePedido {
    public static function agregarDetalle($id_pedido, $id_producto, $cantidad, $precio_unitario) {
        global $pdo;

        $query = 'INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, precio_unitario) VALUES (:id_pedido, :id_producto, :cantidad, :precio_unitario)';
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
        $stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
        $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
        $stmt->bindParam(':precio_unitario', $precio_unitario, PDO::PARAM_STR);
        $stmt->execute();
    }
}
?>
