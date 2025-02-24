<?php
session_start();
require_once('../models/Usuario.php');

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
                $email = $_POST['email'];
                $password = $_POST['password'];
                $rol = $_POST['rol'];
                // Llamar al método de registro
                if (Usuario::register($nombre, $email, $password, $rol)) {
                    header('Location: ../views/dashboard.php'); // Redirigir al listado de usuarios
                    exit();
                } else {
                    echo "Error al crear el usuario.";
                }
            }
            break;

        case 'edit':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id_usuario = $_POST['id_usuario'];
                $nombre = $_POST['nombre'];
                $email = $_POST['email'];
                $password = $_POST['password']; // Si no se ingresa una nueva contraseña, se puede manejar opcionalmente
                $rol = $_POST['rol'];
                // Llamar al método de actualización
                if (Usuario::update($id_usuario, $nombre, $email, $password, $rol)) {
                    header('Location: ../views/dashboard.php'); // Redirigir al listado de usuarios
                    exit();
                } else {
                    echo "Error al actualizar el usuario.";
                }
            }
            break;

        case 'delete':
            if (isset($_GET['id_usuario'])) {
                Usuario::delete($_GET['id_usuario']);
                header('Location: ../views/dashboard.php'); // Redirigir al listado de usuarios
                exit();
            }
            break;
    }
}
?>
