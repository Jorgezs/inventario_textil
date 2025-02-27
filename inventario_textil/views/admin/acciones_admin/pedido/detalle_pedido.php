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

    <!-- Bootstrap 5 y FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-black shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="fas fa-box"></i> Detalles del Pedido</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item me-2">
                        <a href="../../../../views/dashboard.php?id_usuario=<?= $pedido['id_usuario'] ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../../../../controllers/authController.php?logout=true" class="btn btn-danger">
                            <i class="fas fa-sign-out-alt"></i> Cerrar sesión
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <div class="container mt-5">
        <h1 class="text-center"><i class="fas fa-receipt"></i> Pedido #<?= $pedido['id_pedido'] ?></h1>

        <div class="card shadow-sm p-4 mb-4">
            <p><strong><i class="fas fa-calendar-alt"></i> Fecha:</strong> <?= $pedido['fecha_pedido'] ?></p>
            <p><strong><i class="fas fa-info-circle"></i> Estado:</strong> <span class="badge bg-primary"><?= ucfirst($pedido['estado']) ?></span></p>
            <p><strong><i class="fas fa-user"></i> Usuario:</strong> <?= $pedido['id_usuario'] ?></p>
        </div>

        <h3><i class="fas fa-box-open"></i> Productos en este Pedido</h3>

        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center">
                <thead class="table-dark">
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
                            <td><?= htmlspecialchars($producto_pedido['nombre']) ?></td>
                            <td><?= htmlspecialchars($producto_pedido['cantidad']) ?></td>
                            <td><?= number_format($producto_pedido['precio'], 2) ?> €</td>
                            <td><?= number_format($total_producto, 2) ?> €</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <h4 class="mt-4 text-end"><strong>Total del Pedido:</strong> <span class="text-success"><?= number_format($total_pedido, 2) ?> €</span></h4>

        <div class="text-center mt-4">
            <button class="btn btn-warning btn-lg" onclick="cambiarEstado(<?= $pedido['id_pedido'] ?>)">
                <i class="fas fa-edit"></i> Cambiar Estado
            </button>
        </div>
    </div>

    <!-- Script para cambiar estado con SweetAlert -->
    <script>
        function cambiarEstado(idPedido) {
            Swal.fire({
                title: 'Cambiar Estado del Pedido',
                input: 'select',
                inputOptions: {
                    pendiente: 'Pendiente',
                    procesando: 'Procesando',
                    enviado: 'Enviado',
                    entregado: 'Entregado',
                    cancelado: 'Cancelado'
                },
                inputPlaceholder: 'Selecciona un estado',
                showCancelButton: true,
                confirmButtonText: 'Actualizar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    popup: 'shadow-lg'
                }
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
                        Swal.fire({
                            title: 'Estado Actualizado',
                            text: data.success || data.error,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => location.reload());
                    })
                    .catch(error => console.error('Error:', error));
                }
            });
        }
    </script>

    <!-- Scripts de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
