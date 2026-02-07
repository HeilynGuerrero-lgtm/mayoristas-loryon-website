<?php
require_once __DIR__ . '/_init.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../app/helpers/auth.php';

requireLogin();
requireAdmin();

$mensaje = "";
$link = "";

if (isset($_POST['backup'])) {

    set_time_limit(0);

    // Carpeta backups
    $backupDir = __DIR__ . '/../backups';
    if (!file_exists($backupDir)) {
        mkdir($backupDir, 0755, true);
    }

    $date = date("Y-m-d_H-i-s");
    $zipName = "backup_loryon_$date.zip";
    $zipPath = $backupDir . "/" . $zipName;

    // ========== EXPORTAR BASE DE DATOS ==========
    $sqlFile = $backupDir . "/db_$date.sql";

    $command = "mysqldump -h {$DB_HOST} -u {$DB_USER} -p{$DB_PASS} {$DB_NAME} > \"$sqlFile\"";
    exec($command);

    if (!file_exists($sqlFile)) {
        die("❌ Error exportando la base de datos");
    }

    // ========== CREAR ZIP ==========
    $zip = new ZipArchive();
    if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
        die("❌ No se pudo crear el ZIP");
    }

    // Agregar SQL
    $zip->addFile($sqlFile, "database.sql");

    // ========== AGREGAR TODOS LOS ARCHIVOS ==========
    $rootPath = realpath(__DIR__ . "/.."); // htdocs

    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($rootPath, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($files as $file) {
        $filePath = $file->getRealPath();

        // No meter los backups dentro del backup
        if (strpos($filePath, realpath($backupDir)) !== false) continue;

        $relativePath = substr($filePath, strlen($rootPath) + 1);

        if ($file->isDir()) {
            $zip->addEmptyDir($relativePath);
        } else {
            $zip->addFile($filePath, $relativePath);
        }
    }

    $zip->close();
    unlink($sqlFile);

    $link = "../backups/$zipName";
    $mensaje = "✅ Backup creado correctamente";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Backup del Sistema</title>
<style>
body{
    font-family:Poppins,Arial;
    background:#faf6fb;
    text-align:center;
    padding-top:50px;
}
.card{
    background:white;
    width:90%;
    max-width:450px;
    margin:auto;
    padding:25px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,.1);
}
button{
    background:#c67aff;
    border:none;
    color:white;
    padding:15px 30px;
    border-radius:12px;
    font-size:17px;
    cursor:pointer;
}
a.download{
    display:block;
    margin-top:20px;
    background:#5a00a5;
    color:white;
    padding:14px;
    border-radius:12px;
    text-decoration:none;
}
</style>
</head>
<body>

<div class="card">
    <h2>💾 Backup completo</h2>

    <form method="post">
        <button name="backup">Crear Backup</button>
    </form>

    <?php if($mensaje): ?>
        <p><?= $mensaje ?></p>
        <a class="download" href="<?= $link ?>" download>⬇ Descargar Backup</a>
    <?php endif; ?>
</div>

</body>
</html>
