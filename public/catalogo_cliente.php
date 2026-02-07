<?php
require_once __DIR__ . '/_init.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../app/helpers/auth.php';
require_once __DIR__ . '/../app/models/producto.php';

// Modelo
$model = new Producto($pdo);

// Obtener categorías
$categorias = $model->obtenerCategorias();

// Capturar categoría seleccionada
$categoriaSeleccionada = $_GET['categoria'] ?? '';

// Si seleccionó una categoría → filtrar
if (!empty($categoriaSeleccionada)) {
    $productos = $model->obtenerPorCategoria($categoriaSeleccionada);
} else {
    $productos = $model->all();
}
?>

<!doctype html>
<html lang="es">
<head>
      <iframe src="/public/audio_background.php"
        style="display:none;"
        allow="autoplay">
    </iframe>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="utf-8">
<title>Loryon - Catálogo</title>

<style>
body {
  font-family: "Poppins", Arial, sans-serif;
  background: #faf6fb;
  color: #333;
  margin: 0;
  padding: 0;
}
    html, body {
  max-width: 100%;
  overflow-x: hidden;
}


/* === Header === */
header {
  background: linear-gradient(90deg, #dcb0ff, #f3d1ff);
  padding: 15px 30px;
  color: #4b006e;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 20px;
  flex-wrap: wrap;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

header h1 {
  margin: 0;
  font-size: 22px;
  font-weight: 600;
}

header nav {
  display: flex;
  align-items: center;
  gap: 15px;
}

header a {
  color: #4b006e;
  text-decoration: none;
  font-weight: 500;
  padding: 6px 10px;
  border-radius: 6px;
  transition: background 0.2s;
}

header a:hover {
  background: rgba(255,255,255,0.4);
}

/* === Sidebar (Categorías) === */
main {
  display: flex;
  gap: 25px;
  padding: 25px;
}

.sidebar {
  width: 220px;
  background: #ffffff;
  border-right: 1px solid #e5c9ff;
  padding: 20px;
  height: calc(100vh - 80px);
  position: sticky;
  top: 0;
  box-shadow: 2px 0 8px rgba(0,0,0,0.05);
  border-radius: 12px;
}

.sidebar h3 {
  color: #6a0099;
  margin-bottom: 12px;
  font-size: 18px;
}

.sidebar a {
  display: block;
  padding: 8px 12px;
  margin-bottom: 6px;
  background: #f5e4ff;
  border-radius: 8px;
  text-decoration: none;
  color: #4b006e;
  transition: 0.2s;
}

.sidebar a:hover,
.sidebar a.active {
  background: #dca6ff;
  color: white;
}

/* === Catálogo === */
.catalogo {
  flex-grow: 1;
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
  gap: 25px;
  padding: 10px;
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
  top: 50%;
  left: 50%;
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

.producto:hover .tooltip {
  visibility: visible;
  opacity: 1;
  transform: translate(-50%, -50%) scale(1);
}

/* Modal móvil */
.modal {
  display: none;
  position: fixed;
  z-index: 9999;
  left: 0; top: 0;
  width: 100%; height: 100%;
  background-color: rgba(0,0,0,0.45);
  justify-content: center;
  align-items: center;
  padding: 20px;
}

.modal-content {
  background-color: #fff;
  border-radius: 16px;
  padding: 20px 25px;
  max-width: 90%;
  color: #333;
  box-shadow: 0 6px 20px rgba(0,0,0,0.3);
}

.close {
  position: absolute;
  top: 8px;
  right: 12px;
  font-size: 22px;
  font-weight: bold;
  color: #a950ff;
  cursor: pointer;
}

footer {
  text-align: center;
  padding: 15px;
  font-size: 13px;
  color: #777;
  background-color: #f8f3fb;
  border-top: 1px solid #e0caff;
}
    
    /* ============================= */
/* ===== MODO MÓVIL ===== */
/* ============================= */
@media (max-width: 768px) {

  /* HEADER */
  header {
    flex-direction: column;
    align-items: stretch;
    padding: 10px;
    gap: 10px;
  }

  header h1 {
    text-align: center;
    font-size: 18px;
  }

  header nav {
    justify-content: center;
    flex-wrap: wrap;
    gap: 8px;
  }

  header nav a {
    font-size: 14px;
    padding: 6px 10px;
  }

  /* BOTÓN BUSCAR */
  header a[href="busqueda.php"] {
    width: 100%;
    text-align: center;
    font-size: 14px;
    padding: 10px;
  }

  /* MAIN */
  main {
    flex-direction: column;
    padding: 10px;
  }

  /* SIDEBAR → se vuelve barra horizontal */
  .sidebar {
    width: 100%;
    height: auto;
    position: static;
    display: flex;
    overflow-x: auto;
    gap: 10px;
    padding: 10px;
    border-right: none;
    border-bottom: 1px solid #e5c9ff;
    border-radius: 10px;
  }

  .sidebar h3 {
    display: none;
  }

  .sidebar a {
    flex: 0 0 auto;
    white-space: nowrap;
    font-size: 14px;
    padding: 8px 14px;
  }

  /* CATÁLOGO */
  .catalogo {
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    padding: 5px;
  }

  .producto img {
    height: 140px;
  }

  .producto h3 {
    font-size: 14px;
  }

  .precio,
  .stock {
    font-size: 13px;
  }

  .btn-carrito {
    font-size: 14px;
    padding: 8px;
  }

  /* TOOLTIP no sirve en móvil */
  .tooltip {
    display: none;
  }
}
    @media (max-width: 768px) {

  /* Modal de descripción */
  .modal-content {
    max-width: 95%;
    max-height: 70vh;
    padding: 15px;
    border-radius: 14px;
    font-size: 14px;
    overflow-y: auto;
  }

  .modal-content h4 {
    font-size: 16px;
    margin-bottom: 8px;
    text-align: center;
  }

  #descText {
    font-size: 14px;
    line-height: 1.4;
    text-align: justify;
  }

  .close {
    top: 6px;
    right: 10px;
    font-size: 20px;
  }
}
@media (max-width: 768px) {
  .tooltip {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
  }
}
@media (max-width: 768px) {

  .modal-content {
    width: 90%;
    max-width: 400px;
    max-height: 60vh;
    padding: 15px;
    border-radius: 12px;
    overflow-y: auto;
    font-size: 14px;
  }

  #descText {
    font-size: 14px;
    line-height: 1.4;
  }

  .modal-content h4 {
    font-size: 16px;
    text-align: center;
  }
}

.modal * {
  max-width: 100%;
  box-sizing: border-box;
}
    
    /* 🔒 Arreglo definitivo de desbordamiento en móviles */
@media (max-width: 768px) {

  .modal {
    padding: 10px;
  }

  .modal-content {
    width: calc(100vw - 20px) !important;
    max-width: 100vw !important;
    min-width: 0 !important;
    box-sizing: border-box;
    position: relative;
    padding: 16px;
    max-height: 75vh;
    overflow-y: auto;
    border-radius: 14px;
  }

  .close {
    position: absolute;
    right: 10px;
    top: 10px;
  }

  #descText {
    max-width: 100%;
    width: 100%;
    word-break: break-word;
    overflow-wrap: break-word;
    white-space: normal;
    line-height: 1.5;
    font-size: 14px;
  }
}

