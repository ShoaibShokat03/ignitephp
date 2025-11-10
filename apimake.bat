@echo off
REM Main API Make Command Handler
REM Usage: apimake <command> [arguments]

if "%~1"=="" (
    goto :show_help
)

set COMMAND=%~1

if /i "%COMMAND%"=="module" (
    if not exist "%~dp0app\scripts\module.bat" (
        echo Error: module.bat not found at %~dp0app\scripts\module.bat
        exit /b 1
    )
    call "%~dp0app\scripts\module.bat" %2 %3
    exit /b %errorlevel%
)
if /i "%COMMAND%"=="controller" (
    if not exist "%~dp0app\scripts\controller.bat" (
        echo Error: controller.bat not found at %~dp0app\scripts\controller.bat
        exit /b 1
    )
    call "%~dp0app\scripts\controller.bat" %2 %3
    exit /b %errorlevel%
)
if /i "%COMMAND%"=="model" (
    if not exist "%~dp0app\scripts\model.bat" (
        echo Error: model.bat not found at %~dp0app\scripts\model.bat
        exit /b 1
    )
    call "%~dp0app\scripts\model.bat" %2 %3 %4
    exit /b %errorlevel%
)
if /i "%COMMAND%"=="service" (
    if not exist "%~dp0app\scripts\service.bat" (
        echo Error: service.bat not found at %~dp0app\scripts\service.bat
        exit /b 1
    )
    call "%~dp0app\scripts\service.bat" %2 %3
    exit /b %errorlevel%
)
if /i "%COMMAND%"=="usetable" (
    if not exist "%~dp0app\scripts\usetable.bat" (
        echo Error: usetable.bat not found at %~dp0app\scripts\usetable.bat
        exit /b 1
    )
    call "%~dp0app\scripts\usetable.bat" %2
    exit /b %errorlevel%
)

echo Error: Unknown command "%COMMAND%"
echo.
echo Did you mean:
if /i "%COMMAND%"=="moudel" echo   module
if /i "%COMMAND%"=="moduel" echo   module
if /i "%COMMAND%"=="modle" echo   module
echo.
goto :show_help

:show_help
echo.
echo IgnitePHP API Generator
echo ========================
echo.
echo Usage: apimake ^<command^> [arguments]
echo.
echo Commands:
echo   module ^<ModuleName^> [route-prefix]  - Create complete module (all 4 files)
echo   controller ^<ModuleName^> ^<ControllerName^>  - Create controller only
echo   model ^<ModuleName^> ^<ModelName^> [table-name]  - Create model only
echo   service ^<ModuleName^> ^<ServiceName^>  - Create service only
echo   usetable all  - Generate modules for ALL database tables
echo.
echo Examples:
echo   apimake module Product /products
echo   apimake controller Product ProductController
echo   apimake model Product ProductModel products
echo   apimake service Product ProductService
echo   apimake usetable all
echo.
exit /b 0
