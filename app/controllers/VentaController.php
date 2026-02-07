<?php
require_once __DIR__.'/../../config/db.php';

function crearVenta($usuario_id, $items, $total) {
    // $items = array of ['id_producto'=>..., 'cantidad'=>..., 'precio'=>...]
    global $pdo;
    try {
        $pdo->beginTransaction();

        // crear venta
        $stmt = $pdo->prepare("INSERT INTO venta (usuario_id, fecha, total) VALUES (:uid, NOW(), :total)");
        $stmt->execute([':uid'=>$usuario_id, ':total'=>$total]);
        $venta_id = $pdo->lastInsertId();

        $detalleStmt = $pdo->prepare("INSERT INTO detalle_venta (venta_id, id_producto, cantidad, precio_unitario, subtotal) VALUES (:vid, :pid, :cant, :pu, :sub)");

        foreach ($items as $it) {
            // Bloquea la fila del producto (solo funciona con InnoDB)
            $sel = $pdo->prepare("SELECT stock FROM producto WHERE id_producto = :id FOR UPDATE");
            $sel->execute([':id' => $it['id_producto']]);
            $prod = $sel->fetch();

            if (!$prod) {
                throw new Exception("Producto {$it['id_producto']} no existe.");
            }

            if ($prod['stock'] < $it['cantidad']) {
                throw new Exception("Stock insuficiente para {$it['id_producto']} (disponible {$prod['stock']}).");
            }

            // insertar detalle
            $subtotal = $it['cantidad'] * $it['precio'];
            $detalleStmt->execute([
                ':vid'=>$venta_id, ':pid'=>$it['id_producto'], ':cant'=>$it['cantidad'], ':pu'=>$it['precio'], ':sub'=>$subtotal
            ]);

            // decrementar stock
            $upd = $pdo->prepare("UPDATE producto SET stock = stock - :qty WHERE id_producto = :id");
            $upd->execute([':qty'=>$it['cantidad'], ':id'=>$it['id_producto']]);
        }

        $pdo->commit();
        return ['success'=>true, 'venta_id'=>$venta_id];
    } catch (Exception $e) {
        $pdo->rollBack();
        return ['success'=>false, 'error'=>$e->getMessage()];
    }
}
