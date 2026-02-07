<?php
require_once __DIR__ . '/_init.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../app/controllers/CartController.php';
require_once __DIR__ . '/../app/models/productos/producto.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /public/'); exit;
}

$id = $_POST['id_producto'] ?? null;
$cant = max(1, intval($_POST['cantidad'] ?? 1));
if (!$id) {
    header('Location: /public/'); exit;
}

// obtener info del producto para precio/nombre
$prodModel = new Producto($pdo);
$p = $prodModel->find($id);
if (!$p) { header('Location: /public/'); exit; }

if (isset($_SESSION['user'])) {
    // usuario logueado -> guardar en DB carrito
    // simple approach: cargar carrito DB, actualizar o insertar
    $uid = $_SESSION['user']['id'];
    // obtener / crear carrito
    $stmt = $pdo->prepare("SELECT id_carrito FROM carrito WHERE usuario_id = :uid LIMIT 1");
    $stmt->execute([':uid'=>$uid]);
    $carritoId = $stmt->fetchColumn();
    if (!$carritoId) {
        $ins = $pdo->prepare("INSERT INTO carrito (usuario_id, fecha_creacion) VALUES (:uid, NOW())");
        $ins->execute([':uid'=>$uid]);
        $carritoId = $pdo->lastInsertId();
    }
    // si ya existe detalle, actualizar cantidad
    $sel = $pdo->prepare("SELECT cantidad FROM detalle_carrito WHERE id_carrito = :cid AND id_producto = :pid LIMIT 1");
    $sel->execute([':cid'=>$carritoId, ':pid'=>$id]);
    $existing = $sel->fetchColumn();
    if ($existing) {
        $upd = $pdo->prepare("UPDATE detalle_carrito SET cantidad = cantidad + :cant WHERE id_carrito = :cid AND id_producto = :pid");
        $upd->execute([':cant'=>$cant, ':cid'=>$carritoId, ':pid'=>$id]);
    } else {
        $ins = $pdo->prepare("INSERT INTO detalle_carrito (id_carrito, id_producto, cantidad, precio) VALUES (:cid, :pid, :cant, :precio)");
        $ins->execute([':cid'=>$carritoId, ':pid'=>$id, ':cant'=>$cant, ':precio'=>$p['precio_venta']]);
    }
} else {
    // no logueado -> session cart
    addToSessionCart($id, $cant, ['nombre'=>$p['nombre'], 'precio'=>$p['precio_venta']]);
}

header('Location: /public/cart.php'); exit;
