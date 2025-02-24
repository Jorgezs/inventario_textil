<?php
session_start();
require_once('../models/Producto.php');
require_once('../models/Movimiento.php'); // Modelo para movimientos de inventario

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

    header('Location: dashboard.php');
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
    <link rel="stylesheet" href="../public/styles.css">
</head>
<body>
    <!-- Navbar y demás contenido -->
    <div class="container mt-4">
        <h1 class="text-center">Registrar Movimiento de Inventario</h1>
        <form action="registro_movimiento.php" method="POST">
            <div class="mb-3">
                <label for="id_producto" class="form-label">Producto</label>
                <select id="id_producto" name="id_producto" class="form-control">
                    <?php foreach ($productos as $producto): ?>
                        <option value="<?= $producto['id_producto'] ?>"><?= $producto['nombre'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="cantidad" class="form-label">Cantidad</label>
                <input type="number" id="cantidad" name="cantidad" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="tipo_movimiento" class="form-label">Tipo de Movimiento</label>
                <select id="tipo_movimiento" name="tipo_movimiento" class="form-control">
                    <option value="entrada">Entrada</option>
                    <option value="salida">Salida</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea id="descripcion" name="descripcion" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Registrar Movimiento</button>
        </form>
    </div>
</body>
</html>
