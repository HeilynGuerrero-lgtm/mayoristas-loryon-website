<?php
require_once __DIR__ . '/_init.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../app/helpers/auth.php';

requireLogin();
requireAdmin();
?>
<!doctype html>
<html lang="es">
<head>
    <iframe src="/public/audio_background.php"
        style="display:none;"
        allow="autoplay">
    </iframe>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loryon - Panel Administrativo</title>

    <style>
        body {
            font-family: "Poppins", sans-serif;
            background: #faf6fb;
            margin: 0;
            padding: 0;
            color: #4b006e;
        }

        header {
            background: linear-gradient(90deg, #dcb0ff, #f3d1ff);
            padding: 15px 30px;
            color: #4b006e;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            flex-wrap: wrap;
        }

        header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
        }

        header nav a {
            color: #4b006e;
            text-decoration: none;
            font-weight: 500;
            padding: 6px 10px;
            border-radius: 6px;
            transition: background 0.2s;
        }

        header nav a:hover {
            background: rgba(255,255,255,0.4);
        }

        /* Dashboard grid */
        .dashboard {
            max-width: 1100px;
            margin: 50px auto;
            display: grid;
            gap: 25px;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            padding: 20px;
        }

        .card {
            background: white;
            border-radius: 16px;
            padding: 30px 20px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border: 1px solid #e8d9ff;
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.18);
        }

        .icon {
            font-size: 45px;
            margin-bottom: 12px;
        }

        footer {
            text-align: center;
            padding: 15px;
            font-size: 13px;
            color: #777;
            background: #f8f3fb;
            border-top: 1px solid #e0caff;
            margin-top: 40px;
        }
    </style>
</head>
<body>

<header>
    <h1>Panel Administrativo</h1>

    <nav>
        <a href="logout.php">🚪 Salir</a>
    </nav>
</header>

<main>
    <div class="dashboard">

        <div class="card" onclick="location.href='admin_productos.php'">
            <div class="icon">📦</div>
            <h2>Panel de productos</h2>
            <p>Gestiona los productos del catálogo.</p>
        </div>

        <div class="card" onclick="location.href='admin_equipo.php'">
            <div class="icon">👥</div>
            <h2>Equipo administrativo</h2>
            <p>Gestiona administradores y usuarios internos.</p>
        </div>
<!--
        <div class="card" onclick="location.href='backup.php'">
            <div class="icon">💾</div>
            <h2>Crear Backup</h2>
            <p>Generar una copia de seguridad de la base de datos.</p>
        </div>

        <div class="card" onclick="location.href='restore.php'">
            <div class="icon">📂</div>
            <h2>Restaurar Backup</h2>
            <p>Sube y restaura una copia de seguridad existente.</p>
        </div>
-->
    </div>
</main>

<footer>
    © <?= date('Y') ?> Loryon - Panel Administrativo 💜
</footer>

</body>
</html>
