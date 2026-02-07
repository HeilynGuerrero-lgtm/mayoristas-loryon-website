<?php
require_once __DIR__ . '/_init.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../app/models/producto.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: catalogo_cliente.php');
    exit;
}

$model = new Producto($pdo);
$producto = $model->find($id);

if (!$producto) {
    header('Location: catalogo_cliente.php');
    exit;
}

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

if (isset($_SESSION['carrito'][$id])) {
    $_SESSION['carrito'][$id]['cantidad']++;
} else {
    $_SESSION['carrito'][$id] = [
        'nombre' => $producto['NOMBRE'],
        'precio_venta' => $producto['PRECIO_VENTA'],
        'cantidad' => 1
    ];
}

header('Location: carrito_cliente.php');
exit;
