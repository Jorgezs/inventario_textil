<?php
session_start();
require_once '../../../../models/Pedido.php';

// Verificar si el usuario es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Verificar si se ha pasado el ID del usuario
if (!isset($_GET['id_usuario'])) {
    header('Location: admin.php');
    exit();
}

$id_usuario = $_GET['id_usuario'];
$pedidos = Pedido::obtenerPedidosPorUsuario($id_usuario);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos del Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-black">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><i class="fas fa-box"></i> Pedidos del Usuario</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a href="../../../../views/dashboard.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver al Panel</a>
                    </li>
                    <li class="nav-item">
                        <a href="../../../../controllers/authController.php?logout=true" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="text-center">Pedidos del Usuario #<?= htmlspecialchars($id_usuario) ?></h1>
        
        <table class="table table-bordered">
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
                        <td><?= htmlspecialchars($pedido['id_pedido']) ?></td>
                        <td><?= htmlspecialchars($pedido['fecha_pedido']) ?></td>
                        <td><?= htmlspecialchars(ucfirst($pedido['estado'])) ?></td>
                        <td>
                            <a href="detalle_pedido.php?id=<?= $pedido['id_pedido'] ?>" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Ver Detalles
                            </a>
                            <button class="btn btn-warning btn-sm" onclick="cambiarEstado(<?= $pedido['id_pedido'] ?>)">
                                <i class="fas fa-edit"></i> Cambiar Estado
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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
