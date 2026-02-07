<?php
require_once __DIR__ . '/_init.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../app/helpers/auth.php';
require_once __DIR__ . '/../app/models/usuario.php';

requireLogin();
requireAdmin();

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: admin_equipo.php");
    exit;
}

// no puede eliminarse a sí mismo
if ($id == $_SESSION['user_id']) {
    die("No puedes eliminarte a ti mismo.");
}

$model = new Usuario($pdo);
$model->delete($id);

header("Location: admin_equipo.php");
exit;
?>
