<?php 
session_start();
require_once('../models/Producto.php');
require_once('../models/Usuario.php');
require_once('../models/Pedido.php'); // Incluir modelo de pedidos

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$productos = Producto::getAll();
$usuarios = Usuario::getAll();
$pedidos = Pedido::obtenerTodosLosPedidos(); // Obtener todos los pedidos
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><i class="fas fa-cogs"></i> Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a href="../controllers/authController.php?logout=true" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="text-center">Bienvenido, <?= $_SESSION['user_name'] ?? 'Administrador' ?> <i class="fas fa-user-shield"></i></h1>
        
        <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab">
            <li class="nav-item">
                <button class="nav-link active" id="pills-productos-tab" data-bs-toggle="pill" data-bs-target="#pills-productos">Productos <i class="fas fa-box"></i></button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="pills-usuarios-tab" data-bs-toggle="pill" data-bs-target="#pills-usuarios">Usuarios <i class="fas fa-users"></i></button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="pills-pedidos-tab" data-bs-toggle="pill" data-bs-target="#pills-pedidos">Pedidos <i class="fas fa-shopping-cart"></i></button>
            </li>
        </ul>

        <div class="tab-content">
            <!-- Productos -->
            <div class="tab-pane fade show active" id="pills-productos">
                <div class="mb-2 text-end">
                    <a href="../views/create_producto.php" class="btn btn-success"><i class="fas fa-plus"></i> Agregar Producto</a>
                </div>
                <table class="table table-hover table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos as $producto): ?>
                            <tr>
                                <td><?= $producto['id_producto'] ?></td>
                                <td><?= $producto['nombre'] ?></td>
                                <td><?= $producto['descripcion'] ?></td>
                                <td><?= $producto['precio'] ?></td>
                                <td><?= $producto['stock'] ?></td>
                                <td>
                                    <a href="../views/view_producto.php?id=<?= $producto['id_producto'] ?>" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                                    <a href="../views/edit_producto.php?id=<?= $producto['id_producto'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                    <a href="../controllers/productoController.php?action=delete&id_producto=<?= $producto['id_producto'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar producto?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Usuarios -->
            <div class="tab-pane fade" id="pills-usuarios">
                <div class="mb-2 text-end">
                    <a href="../views/create_usuario.php" class="btn btn-success"><i class="fas fa-user-plus"></i> Crear Usuario</a>
                </div>
                <table class="table table-hover table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><?= $usuario['id_usuario'] ?></td>
                                <td><?= $usuario['nombre'] ?></td>
                                <td><?= $usuario['email'] ?></td>
                                <td><?= ucfirst($usuario['rol']) ?></td>
                                <td>
                                    <a href="../views/view_usuario.php?id=<?= $usuario['id_usuario'] ?>" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                                    <a href="../views/editar_usuario.php?id=<?= $usuario['id_usuario'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                    <a href="../controllers/usuarioController.php?action=delete&id_usuario=<?= $usuario['id_usuario'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar usuario?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>





            
        <!-- Pedidos -->
<div class="tab-pane fade" id="pills-pedidos">
    <table class="table table-hover table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID Usuario</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= $usuario['id_usuario'] ?></td>
                    <td><?= $usuario['nombre'] ?></td>
                    <td>
                        <button class="btn btn-info btn-sm" onclick="verPedidosUsuario(<?= $usuario['id_usuario'] ?>)">
                            <i class="fas fa-eye"></i> Ver Pedidos
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Tabla para mostrar los pedidos de un usuario -->
<div id="pedidosUsuario" class="mt-4" style="display: none;">
    <h3>Pedidos de <span id="usuarioNombre"></span></h3>
    <table class="table table-hover table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID Pedido</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="pedidosBody">
            <!-- Aquí se llenarán los pedidos dinámicamente -->
        </tbody>
    </table>
</div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
