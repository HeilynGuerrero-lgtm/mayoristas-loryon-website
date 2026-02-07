    @echo off
    color 0d
    setlocal enabledelayedexpansion

    :: ==========================
    :: CONFIGURACIÓN
    :: ==========================
    set MYSQL_PATH=C:\xampp\mysql\bin
    set PROJECT_PATH=C:\xampp\htdocs\loryon
    set BACKUP_PATH=C:\Respaldo_Loryon

    :: Crear carpetas si no existen
    if not exist "%BACKUP_PATH%" mkdir "%BACKUP_PATH%"
    if not exist "%BACKUP_PATH%\temp" mkdir "%BACKUP_PATH%\temp"

    :: ==========================
    :: FECHA Y HORA LIMPIA
    :: ==========================
    for /f "tokens=1-5 delims=/: " %%a in ("%date% %time%") do (
        set FECHA=%%a-%%b-%%c
        set HORA=%%d-%%e
    )

    :: Ruta carpeta temporal
    set TEMP_DIR=%BACKUP_PATH%\temp

    echo ==========================
    echo  RESPALDANDO BASE DE DATOS
    echo ==========================

    set SQL_FILE=%TEMP_DIR%\bd_%FECHA%_%HORA%.sql

    "%MYSQL_PATH%\mysqldump" -u root --databases mayoristas_loryon > "%SQL_FILE%"

    echo Backup SQL creado:
    echo %SQL_FILE%
    echo.

    echo ======================
    echo  RESPALDANDO ARCHIVOS
    echo ======================

    set FILES_PATH=%TEMP_DIR%\files_%FECHA%_%HORA%
    mkdir "%FILES_PATH%"

    xcopy "%PROJECT_PATH%" "%FILES_PATH%\" /E /I /H /C /Y >nul

    echo Archivos copiados:
    echo %FILES_PATH%
    echo.

    echo ======================
    echo  GENERANDO ARCHIVO ZIP
    echo ======================

    set ZIP_FILE=%BACKUP_PATH%\backup_%FECHA%_%HORA%.zip

    :: Eliminar ZIP si existía
    if exist "%ZIP_FILE%" (
        echo Eliminando ZIP anterior...
        del /f /q "%ZIP_FILE%"
    )

    powershell -command "Compress-Archive -Path '%TEMP_DIR%\*' -DestinationPath '%ZIP_FILE%' -Force"

    echo ZIP creado correctamente:
    echo %ZIP_FILE%
    echo.

    echo ==================================
    echo  LIMPIANDO ARCHIVOS TEMPORALES...
    echo ==================================
    rmdir /s /q "%TEMP_DIR%"

    echo.
    echo ==========================
    echo     BACKUP COMPLETO OK
    echo ==========================
    pause
    exit
