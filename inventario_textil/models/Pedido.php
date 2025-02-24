<?php
require_once '../config/database.php';

class Pedido {
    public static function crearPedido($id_usuario, $productos) {
        global $pdo;
        try {
            $pdo->beginTransaction();

            // Verificar si el carrito tiene productos válidos
            if (empty($productos)) {
                throw new Exception("No hay productos en el carrito.");
            }

            // Crear el pedido con estado 'pendiente'
            $stmt = $pdo->prepare("INSERT INTO pedidos (id_usuario, estado) VALUES (:id_usuario, 'pendiente')");
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();
            $id_pedido = $pdo->lastInsertId();

            $productos_para_pedido = [];

            foreach ($productos as $producto) {
                $id_producto = $producto['id_producto'];
                $cantidad = $producto['cantidad'];

                // Verificar stock
                $stmtStock = $pdo->prepare("SELECT stock, precio FROM productos WHERE id_producto = :id_producto");
                $stmtStock->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
                $stmtStock->execute();
                $productoData = $stmtStock->fetch(PDO::FETCH_ASSOC);

                if (!$productoData || $productoData['stock'] < $cantidad) {
                    throw new Exception("Stock insuficiente para el producto con ID $id_producto");
                }

                // Agregar detalle del pedido
                $stmtDetalle = $pdo->prepare("INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, precio_unitario) 
                                              VALUES (:id_pedido, :id_producto, :cantidad, :precio_unitario)");
                $stmtDetalle->execute([
                    ':id_pedido' => $id_pedido,
                    ':id_producto' => $id_producto,
                    ':cantidad' => $cantidad,
                    ':precio_unitario' => $productoData['precio']
                ]);

                // Descontar stock
                $stmtUpdateStock = $pdo->prepare("UPDATE productos SET stock = stock - :cantidad WHERE id_producto = :id_producto");
                $stmtUpdateStock->execute([
                    ':cantidad' => $cantidad,
                    ':id_producto' => $id_producto
                ]);

                // Registrar movimiento de salida en inventario
                $stmtMovimiento = $pdo->prepare("INSERT INTO movimientos_inventario (id_producto, id_usuario, tipo_movimiento, cantidad, descripcion)
                                                 VALUES (:id_producto, :id_usuario, 'salida', :cantidad, 'Pedido realizado')");
                $stmtMovimiento->execute([
                    ':id_producto' => $id_producto,
                    ':id_usuario' => $id_usuario,
                    ':cantidad' => $cantidad
                ]);

                // Guardar los productos válidos para el pedido
                $productos_para_pedido[] = [
                    'id_producto' => $id_producto,
                    'cantidad' => $cantidad
                ];
            }

            // Si no se agregaron productos válidos, lanzar excepción
            if (empty($productos_para_pedido)) {
                throw new Exception("No se pudieron agregar productos al pedido.");
            }

            $pdo->commit();
            return $id_pedido;

        } catch (Exception $e) {
            $pdo->rollBack();
            return ['error' => $e->getMessage()];
        }
    }

    public static function obtenerTodosLosPedidos() {
        global $pdo;
        $stmt = $pdo->prepare("SELECT pedidos.*, usuarios.nombre AS nombre_usuario 
                               FROM pedidos 
                               INNER JOIN usuarios ON pedidos.id_usuario = usuarios.id_usuario");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para obtener todos los pedidos de un usuario
    public static function obtenerPedidosPorUsuario($id_usuario) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT pedidos.*, usuarios.nombre AS nombre_usuario 
                               FROM pedidos 
                               INNER JOIN usuarios ON pedidos.id_usuario = usuarios.id_usuario
                               WHERE pedidos.id_usuario = :id_usuario");
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtenerPorId($id_pedido) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM pedidos WHERE id_pedido = :id_pedido");
        $stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
