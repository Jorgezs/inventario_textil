<?php
session_start();
require_once '../models/Pedido.php';
require_once '../models/Usuario.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$id_usuario = $_GET['id_usuario'] ?? null;

if (!$id_usuario) {
    echo "Usuario no encontrado.";
    exit();
}

$usuario = Usuario::getById($id_usuario);
$pedidos = Pedido::obtenerPedidosPorUsuario($id_usuario);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedidos de <?= htmlspecialchars($usuario['nombre']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Pedidos de <?= htmlspecialchars($usuario['nombre']) ?></h2>
        <a href="../admin/dashboard.php" class="btn btn-secondary mb-3">Volver</a>
        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID Pedido</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $pedido): ?>
                    <tr>
                        <td><?= $pedido['id_pedido'] ?></td>
                        <td><?= $pedido['fecha_pedido'] ?></td>
                        <td><?= ucfirst($pedido['estado']) ?></td>
                        <td>
                            <a href="view_pedido.php?id=<?= $pedido['id_pedido'] ?>" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Ver</a>
                            <a href="../controllers/pedidoController.php?action=actualizar_estado&id_pedido=<?= $pedido['id_pedido'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Estado</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
