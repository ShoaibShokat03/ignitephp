@echo off
REM Service Generator - Creates service file only
REM Usage: apimake service <ModuleName> <ServiceName>

if "%~1"=="" (
    echo.
    echo Error: Module name is required
    echo.
    echo Usage: apimake service ^<ModuleName^> ^<ServiceName^>
    echo.
    echo Examples:
    echo   apimake service Product ProductService
    echo   apimake service User UserService
    echo.
    exit /b 1
)
if "%~2"=="" (
    echo.
    echo Error: Service name is required
    echo.
    echo Usage: apimake service ^<ModuleName^> ^<ServiceName^>
    echo.
    echo Examples:
    echo   apimake service Product ProductService
    echo   apimake service User UserService
    echo.
    exit /b 1
)

cd /d "%~dp0"
if not exist "make-service.php" (
    echo Error: make-service.php not found in %~dp0
    exit /b 1
)

php "make-service.php" "%~1" "%~2"
if errorlevel 1 exit /b 1

