<?php
session_start();
require_once('../models/Producto.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../views/login.php');
    exit();
}

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'fetch':
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
                    <button class="btnEditar btn btn-warning btn-sm" data-id="' . $producto['id_producto'] . '"><i class="fas fa-edit"></i></button>
                    <a href="../controllers/productoController.php?action=delete&id_producto=' . $producto['id_producto'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'¿Eliminar producto?\')"><i class="fas fa-trash"></i></a>'
                ];
            }
            echo json_encode(["data" => $data]);
            break;

        case 'get':
            if (isset($_GET['id_producto'])) {
                $producto = Producto::getById($_GET['id_producto']);
                echo json_encode($producto);
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

                $producto = Producto::update($id_producto, $nombre, $descripcion, $id_categoria, $color, $talla, $precio, $stock);
                if ($producto) {
                    header('Location: ../views/dashboard.php');
                } else {
                    echo "Error al editar el producto.";
                }
            }
            break;

        case 'delete':
            if (isset($_GET['id_producto'])) {
                Producto::delete($_GET['id_producto']);
                header('Location: ../views/dashboard.php');
            }
            break;

        case 'create':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $nombre = $_POST['nombre'];
                $descripcion = $_POST['descripcion'];
                $id_categoria = $_POST['id_categoria'];
                $color = $_POST['color'];
                $talla = $_POST['talla'];
                $precio = $_POST['precio'];
                $stock = $_POST['stock'];

                $producto = Producto::create($nombre, $descripcion, $id_categoria, $color, $talla, $precio, $stock);
                if ($producto) {
                    echo json_encode(["status" => "success"]);
                } else {
                    echo json_encode(["status" => "error"]);
                }
            }
            break;
    }
}



?>