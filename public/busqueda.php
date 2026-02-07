<?php
require_once __DIR__ . '/_init.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../app/helpers/auth.php';
require_once __DIR__ . '/../app/models/producto.php';

// Modelo
$model = new Producto($pdo);

// Capturar búsqueda
$busqueda = $_GET['buscar'] ?? '';

// Buscar por nombre
$productos = $model->buscar($busqueda);
?>
<!doctype html>
<html lang="es">
<head>
    <iframe src="/public/audio_background.php"
        style="display:none;"
        allow="autoplay"></iframe>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="utf-8">
<title>Resultados de búsqueda</title>

<style>

body {
  font-family: "Poppins", Arial, sans-serif;
  background: #faf6fb;
  color: #333;
  margin: 0;
  padding: 0;
}

/* Header */
header {
  background: linear-gradient(90deg, #dcb0ff, #f3d1ff);
  padding: 12px 16px;
  color: #4b006e;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
  flex-wrap: nowrap;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  max-width: 100%;
  overflow: hidden;
}


header h1 {
  font-size: 18px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 40%;
}


header nav a {
  color: #4b006e;
  text-decoration: none;
  font-weight: 500;
  padding: 6px 10px;
  border-radius: 6px;
}

header nav a:hover {
  background: rgba(255,255,255,0.4);
}

/* Contenedor */
main {
  padding: 25px;
}

/* Resultados */
.titulo-busqueda {
  font-size: 20px;
  margin-bottom: 20px;
  color: #6a0099;
  font-weight: bold;
}

.catalogo {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
  gap: 25px;
}

.producto {
  background: #fff;
  border-radius: 14px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.08);
  padding: 18px;
  text-align: center;
  transition: all 0.2s ease;
  border: 1px solid #f2e6ff;
  position: relative;
}

.producto:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 16px rgba(0,0,0,0.15);
}

.producto img {
  width: 100%;
  height: 180px;
  object-fit: cover;
  border-radius: 12px;
  margin-bottom: 12px;
  cursor: pointer;
}

/* Tooltip */
.tooltip {
  visibility: hidden;
  opacity: 0;
  position: absolute;
  top: 50%; left: 50%;
  transform: translate(-50%, -50%) scale(0.9);
  background: rgba(255, 255, 255, 0.97);
  color: #4b006e;
  border-radius: 12px;
  box-shadow: 0 6px 25px rgba(0, 0, 0, 0.25);
  padding: 15px 20px;
  width: 240px;
  z-index: 50;
  transition: all 0.25s ease;
  border: 1px solid #d5a7ff;
  text-align: justify;
  font-size: 0.9rem;
}
.search-bar {
  flex: 1;
  display: flex;
}

.search-bar input {
  width: 100%;
  padding: 10px 14px;
  border-radius: 12px;
  border: 1px solid #d5a7ff;
  font-size: 14px;
}

.producto:hover .tooltip {
  visibility: visible;
  opacity: 1;
  transform: translate(-50%, -50%) scale(1);
}

/* Botón agregar */
.btn-carrito {
  background: #dca6ff;
  border: none;
  color: white;
  padding: 10px 14px;
  border-radius: 10px;
  width: 100%;
  font-size: 14px;
  cursor: pointer;
  font-weight: 600;
  transition: 0.2s;
}
* {
  box-sizing: border-box;
}

html, body {
  width: 100%;
  max-width: 100%;
  overflow-x: hidden;
}

.btn-carrito:hover {
  background: #c782ff;
}

/* Footer */
footer {
  text-align: center;
  padding: 15px;
  font-size: 13px;
  color: #777;
  background-color: #f8f3fb;
  border-top: 1px solid #e0caff;
}

    @media (max-width: 768px) {
  .search-bar {
    flex: 1;
  }

  .search-bar input {
    height: 48px;
    font-size: 18px;
    padding: 12px 16px;
    border-radius: 16px;
  }
}

</style>
</head>
<body>

<header>
    <h1>Loryon</h1>

    <form action="busqueda.php" method="GET" class="search-bar">
    <input type="text"
        name="buscar"
        placeholder="🔍 Buscar productos..."
        value="<?= htmlspecialchars($busqueda) ?>">
</form>


    <nav>
        <a href="pagina_principal.php">🏠</a>
        <a href="catalogo_cliente.php">📦</a>
        <a href="carrito_cliente.php">🛒</a>
    </nav>
</header>

<main>

<h2 class="titulo-busqueda">
    Resultados para: "<?= htmlspecialchars($busqueda) ?>"
</h2>

<div class="catalogo">

<?php if (empty($productos)): ?>
    <p style="font-size:18px; color:#a24bcf;">No se encontraron productos.</p>
<?php endif; ?>

<?php foreach ($productos as $p): ?>
    <?php 
        $img = trim($p['imagen'] ?? '');
        $img = empty($img) 
            ? 'https://via.placeholder.com/200x150?text=Sin+imagen'
            : '/public/' . ltrim($img, '/');
    ?>

    <div class="producto">
        <img src="<?= $img ?>" loading="lazy" alt="<?= htmlspecialchars($p['nombre']) ?>">

        <div class="tooltip"><?= htmlspecialchars($p['descripcion'] ?? 'Sin descripción') ?></div>

        <h3><?= htmlspecialchars($p['nombre']) ?></h3>
        <p class="precio">$<?= number_format($p['precio_venta'], 0, ',', '.') ?></p>
        <p class="stock">Stock: <?= htmlspecialchars($p['stock']) ?></p>

        <button class="btn-carrito"
            onclick="location.href='agregar_carrito_cliente.php?id=<?= urlencode($p['id_producto']) ?>'">
            🛒 Agregar al carrito
        </button>
    </div>

<?php endforeach; ?>

</div>

</main>

<footer>
    © <?= date('Y') ?> Loryon - Todos los derechos reservados 💜
</footer>

</body>
</html>
