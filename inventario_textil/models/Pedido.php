<?php
require_once __DIR__ . '/../config/database.php';

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


    public static function obtenerProductosPorPedido($id_pedido) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT dp.*, p.nombre, p.precio 
                               FROM detalle_pedido dp
                               INNER JOIN productos p ON dp.id_producto = p.id_producto
                               WHERE dp.id_pedido = :id_pedido");
        $stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function cancelarPedido($id_pedido) {
        global $pdo;
        try {
            $pdo->beginTransaction();
    
            // Obtener el estado del pedido y el id_usuario
            $stmt = $pdo->prepare("SELECT id_usuario, estado FROM pedidos WHERE id_pedido = :id_pedido");
            $stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
            $stmt->execute();
            $pedido = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$pedido) {
                throw new Exception("El pedido no existe.");
            }
    
            if ($pedido['estado'] != 'pendiente') {
                throw new Exception("Solo los pedidos pendientes pueden ser cancelados.");
            }
    
            $id_usuario = $pedido['id_usuario']; // Obtener el usuario que hizo el pedido
    
            // Cambiar estado a "cancelado"
            $stmtUpdate = $pdo->prepare("UPDATE pedidos SET estado = 'cancelado' WHERE id_pedido = :id_pedido");
            $stmtUpdate->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
            $stmtUpdate->execute();
    
            // Obtener los productos del pedido
            $stmtDetalles = $pdo->prepare("SELECT id_producto, cantidad FROM detalle_pedido WHERE id_pedido = :id_pedido");
            $stmtDetalles->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
            $stmtDetalles->execute();
            $detalles = $stmtDetalles->fetchAll(PDO::FETCH_ASSOC);
    
            foreach ($detalles as $detalle) {
                $id_producto = $detalle['id_producto'];
                $cantidad = $detalle['cantidad'];
    
                // Reponer stock
                $stmtStock = $pdo->prepare("UPDATE productos SET stock = stock + :cantidad WHERE id_producto = :id_producto");
                $stmtStock->execute([
                    ':cantidad' => $cantidad,
                    ':id_producto' => $id_producto
                ]);
    
                // Registrar movimiento de inventario (entrada)
                $stmtMovimiento = $pdo->prepare("INSERT INTO movimientos_inventario (id_producto, id_usuario, tipo_movimiento, cantidad, descripcion)
                                                 VALUES (:id_producto, :id_usuario, 'entrada', :cantidad, 'Cancelación de pedido')");
                $stmtMovimiento->execute([
                    ':id_producto' => $id_producto,
                    ':id_usuario' => $id_usuario, // Asegurar que se inserte el usuario correcto
                    ':cantidad' => $cantidad
                ]);
            }
    
            $pdo->commit();
            return true;
        } catch (Exception $e) {
            $pdo->rollBack();
            return ['error' => $e->getMessage()];
        }
    }
    
    public static function obtenerUsuariosConPedidos() {
        global $pdo;
        $stmt = $pdo->prepare("SELECT DISTINCT u.id_usuario, u.nombre 
                               FROM pedidos p 
                               INNER JOIN usuarios u ON p.id_usuario = u.id_usuario");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function actualizarEstadoPedido($id_pedido, $nuevo_estado) {
        global $pdo;
        try {
            // Verifica el valor de nuevo_estado
            error_log("Nuevo estado: " . $nuevo_estado); // O usa var_dump($nuevo_estado); en lugar de error_log()
    
            $stmt = $pdo->prepare("UPDATE pedidos SET estado = :estado WHERE id_pedido = :id_pedido");
            $stmt->bindParam(':estado', $nuevo_estado, PDO::PARAM_STR);
            $stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
            $stmt->execute();
            
            return ['success' => "El estado del pedido ha sido actualizado a '$nuevo_estado'"];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
    public static function eliminarDetallesPorPedido($id_pedido) {
        global $pdo;
        try {
            $stmt = $pdo->prepare("DELETE FROM detalle_pedido WHERE id_pedido = :id_pedido");
            $stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
            $stmt->execute();
            
            return ['success' => 'Detalles eliminados correctamente'];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
    
    public static function eliminarPedido($id_pedido) {
        global $pdo;
        try {
            $stmt = $pdo->prepare("DELETE FROM pedidos WHERE id_pedido = :id_pedido");
            $stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
            $stmt->execute();
            
            return ['success' => 'Pedido eliminado correctamente'];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
    

    public static function eliminarDetallesDePedidosCancelados($id_usuario) {
        global $pdo;
        try {
            // Eliminar los detalles de los pedidos cancelados
            $stmt = $pdo->prepare("DELETE FROM detalle_pedido WHERE id_pedido IN (SELECT id_pedido FROM pedidos WHERE estado = 'cancelado' AND id_usuario = :id_usuario)");
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();
    
            return ['success' => 'Detalles de pedidos cancelados eliminados correctamente'];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
    public static function eliminarPedidosCancelados($id_usuario) {
        global $pdo;
        try {
            // Eliminar los pedidos cancelados
            $stmt = $pdo->prepare("DELETE FROM pedidos WHERE estado = 'cancelado' AND id_usuario = :id_usuario");
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();
    
            return ['success' => 'Pedidos cancelados eliminados correctamente'];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
        
    
}
?>
