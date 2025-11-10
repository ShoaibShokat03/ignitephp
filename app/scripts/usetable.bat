@echo off
:: ===========================================
:: Generate Modules from ALL Database Tables
:: ===========================================

if "%~1"=="" (
    echo Usage: apimake usetable all
    echo.
    echo This command generates complete modules for ALL tables in the database.
    echo Each table will get: Module.php, Controller.php, Service.php, Model.php
    echo.
    echo Example:
    echo   apimake usetable all
    exit /b 1
)

if not "%~1"=="all" (
    echo Error: Only 'all' is supported. Use: apimake usetable all
    exit /b 1
)

cd /d "%~dp0"
php "usetable.php" all
if errorlevel 1 exit /b 1

