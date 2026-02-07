<?php
require_once __DIR__ . '/_init.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../app/helpers/auth.php';
require_once __DIR__ . '/../app/models/usuario.php';

requireLogin();
requireAdmin();

$model = new Usuario($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = [
        'nombre'   => $_POST['nombre'],
        'telefono' => $_POST['telefono'],
        'email'    => $_POST['email'],
        'clave'    => password_hash($_POST['clave'], PASSWORD_BCRYPT),
        'id_rol'   => 1
    ];

    $model->create($data);
    header('Location: admin_equipo.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Crear Administrador</title>

<style>
* {
    box-sizing: border-box;
}

body {
    font-family: "Poppins", Arial, sans-serif;
    background: #faf6fb;
    margin: 0;
    padding: 0;
    color: #333;
}

/* ===========================
   HEADER
=========================== */
header {
    background: linear-gradient(90deg, #dcb0ff, #f3d1ff);
    padding: 16px;
    color: #4b006e;
    display: flex;
    justify-content: center;
    align-items: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    text-align: center;
}

header h1 {
    margin: 0;
    font-size: 22px;
    font-weight: 600;
}

/* ===========================
   CONTENEDOR
=========================== */
.container {
    max-width: 600px;
    margin: 30px auto;
    background: #fff;
    padding: 25px;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

h2 {
    text-align: center;
    color: #5a008a;
    margin-top: 0;
    margin-bottom: 20px;
    font-size: 22px;
}

/* ===========================
   FORMULARIO
=========================== */
label {
    display: block;
    font-weight: 600;
    margin-top: 15px;
    color: #5a008a;
}

input {
    width: 100%;
    padding: 14px;
    border-radius: 10px;
    border: 1.5px solid #d8c3ff;
    margin-top: 6px;
    font-size: 16px;
}

/* ===========================
   BOTÓN
=========================== */
button {
    background: #c67aff;
    color: #fff;
    padding: 16px;
    border: none;
    border-radius: 14px;
    cursor: pointer;
    margin-top: 30px;
    font-weight: 600;
    width: 100%;
    font-size: 18px;
    transition: background 0.2s;
}

button:hover {
    background: #a950ff;
}

/* ===========================
   LINK VOLVER
=========================== */
.volver {
    display: block;
    margin-top: 25px;
    text-align: center;
    color: #6a00a5;
    font-weight: 600;
    text-decoration: none;
    font-size: 16px;
}

.volver:hover {
    text-decoration: underline;
}

/* ===========================
   MODO MÓVIL REAL
=========================== */
@media (max-width: 768px) {

    .container {
        margin: 15px;
        padding: 20px;
    }

    h2 {
        font-size: 20px;
    }

    input {
        font-size: 17px;
        padding: 16px;
    }

    button {
        font-size: 18px;
        padding: 18px;
    }

    .volver {
        font-size: 17px;
    }
}
</style>

</head>
<body>

<iframe src="/public/audio_background.php"
        style="display:none;"
        allow="autoplay"></iframe>

<header>
    <h1>➕ Crear Administrador</h1>
</header>

<div class="container">
    <h2>Registrar nuevo administrador</h2>

    <form method="post">

        <label>Nombre completo</label>
        <input type="text" name="nombre" required>

        <label>Teléfono</label>
        <input type="text" name="telefono">

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Clave</label>
        <input type="password" name="clave" required>

        <button type="submit">Crear Administrador</button>
    </form>

    <a href="admin_equipo.php" class="volver">⬅ Volver</a>
</div>

</body>
</html>
