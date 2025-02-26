<?php
session_start();
require_once '../../../../models/Pedido.php';

// Verificar si el usuario es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Verificar si se ha pasado el ID del pedido
if (!isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit();
}

// Obtener detalles del pedido
$id_pedido = $_GET['id'];
$pedido = Pedido::obtenerPorId($id_pedido);

// Verificar si el pedido existe
if (!$pedido) {
    header('Location: dashboard.php');
    exit();
}

// Obtener los productos del pedido
$productos_pedido = Pedido::obtenerProductosPorPedido($id_pedido);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Pedido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-black">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><i class="fas fa-box"></i> Detalles del Pedido</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a href="../../../../views/dashboard.php?id_usuario=<?= $pedido['id_usuario'] ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver</a>
                    </li>
                    <li class="nav-item">
                        <a href="../../../../controllers/authController.php?logout=true" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="text-center">Detalles del Pedido #<?= $pedido['id_pedido'] ?></h1>
        <p><strong>Fecha:</strong> <?= $pedido['fecha_pedido'] ?></p>
        <p><strong>Estado:</strong> <?= ucfirst($pedido['estado']) ?></p>
        <p><strong>Usuario:</strong> <?= $pedido['id_usuario'] ?></p>

        <h3>Productos en este Pedido</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_pedido = 0;
                foreach ($productos_pedido as $producto_pedido):
                    $total_producto = $producto_pedido['precio'] * $producto_pedido['cantidad'];
                    $total_pedido += $total_producto;
                ?>
                    <tr>
                        <td><?= $producto_pedido['nombre'] ?></td>
                        <td><?= $producto_pedido['cantidad'] ?></td>
                        <td><?= number_format($producto_pedido['precio'], 2) ?> €</td>
                        <td><?= number_format($total_producto, 2) ?> €</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h4>Total del Pedido: <?= number_format($total_pedido, 2) ?> €</h4>

        <button class="btn btn-warning" onclick="cambiarEstado(<?= $pedido['id_pedido'] ?>)">
            <i class="fas fa-edit"></i> Cambiar Estado
        </button>
    </div>

    <script>
        function cambiarEstado(idPedido) {
            Swal.fire({
                title: 'Cambiar Estado',
                input: 'select',
                inputOptions: {
    pendiente: 'Pendiente',
    procesando: 'Procesando',  // Cambié 'en_proceso' por 'procesando'
    enviado: 'Enviado',
    entregado: 'Entregado',
    cancelado: 'Cancelado'
},
                inputPlaceholder: 'Selecciona un estado',
                showCancelButton: true,
                confirmButtonText: 'Actualizar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    let nuevoEstado = result.value;
                    fetch('../../../../controllers/pedidoController.php?action=actualizar_estado', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `id_pedido=${idPedido}&estado=${nuevoEstado}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.fire('Actualizado', data.success || data.error, 'success')
                            .then(() => location.reload());
                    })
                    .catch(error => console.error('Error:', error));
                }
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
