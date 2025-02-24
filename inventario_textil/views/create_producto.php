<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../public/styles.css">
    <script src="../public/script.js"></script>
    <title>Agregar Producto</title>
    <!-- Vincula Bootstrap 5 -->
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Agregar Nuevo Producto</h1>

        <!-- Formulario para agregar producto -->
        <form action="../controllers/productoController.php?action=create" method="POST">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Producto</label>
                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre del Producto" required>
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Descripción" required>
            </div>

            <div class="mb-3">
                <label for="id_categoria" class="form-label">ID Categoría</label>
                <input type="number" class="form-control" id="id_categoria" name="id_categoria" placeholder="ID Categoría" required>
            </div>

            <div class="mb-3">
                <label for="color" class="form-label">Color</label>
                <input type="text" class="form-control" id="color" name="color" placeholder="Color" required>
            </div>

            <div class="mb-3">
                <label for="talla" class="form-label">Talla</label>
                <input type="text" class="form-control" id="talla" name="talla" placeholder="Talla" required>
            </div>

            <div class="mb-3">
                <label for="precio" class="form-label">Precio</label>
                <input type="number" class="form-control" id="precio" name="precio" placeholder="Precio" required>
            </div>

            <div class="mb-3">
                <label for="stock" class="form-label">Stock</label>
                <input type="number" class="form-control" id="stock" name="stock" placeholder="Stock" required>
            </div>

            <!-- Botón para agregar el producto -->
            <button type="submit" class="btn btn-primary">Agregar Producto</button>
            <a href="dashboard.php" class="btn btn-secondary ms-2">Volver al Panel</a>
        </form>
    </div>

    <!-- Vincula el JS de Bootstrap -->
</body>
</html>
