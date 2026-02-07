<?php
require_once __DIR__ . '/_init.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../app/controllers/VentaController.php'; // contiene crearVenta()
require_once __DIR__ . '/../app/controllers/CartController.php';

if (!isset($_SESSION['user'])) {
    // si quieres permitir checkout sin login, necesitas pedir datos cliente. Por simplicidad requerimos login.
    $_SESSION['checkout_error'] = 'Debes iniciar sesión para finalizar la compra.';
    header('Location: /public/login.php'); exit;
}

$uid = $_SESSION['user']['id'];
// construir items desde carrito DB (o session fallback)
$items = [];
$total = 0.0;
// leer detalle_carrito del usuario
$stmt = $pdo->prepare("SELECT d.id_producto, d.cantidad, d.precio, p.stock FROM carrito c JOIN detalle_carrito d ON c.id_carrito = d.id_carrito JOIN producto p ON p.id_producto = d.id_producto WHERE c.usuario_id = :uid");
$stmt->execute([':uid'=>$uid]);
$rows = $stmt->fetchAll();
foreach ($rows as $r) {
    $items[] = ['id_producto'=>$r['id_producto'],'cantidad'=>intval($r['cantidad']),'precio'=>floatval($r['precio'])];
    $total += $r['cantidad'] * $r['precio'];
}

if (empty($items)) {
    $_SESSION['checkout_error'] = 'El carrito está vacío.';
    header('Location: /public/cart.php'); exit;
}

// llamar a la función que ya tienes
$res = crearVenta($uid, $items, $total);
if ($res['success']) {
    // vaciar carrito DB
    $del = $pdo->prepare("DELETE d FROM detalle_carrito d JOIN carrito c ON c.id_carrito = d.id_carrito WHERE c.usuario_id = :uid");
    $del->execute([':uid'=>$uid]);
    // opcional: eliminar carrito
    $del2 = $pdo->prepare("DELETE FROM carrito WHERE usuario_id = :uid");
    $del2->execute([':uid'=>$uid]);

    // también limpiar session cart
    clearSessionCart();

    $_SESSION['checkout_success'] = 'Compra registrada. ID venta: '.$res['venta_id'];
    header('Location: /public/'); exit;
} else {
    $_SESSION['checkout_error'] = 'Error al procesar la venta: ' . $res['error'];
    header('Location: /public/cart.php'); exit;
}
