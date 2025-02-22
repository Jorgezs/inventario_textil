<?php
// Incluir el archivo de configuración de la base de datos
require_once('config/database.php');

try {
    // Obtener todos los usuarios
    $query = "SELECT id_usuario, password FROM usuarios";
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Recorrer todos los usuarios y encriptar sus contraseñas
    foreach ($usuarios as $usuario) {
        $id_usuario = $usuario['id_usuario'];
        $password_plano = $usuario['password'];

        // Encriptar la contraseña usando bcrypt
        $password_encriptada = password_hash($password_plano, PASSWORD_BCRYPT);

        // Actualizar la contraseña en la base de datos
        $updateQuery = "UPDATE usuarios SET password = :password WHERE id_usuario = :id_usuario";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->bindParam(':password', $password_encriptada, PDO::PARAM_STR);
        $updateStmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $updateStmt->execute();
    }

    echo "Contraseñas actualizadas exitosamente.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
