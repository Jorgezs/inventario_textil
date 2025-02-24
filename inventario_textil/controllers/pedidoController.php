<?php
require_once '../models/Pedido.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../views/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['productos'])) {
    $id_usuario = $_SESSION['user_id'];
    $productos = json_decode($_POST['productos'], true);

    $resultado = Pedido::crearPedido($id_usuario, $productos);

    if (isset($resultado['error'])) {
        echo json_encode(['error' => $resultado['error']]);
    } else {
        echo json_encode(['success' => 'Pedido realizado con éxito', 'id_pedido' => $resultado]);
    }
}
?>
