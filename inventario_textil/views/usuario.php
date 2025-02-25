<?php
session_start();
require_once('../models/Producto.php');
require_once('../models/Usuario.php');
require_once('../models/Pedido.php'); // Incluir modelo de pedidos

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'usuario') {
    header('Location: login.php');
    exit();
}

$productos = Producto::getAll(); // Obtener productos
$pedidos = Pedido::obtenerPedidosPorUsuario($_SESSION['user_id']); // Obtener pedidos del usuario

// Manejar la acción de agregar al carrito
if (isset($_POST['add_to_cart'])) {
    $id_producto = $_POST['id_producto'];
    $cantidad = $_POST['cantidad'];

    // Verificar si ya existe el producto en el carrito
    if (isset($_SESSION['cart'][$id_producto])) {
        // Si ya existe, aumentamos la cantidad
        $_SESSION['cart'][$id_producto] += $cantidad;
    } else {
        // Si no existe, lo agregamos al carrito
        $_SESSION['cart'][$id_producto] = $cantidad;
    }

    header('Location: usuario.php');
    exit();
}

// Eliminar un producto del carrito
if (isset($_POST['remove_from_cart'])) {
    $id_producto = $_POST['id_producto'];

    // Eliminar el producto del carrito
    unset($_SESSION['cart'][$id_producto]);

    header('Location: usuario.php');
    exit();
}

// Finalizar el pedido
if (isset($_POST['finalize_order'])) {
    $productos_carrito = $_SESSION['cart'];
    if (!empty($productos_carrito)) {
        // Llamar al método para crear el pedido
        $id_usuario = $_SESSION['user_id'];
        $productos_para_pedido = [];

        // Obtener detalles de cada producto
        foreach ($productos_carrito as $id_producto => $cantidad) {
            $producto = Producto::getById($id_producto);
            if ($producto) {
                $productos_para_pedido[] = [
                    'id_producto' => $id_producto,
                    'cantidad' => $cantidad
                ];
            }
        }

        // Crear el pedido
        $pedido_id = Pedido::crearPedido($id_usuario, $productos_para_pedido);

        // Si el pedido fue creado con éxito, limpiar el carrito
        if ($pedido_id) {
            unset($_SESSION['cart']); // Vaciar el carrito
            header('Location: pedido_confirmado.php?id=' . $pedido_id);
            exit();
        } else {
            echo "Hubo un error al realizar el pedido.";
        }
    } else {
        echo "No has agregado productos al carrito.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .floating-card {
            position: fixed;
            top: 20%;
            right: 20px;
            width: 300px;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 15px;
            border-radius: 8px;
            background-color: #fff;
            display: none;
            max-height: 80%;
            overflow-y: auto;
        }
        .floating-card.show {
            display: block;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-black">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><i class="fas fa-user"></i> Panel de Usuario</a>
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

    <?php if (isset($_SESSION['mensaje'])): ?>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <?= $_SESSION['mensaje']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['mensaje']); // Limpiar el mensaje después de mostrarlo ?>
<?php endif; ?>

        <h1 class="text-center">Bienvenido, <?= $_SESSION['user_name'] ?> <i class="fas fa-smile"></i></h1>

        <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab">
            <li class="nav-item">
                <button class="nav-link active" id="pills-productos-tab" data-bs-toggle="pill" data-bs-target="#pills-productos">Hacer Pedido <i class="fas fa-shopping-cart"></i></button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="pills-pedidos-tab" data-bs-toggle="pill" data-bs-target="#pills-pedidos">Mis Pedidos <i class="fas fa-box"></i></button>
            </li>
        </ul>

        <div class="tab-content">
            <!-- Hacer Pedido -->
            <div class="tab-pane fade show active" id="pills-productos">
                <h2 class="text-center">Selecciona productos</h2>

                <div class="row">
                    <!-- Columna de productos -->
                    <div class="col-md-12">
                        <table class="table table-hover table-bordered mt-3">
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
                                            <form action="usuario.php" method="POST">
                                                <input type="hidden" name="id_producto" value="<?= $producto['id_producto'] ?>">
                                                <input type="hidden" name="add_to_cart" value="true">
                                                <input type="number" name="cantidad" min="1" max="<?= $producto['stock'] ?>" required class="form-control form-control-sm" style="width: 70px; display:inline-block;">
                                                <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-cart-plus"></i> Agregar</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
<!-- Mis Pedidos -->
<div class="tab-pane fade" id="pills-pedidos">
    <h2 class="text-center">Mis Pedidos</h2>
    
    <!-- Botón para eliminar todos los pedidos cancelados -->
    <a href="../controllers/pedidoController.php?action=eliminar_cancelados" class="btn btn-danger mb-3" onclick="return confirm('¿Estás seguro de que deseas eliminar todos los pedidos cancelados?')">
        Eliminar Todos los Cancelados
    </a>
    
    <table class="table table-hover table-bordered mt-3">
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
                        <a href="../views/view_pedidos.php?id=<?= $pedido['id_pedido'] ?>" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Ver Detalles</a>
                        <?php if ($pedido['estado'] === 'pendiente'): ?>
                            <a href="../controllers/pedidoController.php?action=cancelar&id_pedido=<?= $pedido['id_pedido'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas cancelar este pedido?')"><i class="fas fa-times"></i> Cancelar</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

    <!-- Card flotante del carrito -->
    <div id="cart-card" class="floating-card">
        <h5>Carrito de Compras</h5>
        <?php if (!empty($_SESSION['cart'])): ?>
            <table class="table table-sm">
                <tbody>
                    <?php
                    $total_carrito = 0;
                    foreach ($_SESSION['cart'] as $id_producto => $cantidad): 
                        $producto = Producto::getById($id_producto);
                        $total_producto = $producto['precio'] * $cantidad;
                        $total_carrito += $total_producto;
                    ?>
                        <tr>
                            <td><?= $producto['nombre'] ?> (x<?= $cantidad ?>)</td>
                            <td><?= number_format($total_producto, 2) ?> €</td>
                            <td>
                                <form action="usuario.php" method="POST">
                                    <input type="hidden" name="id_producto" value="<?= $id_producto ?>">
                                    <input type="hidden" name="remove_from_cart" value="true">
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <h6>Total: <?= number_format($total_carrito, 2) ?> €</h6>
            <form action="usuario.php" method="POST">
                <button type="submit" name="finalize_order" class="btn btn-primary btn-sm w-100">Finalizar Pedido</button>
            </form>
        <?php else: ?>
            <p>No hay productos en tu carrito.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mostrar la card flotante si el carrito tiene productos
        const cartCard = document.getElementById('cart-card');
        if (<?= !empty($_SESSION['cart']) ? 'true' : 'false' ?>) {
            cartCard.classList.add('show');
        }
    </script>
</body>
</html>
