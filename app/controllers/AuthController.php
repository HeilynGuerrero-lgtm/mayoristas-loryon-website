<?php
session_start();
require_once __DIR__.'/../../config/db.php';

function login($email, $password) {
    global $pdo;

    // el nombre correcto de la tabla y el campo EMAIL
    $stmt = $pdo->prepare("SELECT * FROM usuario WHERE EMAIL = :email LIMIT 1");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    // ⚠️  aquí usamos CLAVE (no password)
    if ($user && password_verify($password, $user['CLAVE'])) {

        // guarda los datos con los nombres correctos
        $_SESSION['user'] = [
            'id'     => $user['ID_USUARIO'],
            'nombre' => $user['NOMBRE_COMPLETO'],
            'rol'    => $user['ID_ROL'],
            'email'  => $user['EMAIL']
        ];
        return true;
    }
    return false;
}
