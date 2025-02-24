<?php
require_once('../config/database.php');
require_once('../models/Pedido.php');
require_once('../models/DetallePedido.php');

class PedidoController {

    public function crearPedido($id_usuario) {
        global $pdo;

        // Crear el pedido
        $query = 'INSERT INTO pedidos (id_usuario, fecha_pedido, estado) VALUES (:id_usuario, NOW(), "pendiente")';
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();

        return $pdo->lastInsertId(); // Devolver el ID del pedido creado
    }
}

$pedidoController = new PedidoController();
