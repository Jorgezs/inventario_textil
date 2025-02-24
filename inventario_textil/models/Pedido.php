<?php
require_once('../config/database.php');

class Pedido {
    public static function create($user_id, $productos) {
        $db = Database::getConnection();
        $query = "INSERT INTO pedidos (user_id, estado) VALUES (?, 'pendiente')";
        $stmt = $db->prepare($query);
        $stmt->execute([$user_id]);

        // Obtener el ID del pedido recién creado
        $pedido_id = $db->lastInsertId();

        // Insertar los productos del pedido
        foreach ($productos as $producto) {
            $query = "INSERT INTO pedido_producto (pedido_id, producto_id, cantidad) VALUES (?, ?, ?)";
            $stmt = $db->prepare($query);
            $stmt->execute([$pedido_id, $producto['id'], $producto['cantidad']]);
        }

        return $pedido_id;
    }

    public static function getByUserId($user_id) {
        $db = Database::getConnection();
        // Cambiar 'user_id' por 'id_usuario'
        $query = "SELECT * FROM pedidos WHERE id_usuario = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public static function getById($pedido_id) {
        $db = Database::getConnection();
        $query = "SELECT * FROM pedidos WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$pedido_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getPedidoProductos($pedido_id) {
        $db = Database::getConnection();
        $query = "SELECT * FROM pedido_producto pp JOIN productos p ON pp.producto_id = p.id_producto WHERE pp.pedido_id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$pedido_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function changeStatus($pedido_id, $estado) {
        $db = Database::getConnection();
        $query = "UPDATE pedidos SET estado = ? WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$estado, $pedido_id]);
    }
}
?>
