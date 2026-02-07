<?php
require_once __DIR__ . '/_init.php';
require_once __DIR__ . '/../app/helpers/auth.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../app/models/producto.php';

requireLogin();
if (!isAdmin()) { 
    header('Location: /public/'); 
    exit; 
}

$model = new Producto($pdo);
$productos = $model->all();
?>
<!doctype html>
<html lang="es">
<head>
    <iframe src="/public/audio_background.php"
        style="display:none;"
        allow="autoplay">
    </iframe>
<meta charset="utf-8">
<title>Panel Admin - Productos</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>

    /* --- Estilos generales --- */
    body {
        font-family: "Segoe UI", Arial, sans-serif;
        background-color: #f3e9ff;
        margin: 0;
        padding: 15px;
        color: #3a2b4c;
    }

    /* --- Encabezado --- */
    header {
        background: linear-gradient(135deg, #b37bff, #d9c2ff);
        padding: 18px 25px;
        border-radius: 12px;
        color: #fff;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 3px 12px rgba(0,0,0,0.15);
        margin-bottom: 25px;
    }

    header h1 {
        margin: 0;
        font-size: 24px;
        letter-spacing: 0.5px;
    }

    .btn-volver {
        background-color: #6f2dbd;
        padding: 10px 18px;
        border-radius: 8px;
        color: #fff;
        text-decoration: none;
        font-weight: bold;
        transition: 0.2s;
    }
    .btn-volver:hover {
        background-color: #551a99;
    }

    /* --- Enlaces secundarios --- */
    .crear-btn {
        display: inline-block;
        background-color: #8246d9;
        color: white;
        padding: 10px 14px;
        border-radius: 8px;
        margin-bottom: 15px;
        font-weight: 600;
        text-decoration: none;
        transition: 0.2s;
    }
    .crear-btn:hover {
        background-color: #6a32b8;
    }

    /* --- Tabla --- */
.table-container {
    width: 100%;
    overflow-x: auto;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.12);
}

table {
    width: 100%;
    border-collapse: collapse;
}


    th {
        background-color: #e9d6ff;
        padding: 12px;
        font-size: 0.95rem;
        color: #4a2974;
        text-transform: uppercase;
    }

    td {
        padding: 12px;
        text-align: center;
        color: #3d2a50;
    }

    tr:nth-child(even) {
        background-color: #f7f1ff;
    }

    img {
        width: 75px;
        height: 75px;
        border-radius: 10px;
        object-fit: cover;
        transition: 0.2s;
    }

    img:hover {
        transform: scale(1.05);
        box-shadow: 0 2px 8px rgba(0,0,0,0.25);
    }

    /* --- Inputs de filtro --- */
    .filtro {
        margin-top: 6px;
        padding: 6px;
        border: 1px solid #caaaff;
        border-radius: 6px;
        width: 90%;
        font-size: 0.9rem;
        text-align: center;
        background: #f8f4ff;
        color: #4a2a78;
        transition: 0.2s;
    }

    .filtro:focus {
        outline: none;
        border-color: #9d5cff;
        box-shadow: 0 0 5px #c8abff;
    }

    /* --- Acciones --- */
    td a {
        color: #7427b8;
        font-weight: bold;
        text-decoration: none;
    }

    td a:hover {
        text-decoration: underline;
    }

    /* --- Responsividad --- */
    @media (max-width: 768px) {
        header {
            flex-direction: column;
            text-align: center;
            gap: 12px;
        }

        header h1 {
            font-size: 20px;
        }

        .btn-volver {
            width: 100%;
            text-align: center;
        }

        .crear-btn {
            width: 100%;
            display: block;
            text-align: center;
        }

        th, td {
            padding: 8px;
            font-size: 0.85rem;
        }

        img {
            width: 60px;
            height: 60px;
        }
    }

    @media (max-width: 480px) {
        body {
            padding: 10px;
        }

        th, td {
            font-size: 0.78rem;
        }

        img {
            width: 50px;
            height: 50px;
        }

        .filtro {
            font-size: 0.75rem;
        }
    }
@media (max-width: 768px) {
    table {
        min-width: 100%;
    }

    .table-container {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
}
    html, body {
    max-width: 100%;
    overflow-x: hidden;
}


</style>
</head>

<body>

<header>
    <h1>Panel de Productos</h1>
    <a href="/public/index.php" class="btn-volver">⬅ Volver</a>
</header>

<a class="crear-btn" href="/public/admin_productos_create.php">➕ Crear producto</a>

<div class="table-container">
<table id="tablaProductos">
    <thead>
        <tr>
            <th>ID<br><input class="filtro" type="text" placeholder="Filtrar ID"></th>
            <th>Nombre<br><input class="filtro" type="text" placeholder="Filtrar nombre"></th>
            <th>Categoría<br><input class="filtro" type="text" placeholder="Filtrar categoría"></th>
            <th>Foto</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Acciones</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($productos as $p): ?>
        <tr>
            <td><?= htmlspecialchars($p['id_producto']) ?></td>
            <td><?= htmlspecialchars($p['nombre']) ?></td>
            <td><?= htmlspecialchars($p['categoria'] ?? 'Sin categoría') ?></td>
            <td>
                <?php 
                $img = trim($p['imagen']);
                $img = $img ? "/public/" . ltrim($img, "/") : "https://via.placeholder.com/80?text=Sin+foto";
                ?>
                <img src="<?= $img ?>" loading="lazy" onerror="this.src='https://via.placeholder.com/80?text=Sin+foto';">
            </td>
            <td>$<?= number_format($p['precio_venta'], 2) ?></td>
            <td><?= htmlspecialchars($p['stock']) ?></td>
            <td>
                <a href="edit_producto.php?id=<?= urlencode($p['id_producto']) ?>">Editar</a> |
                <a href="delete_producto.php?id=<?= urlencode($p['id_producto']) ?>" onclick="return confirm('¿Eliminar este producto?');">Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const filtros = document.querySelectorAll(".filtro");
    const filas = document.querySelectorAll("#tablaProductos tbody tr");

    filtros.forEach((input, colIndex) => {
        input.addEventListener("input", () => {
            const valores = Array.from(filtros).map(f => f.value.toLowerCase());

            filas.forEach(fila => {
                const celdas = fila.querySelectorAll("td");
                let visible = true;

                valores.forEach((valor, i) => {
                    if (valor && celdas[i] && !celdas[i].textContent.toLowerCase().includes(valor)) {
                        visible = false;
                    }
                });

                fila.style.display = visible ? "" : "none";
            });
        });
    });
});
</script>

</body>
</html>
