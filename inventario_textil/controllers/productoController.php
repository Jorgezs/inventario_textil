<?php
// controllers/productoController.php

require_once('../models/Producto.php');

class ProductoController {
    // Mostrar todos los productos
    public function index() {
        $productos = Producto::getAll();
        include('../views/productos.php');
    }

    // Mostrar un formulario para crear un nuevo producto
    public function createForm() {
        include('../views/create_producto.php');
    }

    // Crear un producto
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];
            $id_categoria = $_POST['id_categoria'];
            $color = $_POST['color'];
            $talla = $_POST['talla'];
            $precio = $_POST['precio'];
            $stock = $_POST['stock'];

            Producto::create($nombre, $descripcion, $id_categoria, $color, $talla, $precio, $stock);
            header('Location: ../controllers/productoController.php?action=index');
            exit();
        }
    }

    // Mostrar un formulario para editar un producto
    public function editForm($id_producto) {
        $producto = Producto::getById($id_producto);
        include('../views/edit_producto.php');
    }

    // Actualizar un producto
    public function update() {
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
            header('Location: ../controllers/productoController.php?action=index');
            exit();
        }
    }

    // Eliminar un producto
    public function delete($id_producto) {
        Producto::delete($id_producto);
        header('Location: ../controllers/productoController.php?action=index');
        exit();
    }
}
?>
