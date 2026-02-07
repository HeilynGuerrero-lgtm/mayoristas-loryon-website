<?php
require_once __DIR__ . '/_init.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../app/helpers/auth.php';

requireLogin();
requireAdmin();

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_FILES['backup']) || $_FILES['backup']['error'] !== 0) {
        $mensaje = "❌ Archivo inválido.";
    } else {

        $zipFile = $_FILES['backup']['tmp_name'];
        $tempDir = sys_get_temp_dir() . "/restore_loryon_" . uniqid();
        mkdir($tempDir, 0777, true);

        $zip = new ZipArchive();
        if ($zip->open($zipFile) !== true) {
            die("No se pudo abrir el ZIP.");
        }

        $zip->extractTo($tempDir);
        $zip->close();

        $sqlFile = $tempDir . "/database.sql";
        $filesDir = $tempDir . "/files";

        if (!file_exists($sqlFile) || !is_dir($filesDir)) {
            die("Backup inválido.");
        }

        /* ===========================
           1️⃣ RESTAURAR BASE DE DATOS
        ============================ */
        $pdo->exec("SET FOREIGN_KEY_CHECKS=0");

        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        foreach ($tables as $table) {
            $pdo->exec("DROP TABLE `$table`");
        }

        $sql = file_get_contents($sqlFile);
        $pdo->exec($sql);

        $pdo->exec("SET FOREIGN_KEY_CHECKS=1");


        /* ===========================
           2️⃣ RESTAURAR ARCHIVOS
        ============================ */
        $siteRoot = realpath(__DIR__ . "/..");

        function restoreFiles($src, $dst) {
            $files = scandir($src);
            foreach ($files as $file) {
                if ($file != "." && $file != "..") {
                    if (is_dir("$src/$file")) {
                        if (!is_dir("$dst/$file")) mkdir("$dst/$file", 0777, true);
                        restoreFiles("$src/$file", "$dst/$file");
                    } else {
                        copy("$src/$file", "$dst/$file");
                    }
                }
            }
        }

        restoreFiles($filesDir, $siteRoot);

        $mensaje = "✅ Backup restaurado exitosamente. El sistema ha sido recuperado.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Restaurar Backup</title>
<style>
body {
    font-family: "Poppins", sans-serif;
    background: #faf6fb;
    text-align: center;
    padding-top: 50px;
    color: #4b006e;
}
.card {
    background: white;
    width: 420px;
    margin: auto;
    padding: 25px;
    border-radius: 14px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
input {
    margin-top: 15px;
    padding: 10px;
}
button {
    background: #c67aff;
    border: none;
    padding: 12px 25px;
    font-size: 16px;
    color: white;
    border-radius: 10px;
    cursor: pointer;
    margin-top: 20px;
}
button:hover {
    background: #a950ff;
}
</style>
</head>
<body>

<div class="card">
    <h1>♻ Restaurar Backup</h1>
    <p>Selecciona el archivo ZIP del respaldo</p>

    <form method="post" enctype="multipart/form-data">
        <input type="file" name="backup" accept=".zip" required><br>
        <button type="submit">Restaurar sistema</button>
    </form>

    <?php if ($mensaje): ?>
        <p><strong><?= $mensaje ?></strong></p>
    <?php endif; ?>
</div>

<a href="index.php">⬅ Volver</a>

</body>
</html>
