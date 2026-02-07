<?php
// app/helpers/auth.php
function isLogged() {
    return isset($_SESSION['user']);
}
function isAdmin() {
    return isLogged() && isset($_SESSION['user']['rol']) && $_SESSION['user']['rol'] == 1;
}
function requireLogin() {
    if (!isLogged()) {
        header('Location: /loryon/public/login.php'); exit;
    }
}
function requireAdmin() {
    if (!isAdmin()) {
        header('Location: /loryon/public/login.php'); exit;
    }
}