/* 🔥 Elimina cualquier ancho forzado heredado */
.modal-content, 
.modal-content * {
  max-width: 100% !important;
  box-sizing: border-box;
}


</style>
</head>
<body>

<header>
  <h1>Loryon - Catálogo</h1>
<?php include __DIR__ . '/components/audio_player.php'; ?>
<a href="busqueda.php" 
       style="
            padding: 8px 20px; 
            background: white; 
            color: #4b0082; 
            border-radius: 8px; 
            text-decoration: none; 
            font-size: 16px;
            font-weight: bold;
            transition: 0.2s;
       "
       onmouseover="this.style.background='#e6d9ff'"
       onmouseout="this.style.background='white'"
    >
        ¿Desea buscar un producto?
    </a>

<nav>
    <a href="pagina_principal.php">🏠 Página principal</a>
    <a href="carrito_cliente.php">🛒 Ver carrito</a>
  </nav>
</header>

<main>

  <!-- === Sidebar Categorías === -->
  <aside class="sidebar">
    <h3>Categorías</h3>

    <a href="catalogo_cliente.php" class="<?= empty($categoriaSeleccionada) ? 'active' : '' ?>">
      ⭐ Todos
    </a>

    <?php foreach ($categorias as $c): ?>
      <?php $cat = htmlspecialchars($c['CATEGORIA']); ?>
      <a href="catalogo_cliente.php?categoria=<?= urlencode($cat) ?>"
         class="<?= ($categoriaSeleccionada === $cat) ? 'active' : '' ?>">
        <?= $cat ?>
      </a>
    <?php endforeach; ?>
  </aside>

  <!-- === Productos === -->
  <div class="catalogo">
    <?php foreach ($productos as $p): ?>
      <?php 
        $img = trim($p['imagen'] ?? '');
        if (empty($img)) {
            $img = 'https://via.placeholder.com/200x150?text=Sin+imagen';
        } else {
            $img = '/public/' . ltrim($img, '/');
        }
      ?>

      <div class="producto" data-descripcion="<?= htmlspecialchars($p['descripcion'] ?? 'Sin descripción') ?>">
        <img src="<?= $img ?>" loading="lazy" alt="<?= htmlspecialchars($p['nombre']) ?>">
        <div class="tooltip"><?= htmlspecialchars($p['descripcion'] ?? 'Sin descripción') ?></div>
        <h3><?= htmlspecialchars($p['nombre']) ?></h3>
        <p class="precio">$<?= number_format($p['precio_venta'], 0, ',', '.') ?></p>

        <button class="btn-carrito" onclick="location.href='agregar_carrito_cliente.php?id=<?= urlencode($p['id_producto']) ?>'">
          🛒 Agregar al carrito
        </button>
      </div>

    <?php endforeach; ?>
  </div>

</main>

<!-- Modal móvil -->
<div id="descModal" class="modal">
  <div class="modal-content">
    <span class="close" id="cerrarModal">&times;</span>
    <h4>Descripción</h4>
    <p id="descText"></p>
  </div>
</div>

<footer>
  © <?= date('Y') ?> Loryon - Todos los derechos reservados 💜
</footer>

<script>
const modal = document.getElementById("descModal");
const modalText = document.getElementById("descText");
const closeBtn = document.getElementById("cerrarModal");

document.querySelectorAll(".producto img").forEach(img => {
  img.addEventListener("click", () => {
    if (window.innerWidth <= 768) {
      const desc = img.parentElement.dataset.descripcion;
      modalText.textContent = desc;
      modal.style.display = "flex";
    }
  });
});

closeBtn.onclick = () => modal.style.display = "none";
window.onclick = e => { if (e.target === modal) modal.style.display = "none"; };
</script>

</body>
</html>
