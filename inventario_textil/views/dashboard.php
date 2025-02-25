<?php
session_start();
require_once('../models/Producto.php');
require_once('../models/Usuario.php');
require_once('../models/Pedido.php'); // Incluir modelo de pedidos
require_once('../models/Movimiento.php');


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
    <link rel="stylesheet" href="../public/styles.css">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
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

            <!-- Contenido de Movimientos -->
            <div class="tab-pane fade" id="pills-movimientos">
                <h3>Movimientos de Inventario</h3>
                <table class="table table-hover table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>ID Movimiento</th>
                            <th>Producto</th>
                            <th>Usuario</th>
                            <th>Tipo</th>
                            <th>Cantidad</th>
                            <th>Fecha</th>
                            <th>Descripción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($movimientos as $movimiento): ?>
                            <tr>
                                <td><?= $movimiento['id_movimiento'] ?></td>
                                <td><?= $movimiento['producto'] ?></td>
                                <td><?= $movimiento['usuario'] ?></td>
                                <td><?= ucfirst($movimiento['tipo_movimiento']) ?></td>
                                <td><?= $movimiento['cantidad'] ?></td>
                                <td><?= $movimiento['fecha_movimiento'] ?></td>
                                <td><?= $movimiento['descripcion'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Productos -->
            <div class="tab-pane fade show active" id="pills-productos">
                <div class="mb-2 text-end">
                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                        data-bs-target="#modalAgregar">
                        <i class="fas fa-plus"></i> Agregar Producto
                    </button>
                </div>
                <table id="productosTable" class="table table-hover table-bordered">
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
                    <tbody></tbody>
                </table>
            </div>

            <!-- Usuarios -->
            <div class="tab-pane fade" id="pills-usuarios">
                <div class="mb-2 text-end">
                    <a href="../views/create_usuario.php" class="btn btn-success"><i class="fas fa-user-plus"></i> Crear
                        Usuario</a>
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
                                    <a href="../views/view_usuario.php?id=<?= $usuario['id_usuario'] ?>"
                                        class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                                    <a href="../views/editar_usuario.php?id=<?= $usuario['id_usuario'] ?>"
                                        class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                    <a href="../controllers/usuarioController.php?action=delete&id_usuario=<?= $usuario['id_usuario'] ?>"
                                        class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar usuario?')"><i
                                            class="fas fa-trash"></i></a>
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
                                    <a href="../views/pedidos_admin.php?id_usuario=<?= $usuario['id_usuario'] ?>"
                                        class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Ver Pedidos
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Modal de agregar producto -->
    <div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="modalAgregarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAgregarLabel"><i class="fas fa-box"></i> Agregar Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregar">
                        <!-- Formulario de producto -->
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery y DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            $('#productosTable').DataTable({
                "ajax": {
                    "url": "../controllers/productoController.php?action=fetch",
                    "type": "GET"
                },
                "columns": [
                    { "title": "ID" },
                    { "title": "Nombre" },
                    { "title": "Descripción" },
                    { "title": "Precio" },
                    { "title": "Stock" },
                    { "title": "Acciones", "orderable": false }
                ],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
                }
            });

            // Cargar datos en el modal de edición
            $(document).on("click", ".btnEditar", function () {
                let id_producto = $(this).data("id");
                $.ajax({
                    url: "../controllers/productoController.php",
                    type: "GET",
                    data: {
                        action: "get", id_producto: id_producto
                    },
                    dataType: "json",
                    success: function (data) {
                        // Rellenar el formulario con los datos del producto
                        $("#id_producto").val(data.id_producto);
                        $("#edit_nombre").val(data.nombre);
                        $("#edit_descripcion").val(data.descripcion);
                        $("#edit_id_categoria").val(data.id_categoria);
                        $("#edit_color").val(data.color);
                        $("#edit_talla").val(data.talla);
                        $("#edit_precio").val(data.precio);
                        $("#edit_stock").val(data.stock);

                        // Mostrar el modal de edición
                        $("#modalEditar").modal("show");
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo cargar la información del producto.',
                        });
                    }
                });
            });

            // Guardar cambios en edición
            $("#formEditar").submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: "../controllers/productoController.php?action=edit",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function () {
                        $("#modalEditar").modal("hide");
                        $("#productosTable").DataTable().ajax.reload();
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo actualizar el producto.',
                        });
                    }
                });
            });

            // Agregar producto con AJAX
            $("#formAgregar").submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: "../controllers/productoController.php?action=create",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function () {
                        $("#modalAgregar").modal("hide");
                        $("#productosTable").DataTable().ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: '¡Producto agregado!',
                            text: 'El producto se ha agregado correctamente.',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo agregar el producto. Inténtalo de nuevo.',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                });
            });

            // Eliminar producto con AJAX
            $(document).on("click", ".btnEliminar", function () {
                let id_producto = $(this).data("id");
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esto!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "../controllers/productoController.php?action=delete&id_producto=" + id_producto;
                    }
                });
            });
        });

        S    </script>
</body>

</html>