<?php
require_once __DIR__ . '/_init.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../app/helpers/auth.php';
require_once __DIR__ . '/../app/models/usuario.php';

requireLogin();
requireAdmin();

$model = new Usuario($pdo);
$usuarios = $model->all();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <iframe src="/public/audio_background.php"
        style="display:none;"
        allow="autoplay">
    </iframe>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Equipo Administrativo</title>
    <style>
        body {
            font-family: "Poppins", Arial, sans-serif;
            background: #faf6fb;
            color: #333;
            margin: 0;
            padding: 0;
        }

        header {
            background: linear-gradient(90deg, #dcb0ff, #f3d1ff);
            padding: 15px 30px;
            color: #4b006e;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
        }

        header a {
            color: #4b006e;
            text-decoration: none;
            font-weight: 500;
            padding: 6px 10px;
            border-radius: 6px;
            transition: background .2s;
        }

        header a:hover {
            background: rgba(255,255,255,0.4);
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            padding: 25px;
            border-radius: 14px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        }

        h2 {
            color: #5a008a;
            margin-top: 0;
        }

        .btn-add {
            display: inline-block;
            background: #c67aff;
            padding: 10px 15px;
            color: #fff;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: background .2s;
        }
        .btn-add:hover { background: #a950ff; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
        }

        th {
            background: #e9d5ff;
            color: #4b006e;
            padding: 12px;
            font-size: 15px;
            text-align: left;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #f0e1ff;
        }

        tr:hover {
            background: #faf0ff;
        }

        a.action {
            color: #6a00a5;
            font-weight: 600;
            text-decoration: none;
        }
        a.action:hover {
            text-decoration: underline;
        }

        .volver {
            display: inline-block;
            margin-top: 25px;
            color: #6a00a5;
            font-weight: 600;
            text-decoration: none;
        }
        .volver:hover { text-decoration: underline; }

        @media(max-width: 600px){
            table, tr, td, th { font-size: 13px; }
        }
        
        /* ============================== */
/*     MODO MÓVIL REAL            */
/* ============================== */
@media (max-width: 768px) {

    header {
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 12px;
        padding: 15px;
    }

    header h1 {
        font-size: 20px;
    }

    header nav {
        width: 100%;
        display: flex;
        justify-content: center;
        gap: 25px;
    }

    .container {
        margin: 15px;
        padding: 15px;
        width: auto;
    }

    .btn-add {
        width: 100%;
        text-align: center;
        font-size: 16px;
        padding: 14px;
        margin-bottom: 15px;
    }

    table {
        border: 0;
    }

    table thead {
        display: none;
    }

    table tr {
        display: block;
        margin-bottom: 15px;
        border-radius: 12px;
        background: #f8ecff;
        padding: 12px;
        box-shadow: 0 3px 8px rgba(0,0,0,.05);
    }

    table td {
        display: flex;
        justify-content: space-between;
        padding: 10px 8px;
        border: none;
        border-bottom: 1px solid #e5cfff;
        font-size: 15px;
    }

    table td:last-child {
        border-bottom: none;
        justify-content: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    table td::before {
        content: attr(data-label);
        font-weight: 600;
        color: #5a008a;
    }
}

    </style>
</head>
<body>

<header>
    <h1>👥 Equipo Administrativo</h1>
</header>

<div class="container">

    <a href="admin_equipo_create.php" class="btn-add">➕ Crear nuevo administrador</a>

    <table>
        <tr>
            <th>ID</th>
            <th>Nombre completo</th>
            <th>Teléfono</th>
            <th>Email</th>
            <th>Acciones</th>
        </tr>

        <?php foreach ($usuarios as $u): ?>
        <tr>
            <td data-label="ID"><?= $u['ID_USUARIO'] ?></td>
            <td data-label="Nombre"><?= htmlspecialchars($u['NOMBRE_COMPLETO']) ?></td>
            <td data-label="Teléfono"><?= htmlspecialchars($u['TELEFONO']) ?></td>
            <td data-label="Email"><?= htmlspecialchars($u['EMAIL']) ?></td>
            <td data-label="">

                <a class="action" href="admin_equipo_edit.php?id=<?= $u['ID_USUARIO'] ?>">✏ Editar</a>

                <?php if ($u['ID_USUARIO'] != ($_SESSION['user_id'] ?? null)): ?>
                    | <a class="action" 
                         href="admin_equipo_delete.php?id=<?= $u['ID_USUARIO'] ?>"
                         onclick="return confirm('¿Eliminar administrador?')">🗑 Eliminar</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <a href="index.php" class="volver">⬅ Volver</a>

</div>

</body>
</html>
