@echo off
REM Module Generator - Creates complete module with all files
REM Usage: apimake module <ModuleName> [route-prefix]

if "%~1"=="" (
    echo.
    echo Error: Module name is required
    echo.
    echo Usage: apimake module ^<ModuleName^> [route-prefix]
    echo.
    echo Examples:
    echo   apimake module Product
    echo   apimake module Product /products
    echo   apimake module products
    echo.
    exit /b 1
)

cd /d "%~dp0"
if not exist "make-module.php" (
    echo Error: make-module.php not found in %~dp0
    exit /b 1
)

php "make-module.php" "%~1" "%~2"
if errorlevel 1 exit /b 1

