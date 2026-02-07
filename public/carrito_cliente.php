<?php
require_once __DIR__ . '/_init.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inicializar carrito
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Eliminar producto del carrito
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    unset($_SESSION['carrito'][$id]);
    header('Location: carrito_cliente.php');
    exit;
}

// Aumentar cantidad
if (isset($_GET['sumar'])) {
    $id = $_GET['sumar'];
    $_SESSION['carrito'][$id]['cantidad']++;
    header("Location: carrito_cliente.php");
    exit;
}

// Disminuir cantidad
if (isset($_GET['restar'])) {
    $id = $_GET['restar'];
    if ($_SESSION['carrito'][$id]['cantidad'] > 1) {
        $_SESSION['carrito'][$id]['cantidad']--;
    }
    header("Location: carrito_cliente.php");
    exit;
}


// Calcular total
$total = 0;
foreach ($_SESSION['carrito'] as $p) {
    $total += $p['precio_venta'] * $p['cantidad'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
        <iframe src="/loryon/public/audio_background.php"
        style="display:none;"
        allow="autoplay">
    </iframe>
<meta charset="UTF-8">
<title>Mi Carrito</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>

    * {
    box-sizing: border-box;
}

html, body {
    width: 100%;
    max-width: 100%;
    overflow-x: hidden;
}

body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #f7f2fc, #faf6fb);
    margin: 0;
    padding: 0;
    color: #333;
}

/* --- Encabezado --- */
header {
    background: linear-gradient(90deg, #b66bff, #e3baff);
    padding: 12px 16px; /* antes 30px */
    color: #fff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    max-width: 100%;
    overflow: hidden;
}

header h1 {
    font-size: 18px;
    max-width: 70%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

header a {
    color: #fff;
    text-decoration: none;
    font-weight: 500;
    background: rgba(255,255,255,0.2);
    padding: 8px 14px;
    border-radius: 8px;
    transition: 0.3s;
}
header a:hover {
    background: rgba(255,255,255,0.35);
}
.qty-controls {
    display: flex;
    align-items: center;
    gap: 8px;
}

.qty-btn {
    background: #b66bff;
    color: white;
    border: none;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    font-size: 18px;
    cursor: pointer;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
}

.qty-btn:hover {
    background: #9d45ff;
}

.qty-number {
    font-weight: 600;
    min-width: 22px;
    text-align: center;
}

table, tr, td {
    max-width: 100%;
    overflow: hidden;
}

.qty-controls {
    max-width: 100%;
    flex-wrap: nowrap;
}

td {
    word-break: break-word;
}

    
/* --- Contenido principal --- */
main {
    max-width: 950px;
    margin: 50px auto;
    background: white;
    padding: 30px;
    border-radius: 16px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    animation: fadeIn 0.5s ease-in-out;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* --- Tabla --- */
table {
    width: 100%;
    border-collapse: collapse;
    overflow: hidden;
    border-radius: 10px;
}
th, td {
    text-align: center;
    padding: 12px;
    border-bottom: 1px solid #eee;
}
th {
    color: #5a008a;
    background-color: #f8f1ff;
    font-weight: 600;
}
td a {
    color: #ff5e5e;
    text-decoration: none;
    font-size: 18px;
    transition: 0.3s;
}
td a:hover {
    transform: scale(1.2);
}

/* --- Total --- */
.total {
    text-align: right;
    font-weight: 600;
    color: #5a008a;
    font-size: 18px;
    margin-top: 20px;
}

/* --- Botón WhatsApp --- */
.btn-whatsapp {
    background: linear-gradient(90deg, #a14dff, #c67aff);
    color: white;
    font-weight: 600;
    padding: 12px 25px;
    border-radius: 12px;
    text-decoration: none;
    display: inline-block;
    transition: 0.3s ease;
    box-shadow: 0 4px 10px rgba(161, 77, 255, 0.3);
    margin-top: 20px;
}
.btn-whatsapp:hover {
    background: linear-gradient(90deg, #912bff, #b15bff);
    box-shadow: 0 6px 14px rgba(145, 43, 255, 0.4);
    transform: translateY(-2px);
}

/* --- Carrito vacío --- */
/* --- Carrito vacío mejorado --- */
.empty-cart {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 60px 20px;
    color: #5a008a;
    font-size: 18px;
    animation: fadeIn 0.8s ease;
}

.empty-cart i {
    font-size: 70px;
    color: #b66bff;
    margin-bottom: 20px;
    animation: float 2.5s ease-in-out infinite;
}

/* Efecto flotante del ícono */
@keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
    100% { transform: translateY(0px); }
}

.empty-cart p {
    margin: 0;
    color: #666;
    font-size: 17px;
    max-width: 300px;
}

.empty-cart .btn-whatsapp {
    margin-top: 25px;
    background: linear-gradient(90deg, #a14dff, #c67aff);
    color: white;
    font-weight: 600;
    padding: 12px 25px;
    border-radius: 12px;
    text-decoration: none;
    display: inline-block;
    transition: 0.3s ease;
    box-shadow: 0 4px 10px rgba(161, 77, 255, 0.3);
}
.empty-cart .btn-whatsapp:hover {
    background: linear-gradient(90deg, #912bff, #b15bff);
    transform: translateY(-2px);
    box-shadow: 0 6px 14px rgba(145, 43, 255, 0.4);
}


/* --- Responsive --- */
@media (max-width: 768px) {

    main {
        padding: 15px;
        margin: 15px;
    }

    table, thead, tbody, tr {
        display: block;
        width: 100%;
    }

    thead {
        display: none;
    }

    tr {
        background: #faf6ff;
        margin-bottom: 15px;
        border-radius: 12px;
        padding: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border: none;
        padding: 8px 10px;
        font-size: 14px;
        width: 100%;
    }

    td::before {
        content: attr(data-label);
        font-weight: 600;
        color: #5a008a;
        min-width: 90px;
    }

    .total {
        text-align: center;
        font-size: 20px;
    }

    .btn-whatsapp {
        width: 100%;
        text-align: center;
    }
}

</style>
</head>
<body>
<header>
    <h1>🛍️ Mi carrito</h1>
    <nav><a href="catalogo_cliente.php">⬅ Volver al catálogo</a></nav>
</header>

<main>
<?php if (empty($_SESSION['carrito'])): ?>
  <div class="empty-cart">
    <i>🛍️</i>
    <p>Tu carrito está vacío<br>¡Agrega algo bonito! 💜</p>
    <a href="catalogo_cliente.php" class="btn-whatsapp">Explorar productos</a>
  </div>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($_SESSION['carrito'] as $id => $item): ?>
            <tr>
                <td data-label="Producto"><?= htmlspecialchars($item['nombre']) ?></td>
                <td data-label="Precio">$<?= number_format($item['precio_venta'], 0, ',', '.') ?></td>
                <td data-label="Cantidad">
    <div class="qty-controls">
        <a href="?restar=<?= $id ?>" class="qty-btn">−</a>
        <span class="qty-number"><?= $item['cantidad'] ?></span>
        <a href="?sumar=<?= $id ?>" class="qty-btn">+</a>
    </div>
</td>

                <td data-label="Subtotal">$<?= number_format($item['precio_venta'] * $item['cantidad'], 0, ',', '.') ?></td>
                <td data-label="Quitar"><a href="?eliminar=<?= $id ?>" onclick="return confirm('¿Quitar este producto?')">✖</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p class="total">Total: $<?= number_format($total, 0, ',', '.') ?></p>

    <?php
    $whatsapp = "573222460867";
    $mensaje = "¡Hola! Quiero comprar:%0A%0A";
    foreach ($_SESSION['carrito'] as $item) {
        $mensaje .= "• " . urlencode($item['nombre']) . " x" . $item['cantidad'] . " - $" . number_format($item['precio_venta'], 0, ',', '.') . "%0A";
    }
    $mensaje .= "%0ATotal: $" . number_format($total, 0, ',', '.');
    $mensaje .= "%0A%0A¿Está disponible?";
    $whatsappUrl = "https://wa.me/$whatsapp?text=$mensaje";
    ?>
    <div style="text-align: right;">
        <a href="<?= $whatsappUrl ?>" target="_blank" class="btn-whatsapp">💜 Finalizar compra por WhatsApp</a>
    </div>
<?php endif; ?>
</main>
</body>
</html>
