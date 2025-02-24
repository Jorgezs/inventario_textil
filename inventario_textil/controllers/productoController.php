<?php
session_start();
require_once('../models/Producto.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../views/login.php');
    exit();
}

// Manejo de acciones
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'create':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $nombre = $_POST['nombre'];
                $descripcion = $_POST['descripcion'];
                $id_categoria = $_POST['id_categoria'];
                $color = $_POST['color'];
                $talla = $_POST['talla'];
                $precio = $_POST['precio'];
                $stock = $_POST['stock'];
                Producto::create($nombre, $descripcion, $id_categoria, $color, $talla, $precio, $stock);
                header('Location: ../views/dashboard.php');
                exit();
            }
            break;

        case 'edit':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id_producto = $_POST['id_producto'];
                $nombre = $_POST['nombre'];
                $descripcion = $_POST['descripcion'];
                $id_categoria = $_POST['id_categoria'];
                $color = $_POST['color'];
                $talla = $_POST['talla'];
                $precio = $_POST['precio'];
                $stock = $_POST['stock'];
                Producto::update($id_producto, $nombre, $descripcion, $id_categoria, $color, $talla, $precio, $stock);
                header('Location: ../views/dashboard.php');
                exit();
            }
            break;

        case 'delete':
            if (isset($_GET['id_producto'])) {
                Producto::delete($_GET['id_producto']);
                header('Location: ../views/dashboard.php');
                exit();
            }
            break;
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'fetch') {
    $productos = Producto::getAll();
    $data = [];

    foreach ($productos as $producto) {
        $data[] = [
            $producto['id_producto'],
            $producto['nombre'],
            $producto['descripcion'],
            $producto['precio'],
            $producto['stock'],
            '<a href="../views/view_producto.php?id=' . $producto['id_producto'] . '" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
             <button class="btnEditar btn btn-warning btn-sm " data-id="' . $producto['id_producto'] . '"><i class="fas fa-edit"></i></button>
             <a href="../controllers/productoController.php?action=delete&id_producto=' . $producto['id_producto'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'¿Eliminar producto?\')"><i class="fas fa-trash"></i></a>'
        ];
    }

    echo json_encode(["data" => $data]);
    exit();
}

?>