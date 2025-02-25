<?php
require_once '../models/Pedido.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../views/login.php');
    exit();
}

// Acción para cancelar un pedido
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] === 'cancelar' && isset($_GET['id_pedido'])) {
    $id_usuario = $_SESSION['user_id'];
    $id_pedido = $_GET['id_pedido'];

    // Verificar si el pedido pertenece al usuario
    $pedido = Pedido::obtenerPorId($id_pedido);
    if (!$pedido || $pedido['id_usuario'] != $id_usuario) {
        $_SESSION['mensaje'] = "❌ No tienes permiso para cancelar este pedido.";
        header('Location: ../views/usuario.php');
        exit();
    }

    // Cancelar el pedido
    $resultado = Pedido::cancelarPedido($id_pedido);

    $_SESSION['mensaje'] = isset($resultado['error']) 
        ? "❌ Error al cancelar el pedido: " . $resultado['error']
        : "✅ El pedido #$id_pedido ha sido cancelado con éxito.";

    header('Location: ../views/usuario.php');
    exit();
}

// Acción para eliminar un pedido
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'eliminar' && isset($_GET['id_pedido'])) {
    $id_usuario = $_SESSION['user_id'];
    $id_pedido = $_GET['id_pedido'];

    // Verificar si el pedido pertenece al usuario
    $pedido = Pedido::obtenerPorId($id_pedido);
    if (!$pedido || $pedido['id_usuario'] != $id_usuario) {
        $_SESSION['mensaje'] = "❌ No tienes permiso para eliminar este pedido.";
        header('Location: ../views/usuario.php');
        exit();
    }

    // Verificar que el pedido esté cancelado
    if ($pedido['estado'] !== 'cancelado') {
        $_SESSION['mensaje'] = "❌ Este pedido no puede ser eliminado porque no está cancelado.";
        header('Location: ../views/usuario.php');
        exit();
    }

    // Eliminar el pedido
    $resultado = Pedido::eliminarPedido($id_pedido);

    $_SESSION['mensaje'] = isset($resultado['error']) 
        ? "❌ Error al eliminar el pedido: " . $resultado['error']
        : "✅ El pedido #$id_pedido ha sido eliminado con éxito.";

    header('Location: ../views/usuario.php');
    exit();
}

// Acción para eliminar todos los pedidos cancelados
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] === 'eliminar_cancelados') {
    $id_usuario = $_SESSION['user_id'];

    // Eliminar los detalles de los pedidos cancelados
    $resultadoDetalles = Pedido::eliminarDetallesDePedidosCancelados($id_usuario);

    if ($resultadoDetalles['error']) {
        $_SESSION['mensaje'] = "❌ Error al eliminar detalles de pedidos cancelados: " . $resultadoDetalles['error'];
        header('Location: ../views/usuario.php');
        exit();
    }

    // Eliminar los pedidos cancelados
    $resultadoPedidos = Pedido::eliminarPedidosCancelados($id_usuario);

    $_SESSION['mensaje'] = isset($resultadoPedidos['error']) 
        ? "❌ Error al eliminar pedidos cancelados: " . $resultadoPedidos['error']
        : "✅ Todos los pedidos cancelados han sido eliminados con éxito.";

    header('Location: ../views/usuario.php');
    exit();
}



// Verificar si se ha enviado una acción por GET
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'obtener_usuarios':
            echo json_encode(Pedido::obtenerUsuariosConPedidos());
            exit();

        case 'obtener_pedidos':
            if (isset($_GET['id_usuario'])) {
                $id_usuario = $_GET['id_usuario'];
                echo json_encode(Pedido::obtenerPedidosPorUsuario($id_usuario));
            }
            exit();
    }
}

// Acción para actualizar el estado de un pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'actualizar_estado') {
    if (isset($_POST['id_pedido'], $_POST['estado'])) {
        $id_pedido = $_POST['id_pedido'];
        $nuevo_estado = $_POST['estado'];

        $resultado = Pedido::actualizarEstadoPedido($id_pedido, $nuevo_estado);

        echo json_encode(['success' => $resultado ? 'Estado actualizado correctamente' : 'Error al actualizar el estado']);
    } else {
        echo json_encode(['error' => 'Datos incompletos']);
    }
    exit();
}

// Si no se encuentra una acción válida, redirigir al panel de administración
header('Location: ../views/dashboard.php');
exit();
?>
