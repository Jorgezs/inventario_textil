<?php
session_start();

// Verificar que el usuario esté logueado y sea un administrador
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php'); // Redirigir si no está logueado como admin
    exit();
}

// Mostrar el panel de administración
echo "<h1>Bienvenido, Administrador</h1>";
// Aquí puedes agregar más contenido de administración, como productos, usuarios, etc.
?>

<a href="../controllers/authController.php?logout=true">Cerrar sesión</a>
