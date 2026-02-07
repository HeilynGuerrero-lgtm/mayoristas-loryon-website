<?php
require_once __DIR__ . '/_init.php';
require_once __DIR__ . '/../config/db.php';

define('DEV_SHOW_OTP', true); // ✅ modo desarrollo

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /public/login.php');
    exit;
}

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
if (!$email) {
    $_SESSION['login_error'] = 'Por favor ingresa un correo válido.';
    header('Location: /public/recuperar.php');
    exit;
}

// Buscar usuario
$stmt = $pdo->prepare("SELECT ID_USUARIO, NOMBRE_COMPLETO FROM usuario WHERE EMAIL = :email LIMIT 1");
$stmt->execute(['email' => $email]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    $_SESSION['login_error'] = 'Si el correo está registrado, recibirás tu código.';
    header('Location: /public/recuperar.php');
    exit;
}

// Generar OTP simulado
$otp = random_int(100000, 999999);
$hash = password_hash((string)$otp, PASSWORD_DEFAULT);
$expira = (new DateTime('+10 minutes'))->format('Y-m-d H:i:s');

// Guardar en BD
$ins = $pdo->prepare("
    INSERT INTO recuperacion_clave (ID_USUARIO, CODIGO_HASH, FECHA_EXPIRA)
    VALUES (:id, :h, :e)
");
$ins->execute(['id' => $usuario['ID_USUARIO'], 'h' => $hash, 'e' => $expira]);

// Guardar OTP en sesión solo si estamos en desarrollo
if (defined('DEV_SHOW_OTP') && DEV_SHOW_OTP) {
    $_SESSION['DEV_OTP'] = $otp;
    $_SESSION['DEV_EMAIL'] = $email;
}

// Mensaje simulado
$_SESSION['login_success'] = 'Código generado (modo pruebas).';
header('Location: /public/verificar_codigo.php');
exit;
