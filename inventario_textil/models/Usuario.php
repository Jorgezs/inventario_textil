<?php
class Usuario {
    public $id_usuario;
    public $nombre;
    public $email;
    public $password;
    public $rol;

    public function __construct($id_usuario, $nombre, $email, $password, $rol) {
        $this->id_usuario = $id_usuario;
        $this->nombre = $nombre;
        $this->email = $email;
        $this->password = $password;
        $this->rol = $rol;
    }

    public static function findByEmail($email) {
        global $pdo;

        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            return new Usuario($user['id_usuario'], $user['nombre'], $user['email'], $user['password'], $user['rol']);
        }

        return null;
    }
}
