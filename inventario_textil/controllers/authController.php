<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('../config/database.php');


class AuthController {

    // Método para iniciar sesión
    public function login($email, $password) {
        global $pdo;

        // Verificar si la conexión a la base de datos está activa
        if ($pdo === null) {
            die("Error: No se pudo conectar a la base de datos.");
        }

        // Preparar la consulta para obtener el usuario por su email
        $query = 'SELECT * FROM usuarios WHERE email = :email';
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        // Verificar si el usuario existe
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificar la contraseña
            if (password_verify($password, $user['password'])) {
                // Establecer los datos del usuario en la sesión
                $_SESSION['user_id'] = $user['id_usuario'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['rol'];

                // Redirigir según el rol del usuario
                if ($user['rol'] == 'admin') {
                    // Si es administrador, redirigir al panel de administración
                    header('Location: ../views/dashboard.php');
                    exit(); // Asegúrate de salir después de la redirección
                } else {
                    // Si es un usuario normal, redirigir a una página de usuario
                    header('Location: ../views/productos.php');
                    exit();
                }
            } else {
                // Si la contraseña no es correcta
                return false;
            }
        }

        return false; // Si no se encontraron resultados
    }

    // Método para registrar un nuevo usuario (añadir)
    public function register($nombre, $email, $password, $rol) {
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

    // Método para cerrar sesión
    public function logout() {
        session_start(); // Inicia la sesión en caso de que no esté iniciada
        session_unset(); // Elimina todas las variables de sesión
        session_destroy(); // Destruye la sesión
    
        // Redirigir al usuario a la página de inicio de sesión
        header('Location: ../views/login.php');
        exit();
    }
}
