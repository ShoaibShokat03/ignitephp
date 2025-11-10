@echo off
REM Controller Generator - Creates controller file only
REM Usage: apimake controller <ModuleName> <ControllerName>

if "%~1"=="" (
    echo.
    echo Error: Module name is required
    echo.
    echo Usage: apimake controller ^<ModuleName^> ^<ControllerName^>
    echo.
    echo Examples:
    echo   apimake controller Product ProductController
    echo   apimake controller User UserController
    echo.
    exit /b 1
)
if "%~2"=="" (
    echo.
    echo Error: Controller name is required
    echo.
    echo Usage: apimake controller ^<ModuleName^> ^<ControllerName^>
    echo.
    echo Examples:
    echo   apimake controller Product ProductController
    echo   apimake controller User UserController
    echo.
    exit /b 1
)

cd /d "%~dp0"
if not exist "make-controller.php" (
    echo Error: make-controller.php not found in %~dp0
    exit /b 1
)

php "make-controller.php" "%~1" "%~2"
if errorlevel 1 exit /b 1

