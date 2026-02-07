<?php
require_once __DIR__ . '/_init.php';
require_once __DIR__ . '/../app/helpers/auth.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../app/models/producto.php';

requireLogin();
requireAdmin();

$model = new Producto($pdo);

$id = $_GET['id'] ?? null;
if (!$id) die("ID de producto no especificado.");

$producto = $model->find($id);
if (!$producto) die("Producto no encontrado.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Datos básicos
    $data = [
        'nombre' => $_POST['nombre'],
        'categoria' => $_POST['categoria'],
        'precio_compra' => $_POST['precio_compra'],
        'precio_venta' => $_POST['precio_venta'],
        'stock' => $_POST['stock'],
        'descripcion' => $_POST['descripcion'],
        'prov_cod' => $_POST['prov_cod'],
        'imagen' => $producto['IMAGEN'] // Mantener imagen anterior si no cambia
    ];

    // ==============================
    // ⬇ PROCESO DE IMAGEN EXACTO COMO PEDISTE ⬇
    // ==============================
    if (!empty($_FILES['imagen']['tmp_name'])) {

        // Ruta fija donde guardas tus imágenes
        $carpeta = __DIR__ . '/img/productos/';

        // Nombre del archivo basado en el ID del producto
        $nombreArchivo = $producto['ID_PRODUCTO'] . '.jpg';

        // Ruta completa en disco
        $rutaFinal = $carpeta . $nombreArchivo;

        // BORRAR LA IMAGEN ANTERIOR SI EXISTE
        if (file_exists($rutaFinal)) {
            unlink($rutaFinal);
        }

        // Convertir imagen a JPG
        $imgData = file_get_contents($_FILES['imagen']['tmp_name']);
        $image = imagecreatefromstring($imgData);

        // Guardar JPG optimizado
        imagejpeg($image, $rutaFinal, 85);
        imagedestroy($image);

        // Ruta que se guarda en la base de datos
        $data['imagen'] = 'img/productos/' . $nombreArchivo;
    }
    // ==============================
    // ⬆ FIN DEL PROCESO DE IMAGEN ⬆
    // ==============================

    // Actualizar en la base de datos
    $model->update($id, $data);

    header('Location: /public/admin_productos.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>    
    <iframe src="/public/audio_background.php"
        style="display:none;"
        allow="autoplay">
    </iframe>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

        html {
    font-size: 16px;
    -webkit-text-size-adjust: 100%;
}

body {
    min-width: 100%;
    width: 100%;
}

        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #3a1c71, #5f0a87, #a4508b);
            background-attachment: fixed;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #f3edff;
        }

        .container {
            width: 95%;
            max-width: 780px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 25px;
            padding: 45px 55px;
            backdrop-filter: blur(12px);
            box-shadow: 0 8px 40px rgba(100, 50, 255, 0.3);
            border: 1px solid rgba(200, 170, 255, 0.3);
            animation: fadeInUp 0.8s ease;
        }

        @keyframes fadeInUp {
            from {opacity: 0; transform: translateY(20px);}
            to {opacity: 1; transform: translateY(0);}
        }

        h1 {
            text-align: center;
            color: #efe2ff;
            font-size: 32px;
            margin-bottom: 35px;
            letter-spacing: 0.6px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 22px;
        }

        label {
            font-weight: 600;
            color: #e5d1ff;
            margin-bottom: 5px;
            font-size: 17px;
            display: block;
        }

        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 14px 16px;
            border: 1.5px solid #cdb8ff;
            border-radius: 12px;
            font-size: 17px;
            background: #f2e8ff;
            color: #4d277a;
            transition: all 0.3s ease;
        }

        input::placeholder, textarea::placeholder { color: #7e66a5; }
        input:hover, textarea:hover, select:hover { background: #eee1ff; }

        input:focus,
        textarea:focus,
        select:focus {
            border-color: #9a6bff;
            background: #f8f3ff;
            box-shadow: 0 0 10px rgba(160, 110, 255, 0.3);
            outline: none;
        }

        textarea { resize: vertical; min-height: 100px; }

        .preview {
            text-align: center;
            margin-top: 10px;
        }

        .preview img {
            max-width: 160px;
            border-radius: 15px;
            border: 2px solid rgba(200, 150, 255, 0.4);
            box-shadow: 0 2px 12px rgba(155, 110, 255, 0.3);
            transition: transform 0.3s ease;
        }

        .preview img:hover { transform: scale(1.05); }

        .actions {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 25px;
        }

        button {
            background: linear-gradient(135deg, #a77bff, #7a4bff);
            color: #fff;
            border: none;
            padding: 14px 35px;
            font-size: 17px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(138, 99, 255, 0.35);
        }

        button:hover {
            background: linear-gradient(135deg, #8a63ff, #6d3ce1);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(145, 80, 255, 0.4);
        }

        a {
            text-decoration: none;
            background: rgba(255, 255, 255, 0.2);
            color: #f0e6ff;
            padding: 14px 35px;
            border-radius: 12px;
            font-size: 17px;
            font-weight: 600;
            text-align: center;
            transition: 0.3s ease;
            box-shadow: 0 4px 10px rgba(120, 80, 255, 0.25);
        }

        a:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
        
        /* ============================= */
/*   MODO MÓVIL OPTIMIZADO        */
/* ============================= */
@media (max-width: 768px) {

    body {
        padding: 15px;
        align-items: flex-start;
    }

    .container {
        width: 100%;
        max-width: 100%;
        padding: 25px 20px;
        border-radius: 18px;
    }

    h1 {
        font-size: 24px;
        margin-bottom: 20px;
    }

    label {
        font-size: 16px;
    }

    input,
    textarea,
    select {
        font-size: 18px;
        padding: 14px 16px;
        border-radius: 14px;
    }

    textarea {
        min-height: 140px;
    }

    .preview img {
        max-width: 90%;
        height: auto;
    }

    .actions {
        flex-direction: column;
        gap: 15px;
    }

    button,
    a {
        width: 100%;
        font-size: 18px;
        padding: 16px;
        border-radius: 16px;
    }
}

/* Para celulares pequeños */
@media (max-width: 480px) {

    h1 {
        font-size: 22px;
    }

    label {
        font-size: 15px;
    }

    input,
    textarea,
    select {
        font-size: 17px;
    }
}

    </style>
</head>
<body>
    <div class="container">
        <h1>💜 Editar Producto</h1>

        <!-- enctype es necesario -->
        <form method="post" enctype="multipart/form-data">

            <label>ID:</label>
            <input type="text" name="id_producto" value="<?= htmlspecialchars($producto['ID_PRODUCTO']) ?>" readonly>

            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($producto['NOMBRE']) ?>" required>

            <label>Categoría:</label>
            <input type="text" name="categoria" value="<?= htmlspecialchars($producto['CATEGORIA'] ?? '') ?>">

            <label>Precio de compra:</label>
            <input type="number" step="0.01" name="precio_compra" value="<?= htmlspecialchars($producto['PRECIO_COMPRA']) ?>" required>

            <label>Precio de venta:</label>
            <input type="number" step="0.01" name="precio_venta" value="<?= htmlspecialchars($producto['PRECIO_VENTA']) ?>" required>

            <label>Stock:</label>
            <input type="number" name="stock" value="<?= htmlspecialchars($producto['STOCK']) ?>" required>

            <label>Descripción:</label>
            <textarea name="descripcion"><?= htmlspecialchars($producto['DESCRIPCION'] ?? '') ?></textarea>

            <label>Nueva imagen:</label>
            <input type="file" name="imagen">

            <?php if (!empty($producto['IMAGEN'])): ?>
                <div class="preview">
                    <img src="<?= htmlspecialchars($producto['IMAGEN']) ?>" alt="Imagen del producto">
                </div>
            <?php endif; ?>

            <label>Proveedor:</label>
            <select name="prov_cod" required>
                <?php
                $stmt = $pdo->query("SELECT PROV_COD, NOMBRE FROM proveedor");
                while ($prov = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $selected = ($prov['PROV_COD'] == $producto['PROV_COD']) ? 'selected' : '';
                    echo "<option value='{$prov['PROV_COD']}' $selected>{$prov['NOMBRE']} ({$prov['PROV_COD']})</option>";
                }
                ?>
            </select>

            <div class="actions">
                <button type="submit">💾 Guardar cambios</button>
                <a href="admin_productos.php">⬅️ Volver</a>
            </div>

        </form>
    </div>
</body>
</html>
