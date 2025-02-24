<?php
session_start();
require_once('../config/database.php'); // Si necesitas la conexión

// Si el parámetro logout está en la URL, ejecuta el método de cierre de sesión
if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    $auth = new AuthController();
    $auth->logout();
}

class AuthController {
    
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

    // Método para manejar el envío del formulario de registro
    public function handleRequest() {
        if (isset($_POST['action']) && $_POST['action'] === 'register') {
            $nombre = $_POST['nombre'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $rol = $_POST['rol'];

            // Llamar al método de registro
            $result = $this->register($nombre, $email, $password, $rol);

            if ($result) {
                header('Location: ../views/dashboard.php'); // Redirigir a la página de administración
                exit();
            } else {
                echo "Error al crear el usuario.";
            }
        }
    }

    // Método para iniciar sesión
    public function login($email, $password) {
        global $pdo;

        if ($pdo === null) {
            die("Error: No se pudo conectar a la base de datos.");
        }

        $query = 'SELECT * FROM usuarios WHERE email = :email';
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id_usuario'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['rol'];
                $_SESSION['user_name'] = $user['nombre'];  // Añadir el nombre a la sesión

                

                if ($user['rol'] == 'admin') {
                    header('Location: ../views/dashboard.php');
                    exit();
                } else {
                    header('Location: ../views/usuario.php');
                    exit();
                }
            } else {
                return false;
            }
        }

        return false;
    }

    // Método para cerrar sesión
    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header('Location: ../views/login.php');
        exit();
    }
}

// Crear una instancia del controlador y manejar la solicitud
$auth = new AuthController();
$auth->handleRequest();
?>