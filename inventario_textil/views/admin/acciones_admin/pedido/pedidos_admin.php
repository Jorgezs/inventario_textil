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

    <!-- Bootstrap 5 y FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-black shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="fas fa-box"></i> Pedidos del Usuario</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item me-2">
                        <a href="../../../../views/dashboard.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver al Panel</a>
                    </li>
                    <li class="nav-item">
                        <a href="../../../../controllers/authController.php?logout=true" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <div class="container mt-5">
        <h1 class="text-center mb-4"><i class="fas fa-user"></i> Pedidos del Usuario #<?= htmlspecialchars($id_usuario) ?></h1>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark text-center">
                    <tr>
                        <th><i class="fas fa-hashtag"></i> ID Pedido</th>
                        <th><i class="fas fa-calendar-alt"></i> Fecha</th>
                        <th><i class="fas fa-info-circle"></i> Estado</th>
                        <th><i class="fas fa-cogs"></i> Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedidos as $pedido): ?>
                        <tr>
                            <td class="text-center"><?= htmlspecialchars($pedido['id_pedido']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($pedido['fecha_pedido']) ?></td>
                            <td class="text-center">
                                <span class="badge bg-primary"><?= htmlspecialchars(ucfirst($pedido['estado'])) ?></span>
                            </td>
                            <td class="text-center">
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
