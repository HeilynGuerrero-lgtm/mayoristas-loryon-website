<?php
require_once __DIR__ . '/_init.php';
require_once __DIR__ . '/../helpers/auth.php';
requireLogin(); if (!isAdmin()) { header('Location: /public/'); exit; }
require_once __DIR__ . '/../config/db.php';

$id = trim($_POST['id_producto'] ?? '');
$nombre = trim($_POST['nombre'] ?? '');
$precio = floatval($_POST['precio_venta'] ?? 0);
$stock = intval($_POST['stock'] ?? 0);
$imagenNombre = null;

if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
    $imagenNombre = uniqid('p_').'.'.$ext;
    $dest = __DIR__ . '/uploads/' . $imagenNombre;
    move_uploaded_file($_FILES['imagen']['tmp_name'], $dest);
}

$ins = $pdo->prepare("INSERT INTO producto (id_producto,nombre,precio_venta,stock,imagen,created_at) VALUES (:id,:nombre,:pv,:stock,:img,NOW())");
$ins->execute([':id'=>$id, ':nombre'=>$nombre, ':pv'=>$precio, ':stock'=>$stock, ':img'=>$imagenNombre]);
header('Location: /public/admin_productos.php'); exit;
