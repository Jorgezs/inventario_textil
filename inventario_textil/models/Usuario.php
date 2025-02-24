<?php
// models/Usuario.php

require_once('../config/database.php');

class Usuario {

    // Obtener todos los usuarios
    public static function getAll() {
        global $pdo;
        $query = "SELECT * FROM usuarios";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Eliminar un usuario por su ID
    public static function delete($id_usuario) {
        global $pdo;
        $query = "DELETE FROM usuarios WHERE id_usuario = :id_usuario";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
    }
}
?>
