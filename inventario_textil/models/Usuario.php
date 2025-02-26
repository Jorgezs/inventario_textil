<?php
// models/Usuario.php

require_once __DIR__ . '/../config/database.php';

class Usuario {

    // Obtener todos los usuarios
    public static function getAll() {
        global $pdo;
        $query = "SELECT * FROM usuarios";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un usuario por su ID
    public static function getById($id_usuario) {
        global $pdo;
        $query = "SELECT * FROM usuarios WHERE id_usuario = :id_usuario";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Registrar un nuevo usuario
    public static function register($nombre, $email, $password, $rol) {
        global $pdo;

        // Encriptar la contraseña
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Preparar la consulta para insertar el nuevo usuario
        $query = 'INSERT INTO usuarios (nombre, email, password, rol) VALUES (:nombre, :email, :password, :rol)';
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(':rol', $rol, PDO::PARAM_STR);

        if ($stmt->execute()) {
            return true; // Usuario registrado correctamente
        }
        return false; // Error al registrar el usuario
    }
// Actualizar un usuario existente
public static function update($id_usuario, $nombre, $email, $password, $rol) {
    global $pdo;

    // Si no se proporciona una nueva contraseña, no la actualizamos
    if (empty($password)) {
        // No actualizamos la contraseña, solo actualizamos los otros campos
        $query = 'UPDATE usuarios SET nombre = :nombre, email = :email, rol = :rol WHERE id_usuario = :id_usuario';
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':rol', $rol, PDO::PARAM_STR);
    } else {
        // Si se proporciona una nueva contraseña, la encriptamos y la actualizamos
        $query = 'UPDATE usuarios SET nombre = :nombre, email = :email, password = :password, rol = :rol WHERE id_usuario = :id_usuario';
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', password_hash($password, PASSWORD_BCRYPT), PDO::PARAM_STR);
        $stmt->bindParam(':rol', $rol, PDO::PARAM_STR);
    }

    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);

    if ($stmt->execute()) {
        return true; // Usuario actualizado correctamente
    }
    return false; // Error al actualizar el usuario
}

public static function delete($id_usuario) {
    global $pdo;
    
    $query = "DELETE FROM usuarios WHERE id_usuario = :id_usuario";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    
    return $stmt->execute(); // Retorna true si se ejecuta correctamente
}


}
?>
