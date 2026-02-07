<?php
// login_process.php
require_once __DIR__ . '/_init.php';
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /public/login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Validar campos
if (empty($email) || empty($password)) {
    $_SESSION['login_error'] = 'Por favor ingresa tu correo y contraseña.';
    header('Location: /public/login.php');
    exit;
}

try {
    // Buscar usuario por EMAIL
    $stmt = $pdo->prepare("SELECT * FROM usuario WHERE EMAIL = :email LIMIT 1");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $hashGuardado = $user['CLAVE'];

        // Verificar contraseña escrita vs hash guardado
        if (password_verify($password, $hashGuardado)) {

            // Contraseña correcta ✅
            session_regenerate_id(true);
            $_SESSION['user'] = [
                'id' => $user['ID_USUARIO'],
                'nombre' => $user['NOMBRE_COMPLETO'],
                'rol' => $user['ID_ROL'],
                'email' => $user['EMAIL']
            ];

            // Redirigir según rol
            if ($user['ID_ROL'] == 1) {
                header('Location: /public/index.php');
            } else {
                header('Location: /public/catalogo_cliente.php');
            }
            exit;
        } else {
            // Contraseña incorrecta ❌
            $_SESSION['login_error'] = 'Usuario o contraseña incorrectos.';
            header('Location: /public/login.php');
            exit;
        }
    } else {
        // No existe el usuario ❌
        $_SESSION['login_error'] = 'Usuario o contraseña incorrectos.';
        header('Location: /public/login.php');
        exit;
    }
} catch (PDOException $e) {
    // Error de conexión o consulta
    $_SESSION['login_error'] = 'Error en la base de datos: ' . $e->getMessage();
    header('Location: /public/login.php');
    exit;
}
