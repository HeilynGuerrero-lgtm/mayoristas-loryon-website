<?php
require_once __DIR__ . '/_init.php';
require_once __DIR__ . '/../app/helpers/auth.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../app/models/producto.php';

requireLogin();
requireAdmin();

// Verificar si viene el ID del producto
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: admin_productos.php');
    exit;
}

$id = $_GET['id'];

$model = new Producto($pdo);

// 🔥 Obtener el producto antes de eliminarlo
$producto = $model->find($id);

// 🔥 Borrar imagen física si existe
if ($producto && !empty($producto['ID_PRODUCTO'])) {
    $rutaImagen = __DIR__ . "/img/productos/" . $producto['ID_PRODUCTO'] . ".jpg";

    if (file_exists($rutaImagen)) {
        unlink($rutaImagen); // elimina la imagen
    }
}

if ($model->delete($id)) {
    header('Location: admin_productos.php');
    exit;
} else {
    echo "<p>Error al eliminar el producto con ID: " . htmlspecialchars($id) . "</p>";
}
?>
