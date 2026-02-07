<?php
require_once __DIR__ . '/_init.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../app/controllers/CartController.php';
require_once __DIR__ . '/../app/models/productos/producto.php';

$items = [];
$total = 0.0;

if (isset($_SESSION['user'])) {
    // cargar desde DB
    $uid = $_SESSION['user']['id'];
    $stmt = $pdo->prepare("SELECT d.id_producto, d.cantidad, d.precio, p.nombre, p.stock
                           FROM carrito c JOIN detalle_carrito d ON c.id_carrito = d.id_carrito
                           JOIN producto p ON p.id_producto = d.id_producto
                           WHERE c.usuario_id = :uid");
    $stmt->execute([':uid'=>$uid]);
    $rows = $stmt->fetchAll();
    foreach ($rows as $r) {
        $subtotal = $r['cantidad'] * $r['precio'];
        $items[] = ['id'=>$r['id_producto'],'nombre'=>$r['nombre'],'cantidad'=>$r['cantidad'],'precio'=>$r['precio'],'subtotal'=>$subtotal,'stock'=>$r['stock']];
        $total += $subtotal;
    }
} else {
    if (!empty($_SESSION['cart'])) {
        // cargar detalles de productos para asegurar precio/stock actual
        $prodModel = new Producto($pdo);
        foreach ($_SESSION['cart'] as $pid => $it) {
            $p = $prodModel->find($pid);
            if (!$p) continue;
            $cantidad = $it['cantidad'];
            $precio = $p['precio_venta'];
            $subtotal = $cantidad * $precio;
            $items[] = ['id'=>$pid,'nombre'=>$p['nombre'],'cantidad'=>$cantidad,'precio'=>$precio,'subtotal'=>$subtotal,'stock'=>$p['stock']];
            $total += $subtotal;
        }
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Carrito - Loryon</title></head>
<body>
  <h2>Carrito</h2>
  <?php if(empty($items)): ?><p>Tu carrito está vacío</p><?php else: ?>
    <table border="1" cellpadding="6">
      <tr><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr>
      <?php foreach($items as $it): ?>
        <tr>
          <td><?=htmlspecialchars($it['nombre'])?></td>
          <td><?=intval($it['cantidad'])?></td>
          <td><?=number_format($it['precio'],2)?></td>
          <td><?=number_format($it['subtotal'],2)?></td>
        </tr>
      <?php endforeach; ?>
      <tr><td colspan="3"><strong>Total</strong></td><td><?=number_format($total,2)?></td></tr>
    </table>

    <form action="/public/checkout.php" method="post">
      <button type="submit">Finalizar compra</button>
    </form>
  <?php endif; ?>
</body>
</html>
