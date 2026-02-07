<?php
// app/controllers/CartController.php
// funciones para carrito en session y sync con DB
require_once __DIR__ . '/../../config/db.php';

// formato $_SESSION['cart'] = [ 'id_producto' => ['cantidad'=>n, 'nombre'=>..., 'precio'=>...], ... ]

function addToSessionCart($id_producto, $cantidad = 1, $extra = []) {
    if (session_status() == PHP_SESSION_NONE) session_start();
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    if (isset($_SESSION['cart'][$id_producto])) {
        $_SESSION['cart'][$id_producto]['cantidad'] += $cantidad;
    } else {
        $_SESSION['cart'][$id_producto] = array_merge(['cantidad'=>$cantidad], $extra);
    }
}

function updateSessionCartQuantity($id_producto, $cantidad) {
    if (session_status() == PHP_SESSION_NONE) session_start();
    if (!isset($_SESSION['cart'][$id_producto])) return;
    if ($cantidad <= 0) {
        unset($_SESSION['cart'][$id_producto]);
    } else {
        $_SESSION['cart'][$id_producto]['cantidad'] = $cantidad;
    }
}

function clearSessionCart() {
    if (session_status() == PHP_SESSION_NONE) session_start();
    unset($_SESSION['cart']);
}

// --- sincronización simple session -> DB ---
// Asumo tabla carrito (id_carrito, usuario_id) y detalle_carrito (id_carrito, id_producto, cantidad, precio)
// Si tu BD tiene otras columnas, ajusta.
function saveSessionCartToDB($usuario_id, $sessionCart, $pdo) {
    // encontrar o crear carrito del usuario
    $stmt = $pdo->prepare("SELECT id_carrito FROM carrito WHERE usuario_id = :uid LIMIT 1");
    $stmt->execute([':uid'=>$usuario_id]);
    $carrito = $stmt->fetchColumn();
    if (!$carrito) {
        $ins = $pdo->prepare("INSERT INTO carrito (usuario_id, fecha_creacion) VALUES (:uid, NOW())");
        $ins->execute([':uid'=>$usuario_id]);
        $carrito = $pdo->lastInsertId();
    }
    // limpiar detalles actuales
    $pdo->prepare("DELETE FROM detalle_carrito WHERE id_carrito = :cid")->execute([':cid'=>$carrito]);
    $insDet = $pdo->prepare("INSERT INTO detalle_carrito (id_carrito, id_producto, cantidad, precio) VALUES (:cid, :pid, :cant, :precio)");
    foreach ($sessionCart as $pid => $item) {
        $precio = $item['precio'] ?? 0;
        $cant = $item['cantidad'] ?? 1;
        $insDet->execute([':cid'=>$carrito, ':pid'=>$pid, ':cant'=>$cant, ':precio'=>$precio]);
    }
    return true;
}

// --- leer carrito DB para un usuario ---
function loadCartFromDB($usuario_id, $pdo) {
    $stmt = $pdo->prepare("SELECT c.id_carrito, d.id_producto, d.cantidad, d.precio
                           FROM carrito c
                           JOIN detalle_carrito d ON c.id_carrito = d.id_carrito
                           WHERE c.usuario_id = :uid");
    $stmt->execute([':uid'=>$usuario_id]);
    $rows = $stmt->fetchAll();
    $cart = [];
    foreach ($rows as $r) {
        $cart[$r['id_producto']] = ['cantidad'=>$r['cantidad'], 'precio'=>$r['precio']];
    }
    return $cart;
}
