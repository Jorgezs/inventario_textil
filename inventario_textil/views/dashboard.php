<?php
session_start();
require_once('../models/Producto.php');
require_once('../models/Usuario.php'); // Incluir el modelo de Usuario

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$productos = Producto::getAll();
$usuarios = Usuario::getAll(); // Obtener todos los usuarios
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
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><i class="fas fa-cogs"></i> Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
        <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home"
                    type="button" role="tab">Productos <i class="fas fa-box"></i></button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile"
                    type="button" role="tab">Usuarios <i class="fas fa-users"></i></button>
            </li>
        </ul>

        <div class="tab-content" id="pills-tabContent">
            <!-- Productos -->
            <div class="tab-pane fade show active" id="pills-home" role="tabpanel">
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
            <div class="tab-pane fade" id="pills-profile" role="tabpanel">
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
        </div>
    </div>
    <!-- modales  -->
    <div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="modalAgregarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAgregarLabel"><i class="fas fa-box"></i> Agregar Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregar">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="id_categoria" class="form-label">Categoría</label>
                            <input type="number" class="form-control" id="id_categoria" name="id_categoria" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="color" class="form-label">Color</label>
                                <input type="text" class="form-control" id="color" name="color" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="talla" class="form-label">Talla</label>
                                <input type="text" class="form-control" id="talla" name="talla" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="precio" class="form-label">Precio (€)</label>
                                <input type="number" class="form-control" id="precio" name="precio" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="stock" class="form-label">Stock</label>
                                <input type="number" class="form-control" id="stock" name="stock" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar Producto</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarLabel"><i class="fas fa-edit"></i> Editar Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditar">
                        <input type="hidden" id="id_producto" name="id_producto">
                        <div class="mb-3">
                            <label for="edit_nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="edit_nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="edit_descripcion" name="descripcion" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_id_categoria" class="form-label">Categoría</label>
                            <input type="number" class="form-control" id="edit_id_categoria" name="id_categoria"
                                required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_color" class="form-label">Color</label>
                                <input type="text" class="form-control" id="edit_color" name="color" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_talla" class="form-label">Talla</label>
                                <input type="text" class="form-control" id="edit_talla" name="talla" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_precio" class="form-label">Precio (€)</label>
                                <input type="number" class="form-control" id="edit_precio" name="precio" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_stock" class="form-label">Stock</label>
                                <input type="number" class="form-control" id="edit_stock" name="stock" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Guardar Cambios</button>
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
                let nombre = $(this).data("nombre");

                console.log("Se hizo clic en editar, ID:", id_producto, nombre);

                $.ajax({
                    url: "../controllers/productoController.php",
                    type: "GET",
                    data: {
                        action: "get", id_producto: id_producto,
                        nombre: nombre
                    },
                    dataType: "json",
                    success: function (data) {
                        $("#id_producto").val(data.id_producto);
                        $("#edit_nombre").val(data.nombre);
                        $("#edit_descripcion").val(data.descripcion);
                        $("#edit_id_categoria").val(data.id_categoria);
                        $("#edit_color").val(data.color);
                        $("#edit_talla").val(data.talla);
                        $("#edit_precio").val(data.precio);
                        $("#edit_stock").val(data.stock);

                        $("#modalEditar").modal("show"); // Asegúrate de que este código se ejecuta
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

                        // SweetAlert de éxito
                        Swal.fire({
                            icon: 'success',
                            title: '¡Producto agregado!',
                            text: 'El producto se ha agregado correctamente.',
                            showConfirmButton: false,
                            timer: 2000 // Cierra la alerta en 2 segundos
                        });
                    },
                    error: function () {
                        // SweetAlert de error
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo agregar el producto. Inténtalo de nuevo.',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                });
            });

        });
    </script>
</body>

</html>