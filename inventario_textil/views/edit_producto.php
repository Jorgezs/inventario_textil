<?php
session_start();
require_once('../models/Producto.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$producto = Producto::getById($_GET['id']);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../public/styles.css">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4"><i class="fas fa-edit"></i> Editar Producto</h1>

        <form action="../controllers/productoController.php?action=edit" method="POST"
            class="shadow p-4 rounded bg-light">
            <input type="hidden" name="id_producto" value="<?= $producto['id_producto'] ?>">

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Producto</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $producto['nombre'] ?>"
                    required>
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion"
                    required><?= $producto['descripcion'] ?></textarea>
            </div>

            <div class="mb-3">
                <label for="id_categoria" class="form-label">Categoría</label>
                <input type="number" class="form-control" id="id_categoria" name="id_categoria"
                    value="<?= $producto['id_categoria'] ?>" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="color" class="form-label">Color</label>
                    <input type="text" class="form-control" id="color" name="color" value="<?= $producto['color'] ?>"
                        required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="talla" class="form-label">Talla</label>
                    <input type="text" class="form-control" id="talla" name="talla" value="<?= $producto['talla'] ?>"
                        required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="precio" class="form-label">Precio</label>
                    <input type="number" class="form-control" id="precio" name="precio"
                        value="<?= $producto['precio'] ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="stock" class="form-label">Stock</label>
                    <input type="number" class="form-control" id="stock" name="stock" value="<?= $producto['stock'] ?>"
                        required>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar Cambios</button>
                <a href="../views/dashboard.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver al
                    Panel</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../public/script.js"></script>
</body>

</html>