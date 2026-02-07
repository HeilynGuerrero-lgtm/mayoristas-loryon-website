<?php
// public/_init.php
// Inicio de sesión seguro
if (session_status() == PHP_SESSION_NONE) {
    // cookies más seguras (ajusta según entorno)
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_strict_mode', 1);
    session_start();
}
