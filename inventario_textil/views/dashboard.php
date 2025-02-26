<?php
session_start();
require_once('../models/Producto.php');
require_once('../models/Usuario.php');
require_once('../models/Pedido.php'); // Incluir modelo de pedidos
require_once('../models/Movimiento.php');
include ('js/complementos.php');

cargarRecursos();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$productos = Producto::getAll();
$usuarios = Usuario::getAll();
$pedidos = Pedido::obtenerTodosLosPedidos(); // Obtener todos los pedidos
$movimientos = Movimiento::obtenerMovimientos();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <!-- Bootstrap 5 -->
  
    

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
                    <a href="../controllers/authController.php?logout=true" class="btn btn-danger"><i
                            class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h1 class="text-center">Bienvenido, <?= $_SESSION['user_name'] ?? 'Administrador' ?> <i
            class="fas fa-user-shield"></i></h1>

    <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab">
        <li class="nav-item">
            <button class="nav-link active" id="pills-productos-tab" data-bs-toggle="pill"
                data-bs-target="#pills-productos">Productos <i class="fas fa-box"></i></button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="pills-usuarios-tab" data-bs-toggle="pill"
                data-bs-target="#pills-usuarios">Usuarios <i class="fas fa-users"></i></button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="pills-pedidos-tab" data-bs-toggle="pill"
                data-bs-target="#pills-pedidos">Pedidos <i class="fas fa-shopping-cart"></i></button>
        </li>
        <!-- Pestaña de Movimientos -->
        <li class="nav-item">
            <button class="nav-link" id="pills-movimientos-tab" data-bs-toggle="pill"
                data-bs-target="#pills-movimientos">Movimientos <i class="fas fa-exchange-alt"></i></button>
        </li>
    </ul>
    <div class="tab-content">
    <!-- Cargar los contenidos dinámicamente según la pestaña seleccionada -->
    <div class="tab-pane fade" id="pills-usuarios">
        <?php include('admin/crud_admin/usuarios_crud.php'); ?>
    </div>

    <div class="tab-pane fade show active" id="pills-productos">
        <?php include('admin/crud_admin/productos_crud.php'); ?>
    </div>

    <div class="tab-pane fade" id="pills-pedidos">
        <?php include('admin/crud_admin/pedidos_crud.php'); ?>
    </div>

    <div class="tab-pane fade" id="pills-movimientos">
        <?php include('admin/crud_admin/movimientos_crud.php'); ?>
    </div>
</div>

<?php include('admin/acciones_admin/producto/create_producto.php'); ?>
<?php include('admin/acciones_admin/producto/editar_producto.php'); ?>




<script src="js/admin.js"></script>

</body>

</html>