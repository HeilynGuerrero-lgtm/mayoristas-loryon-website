<?php
require_once __DIR__ . '/_init.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../app/helpers/auth.php';
require_once __DIR__ . '/../app/models/usuario.php';

requireLogin();
requireAdmin();

// Corregir posible falta de la sesión user_id
$currentUserId = $_SESSION['user_id'] ?? null;

$id = $_GET['id'] ?? null;
if (!$id) die("ID no válido");

$model = new Usuario($pdo);
$usuario = $model->find($id);

if (!$usuario) die("Usuario no encontrado");

// Permitir editar solo su propio perfil, o si es admin general
if ($currentUserId != $id && !isAdmin()) {
    die("No autorizado");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $model->update($id, [
        'nombre' => $_POST['nombre'],
        'telefono' => $_POST['telefono'],
        'email' => $_POST['email']
    ]);

    if (!empty($_POST['clave'])) {
        $hashed = password_hash($_POST['clave'], PASSWORD_BCRYPT);
        $model->updatePassword($id, $hashed);
    }

    header("Location: admin_equipo.php");
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Administrador</title>
    <style>
        body {
            font-family: "Poppins", Arial, sans-serif;
            background: #faf6fb;
            margin: 0;
            padding: 0;
            color: #333;
        }
        html, body {
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
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

        header h1 { margin: 0; font-size: 22px; font-weight: 600; }
        header a {
            color: #4b006e;
            text-decoration: none;
            padding: 6px 10px;
            font-weight: 500;
            border-radius: 6px;
        }
        header a:hover { background: rgba(255,255,255,0.4); }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            padding: 25px;
            border-radius: 14px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        }

        h2 {
            color: #5a008a;
            margin-top: 0;
            font-size: 20px;
            font-weight: 600;
        }

        label {
            display: block;
            font-weight: 600;
            margin-top: 15px;
            color: #5a008a;
        }

        input {
            width: 100%;
            max-width: 100%;
            padding: 14px;
            border-radius: 10px;
            border: 1.5px solid #d8c3ff;
            margin-top: 6px;
            font-size: 16px;
            box-sizing: border-box;
        }


        button {
            background: #c67aff;
            color: #fff;
            padding: 12px 18px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            margin-top: 25px;
            font-weight: 600;
            width: 100%;
            transition: background 0.2s;
        }
        button:hover { background: #a950ff; }

        .volver {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: #6a00a5;
            font-weight: 600;
            text-decoration: none;
        }
        .volver:hover { text-decoration: underline; }
        
        @media (max-width: 768px) {

    header {
        padding: 18px;
        text-align: center;
    }

    header h1 {
        font-size: 24px;
    }

    .container {
        margin: 12px;
        padding: 22px;
        border-radius: 18px;
    }

    h2 {
        font-size: 22px;
        text-align: center;
    }

    label {
        font-size: 16px;
    }

    input {
        font-size: 18px;
        padding: 16px;
        border-radius: 14px;
    }

    button {
        font-size: 18px;
        padding: 18px;
        border-radius: 16px;
    }

    .volver {
        font-size: 17px;
    }
}

    </style>
</head>
<body>

<header>
    <h1>✏ Editar Administrador</h1>
</header>

<div class="container">
    <h2>Actualizar información</h2>

    <form method="post">

        <label>Nombre completo:</label>
        <input type="text" name="nombre"
               value="<?= htmlspecialchars($usuario['NOMBRE_COMPLETO']) ?>" required>

        <label>Teléfono:</label>
        <input type="text" name="telefono"
               value="<?= htmlspecialchars($usuario['TELEFONO']) ?>">

        <label>Email:</label>
        <input type="email" name="email"
               value="<?= htmlspecialchars($usuario['EMAIL']) ?>" required>

        <label>Nueva contraseña (opcional):</label>
        <input type="password" name="clave" placeholder="••••••">

        <button type="submit">Guardar cambios</button>
    </form>

    <a href="admin_equipo.php" class="volver">⬅ Volver</a>
</div>

</body>
</html>
