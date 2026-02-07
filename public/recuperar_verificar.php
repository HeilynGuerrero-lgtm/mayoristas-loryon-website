<?php
require_once __DIR__ . '/_init.php';
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /loryon/public/login.php');
    exit;
}

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$codigo = trim($_POST['codigo'] ?? '');
$nueva = trim($_POST['nueva'] ?? '');

if (!$email || !$codigo || !$nueva) {
    $_SESSION['login_error'] = 'Todos los campos son obligatorios.';
    header('Location: /public/verificar_codigo.php');
    exit;
}

// Buscar usuario
$q = $pdo->prepare("SELECT ID_USUARIO FROM usuario WHERE EMAIL = :email LIMIT 1");
$q->execute(['email' => $email]);
$u = $q->fetch(PDO::FETCH_ASSOC);

if (!$u) {
    $_SESSION['login_error'] = 'Código inválido.';
    header('Location: /public/verificar_codigo.php');
    exit;
}

// Buscar último código válido
$r = $pdo->prepare("
    SELECT * FROM recuperacion_clave 
    WHERE ID_USUARIO = :id AND USADO = 0 
    ORDER BY FECHA_CREACION DESC LIMIT 1
");
$r->execute(['id' => $u['ID_USUARIO']]);
$reset = $r->fetch(PDO::FETCH_ASSOC);

if (
    !$reset ||
    new DateTime() > new DateTime($reset['FECHA_EXPIRA']) ||
    !password_verify($codigo, $reset['CODIGO_HASH'])
) {
    $_SESSION['login_error'] = 'Código inválido o expirado.';
    header('Location: /public/verificar_codigo.php');
    exit;
}

// Actualizar contraseña
$hash = password_hash($nueva, PASSWORD_DEFAULT);
$pdo->prepare("UPDATE usuario SET CLAVE = :c WHERE ID_USUARIO = :id")
    ->execute(['c' => $hash, 'id' => $u['ID_USUARIO']]);

// Marcar código como usado
$pdo->prepare("UPDATE recuperacion_clave SET USADO = 1 WHERE ID_RECUPERACION = :r")
    ->execute(['r' => $reset['ID_RECUPERACION']]);

unset($_SESSION['DEV_OTP'], $_SESSION['DEV_EMAIL']);
$_SESSION['login_success'] = 'Contraseña actualizada correctamente. Ya puedes iniciar sesión.';
header('Location: /public/login.php');
exit;
