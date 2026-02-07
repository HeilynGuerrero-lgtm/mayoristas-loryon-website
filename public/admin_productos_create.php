<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/_init.php';
require_once __DIR__ . '/../app/helpers/auth.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../app/models/producto.php';

requireLogin();
requireAdmin();

$model = new Producto($pdo);

/* ===== OBTENER PROVEEDORES ===== */
$proveedores = [];

try {
    $stmt = $pdo->prepare("SELECT PROV_COD, NOMBRE FROM proveedor ORDER BY NOMBRE");
    $stmt->execute();
    $proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
    // No romper la página
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $idProducto = $_POST['id_producto'];

    /* ===== SUBIDA DE IMAGEN ===== */
    $rutaImagenBD = null;

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {

        $directorio = __DIR__ . "/../public/img/productos/";

        if (!file_exists($directorio)) {
            mkdir($directorio, 0777, true);
        }

        $nombreArchivo = $idProducto . ".jpg";
        $rutaDestino = $directorio . $nombreArchivo;

        $imagenTemporal = $_FILES['imagen']['tmp_name'];
        $extension = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));

        if ($extension !== "jpg" && $extension !== "jpeg") {
            $imgOriginal = imagecreatefromstring(file_get_contents($imagenTemporal));
            imagejpeg($imgOriginal, $rutaDestino, 90);
            imagedestroy($imgOriginal);
        } else {
            move_uploaded_file($imagenTemporal, $rutaDestino);
        }

        $rutaImagenBD = "img/productos/" . $nombreArchivo;
    }

    /* ===== INSERTAR EN BD ===== */
    $data = [
        'id_producto'   => $idProducto,
        'nombre'        => $_POST['nombre'],
        'categoria'     => $_POST['categoria'],
        'precio_compra' => $_POST['precio_compra'],
        'precio_venta'  => $_POST['precio_venta'],
        'stock'         => $_POST['stock'],
        'descripcion'   => $_POST['descripcion'],
        'imagen'        => $rutaImagenBD,
        'prov_cod'      => $_POST['prov_cod']
    ];

    try {
        $model->create($data);
        header("Location: /public/admin_productos.php");
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <iframe src="/public/audio_background.php" style="display:none;" allow="autoplay"></iframe>
    <meta charset="UTF-8">
    <title>Crear Producto</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #3a1c71, #5f0a87, #a4508b);
    background-attachment: fixed;
    min-height: 100vh;
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #f3edff;
}

form {
    width: 95%;
    max-width: 700px;
    background: rgba(255,255,255,0.1);
    padding: 40px 55px;
    border-radius: 25px;
    backdrop-filter: blur(12px);
    box-shadow: 0 8px 40px rgba(100,50,255,0.3);
}

h1 {
    text-align: center;
    margin-bottom: 30px;
}

label {
    display: block;
    font-weight: 600;
    margin-bottom: 6px;
}

input[type="text"],
input[type="number"],
textarea,
input[type="file"],
select {
    width: 100%;
    padding: 14px 16px;
    border-radius: 12px;
    border: 1.5px solid #cdb8ff;
    font-size: 17px;
    background: #f2e8ff;
    color: #4d277a;
    margin-bottom: 15px;
}

textarea {
    min-height: 100px;
    resize: vertical;
}

button {
    width: 100%;
    padding: 14px;
    border: none;
    border-radius: 12px;
    background: linear-gradient(135deg, #a77bff, #7a4bff);
    color: white;
    font-size: 17px;
    cursor: pointer;
    margin-top: 20px;
}

.error {
    color: #ff9aa2;
    text-align: center;
    margin-bottom: 15px;
}
</style>
</head>

<body>
<form method="post" enctype="multipart/form-data">

    <h1>💜 Crear Producto</h1>

    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <label>ID del producto</label>
    <input type="text" name="id_producto" required placeholder="Ej: PROD001">

    <label>Nombre</label>
    <input type="text" name="nombre" required placeholder="Ej: Shampoo Lavanda">

    <label>Categoría</label>
    <select name="categoria" required>
        <option value="">-- Selecciona una categoría --</option>
        <option value="Baño">Baño</option>
        <option value="Belleza/Cuidado">Belleza / Cuidado</option>
        <option value="Cocina">Cocina</option>
        <option value="Herramientas">Herramientas</option>
        <option value="Hogar">Hogar</option>
        <option value="Niños">Niños</option>
        <option value="Otros">Otros</option>
        <option value="Utensilios">Utensilios</option>
    </select>

    <label>Precio de compra</label>
    <input type="number" step="0.01" name="precio_compra" required>

    <label>Precio de venta</label>
    <input type="number" step="0.01" name="precio_venta" required>

    <label>Stock</label>
    <input type="number" name="stock" required>

    <label>Descripción</label>
    <textarea name="descripcion"></textarea>

    <label>Imagen del producto</label>
    <input type="file" name="imagen" accept="image/*" required>

    <label>Proveedor</label>
    <select name="prov_cod" required>
        <option value="">-- Selecciona un proveedor --</option>
        <?php foreach ($proveedores as $prov): ?>
            <option value="<?= htmlspecialchars($prov['PROV_COD']) ?>">
                <?= htmlspecialchars($prov['PROV_COD']) ?> - <?= htmlspecialchars($prov['NOMBRE']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit">✨ Crear producto</button>

</form>
</body>
</html>
