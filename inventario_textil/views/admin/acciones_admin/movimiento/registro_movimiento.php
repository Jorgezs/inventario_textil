<?php
session_start();
require_once '../../../../models/Producto.php';
require_once('../../../../models/Movimiento.php'); // Modelo para movimientos de inventario

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_producto = $_POST['id_producto'];
    $cantidad = $_POST['cantidad'];
    $tipo_movimiento = $_POST['tipo_movimiento'];
    $descripcion = $_POST['descripcion'];
    $id_usuario = $_SESSION['user_id']; // Obtener ID del administrador

    // Registrar movimiento
    Movimiento::registrarMovimiento($id_producto, $id_usuario, $tipo_movimiento, $cantidad, $descripcion);

    // Actualizar stock según el tipo de movimiento
    Producto::actualizarStock($id_producto, $tipo_movimiento, $cantidad);

    header('Location: ../../../dashboard.php');
    exit();
}

$productos = Producto::getAll(); // Obtener todos los productos

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Movimiento de Inventario</title>

    <!-- Bootstrap 5 y estilos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="fas fa-warehouse"></i> Inventario</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a href="../../../dashboard.php" class="btn btn-outline-light">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido -->
    <div class="container mt-5">
        <h1 class="text-center mb-4"><i class="fas fa-exchange-alt"></i> Registrar Movimiento de Inventario</h1>

        <div class="card shadow-sm p-4">
            <form action="registro_movimiento.php" method="POST" id="formMovimiento">
                <div class="mb-3">
                    <label for="id_producto" class="form-label"><i class="fas fa-box"></i> Producto</label>
                    <select id="id_producto" name="id_producto" class="form-select" required>
                        <option value="" selected disabled>Seleccione un producto</option>
                        <?php foreach ($productos as $producto): ?>
                            <option value="<?= $producto['id_producto'] ?>"><?= htmlspecialchars($producto['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">Debe seleccionar un producto.</div>
                </div>

                <div class="mb-3">
                    <label for="cantidad" class="form-label"><i class="fas fa-sort-numeric-up-alt"></i> Cantidad</label>
                    <input type="number" id="cantidad" name="cantidad" class="form-control" min="1" required>
                    <div class="invalid-feedback">Ingrese una cantidad válida mayor a 0.</div>
                </div>

                <div class="mb-3">
                    <label for="tipo_movimiento" class="form-label"><i class="fas fa-sync-alt"></i> Tipo de Movimiento</label>
                    <select id="tipo_movimiento" name="tipo_movimiento" class="form-select" required>
                        <option value="entrada">Entrada</option>
                        <option value="salida">Salida</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label"><i class="fas fa-align-left"></i> Descripción</label>
                    <textarea id="descripcion" name="descripcion" class="form-control" rows="3" required></textarea>
                    <div class="invalid-feedback">Ingrese una descripción del movimiento.</div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-success" id="btnRegistrar" disabled>
                        <i class="fas fa-save"></i> Registrar Movimiento
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Validaciones con JavaScript -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.getElementById("formMovimiento");
            const btnRegistrar = document.getElementById("btnRegistrar");
            const inputs = form.querySelectorAll("input, select, textarea");

            form.addEventListener("input", function () {
                let formValido = true;
                inputs.forEach(input => {
                    if (!input.checkValidity()) {
                        formValido = false;
                    }
                });
                btnRegistrar.disabled = !formValido;
            });

            form.addEventListener("submit", function (event) {
                event.preventDefault();
                
                Swal.fire({
                    title: 'Confirmar Registro',
                    text: "¿Desea registrar este movimiento de inventario?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, registrar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>

    <!-- Scripts de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
