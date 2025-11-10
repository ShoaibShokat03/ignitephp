@echo off
REM Model Generator - Creates model file only
REM Usage: apimake model <ModuleName> <ModelName> [table-name]

if "%~1"=="" (
    echo.
    echo Error: Module name is required
    echo.
    echo Usage: apimake model ^<ModuleName^> ^<ModelName^> [table-name]
    echo.
    echo Examples:
    echo   apimake model Product ProductModel
    echo   apimake model Product ProductModel products
    echo   apimake model User UserModel users
    echo.
    exit /b 1
)
if "%~2"=="" (
    echo.
    echo Error: Model name is required
    echo.
    echo Usage: apimake model ^<ModuleName^> ^<ModelName^> [table-name]
    echo.
    echo Examples:
    echo   apimake model Product ProductModel
    echo   apimake model Product ProductModel products
    echo.
    exit /b 1
)

cd /d "%~dp0"
if not exist "make-model.php" (
    echo Error: make-model.php not found in %~dp0
    exit /b 1
)

php "make-model.php" "%~1" "%~2" "%~3"
if errorlevel 1 exit /b 1

