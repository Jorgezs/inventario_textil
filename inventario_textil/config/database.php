<?php
$host = 'localhost';
$dbname = 'inventario_textil'; // Asegúrate de que el nombre de la base de datos sea correcto
$username = 'root';  // Usuario de la base de datos (por defecto 'root' en XAMPP)
$password = '';  // Contraseña (por defecto está vacía en XAMPP)

try {
    // Crear la conexión PDO a la base de datos
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Habilitar el modo de error
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
