@echo off
:: ===========================================
:: Simple PHP Dev Server
:: ===========================================
setlocal

:: Get current directory
set "PROJECT_DIR=%cd%"
set "PORT=8000"

echo ===========================================
echo Starting PHP Dev Server
echo Directory: %PROJECT_DIR%
echo Local URL: http://localhost:%PORT%
echo Network URL: http://%COMPUTERNAME%.local:%PORT%

:: Get the actual IP address
for /f "tokens=2 delims=:" %%a in ('ipconfig ^| findstr /c:"IPv4 Address"') do (
    set "IP=%%a"
    goto :found
)
:found
if defined IP (
    set "IP=%IP: =%"
    echo IP URL: http://%IP%:%PORT%
) else (
    echo IP URL: http://[YOUR_IP]:%PORT% (run ipconfig to find your IP)
)
echo ===========================================
echo.
echo To find your IP address, run: ipconfig
echo Other computers can access via your IP address
echo Make sure Windows Firewall allows port %PORT%
echo ===========================================

:: Check if PHP is installed
php -v >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: PHP not found! Please install PHP or add it to PATH.
    pause
    exit /b
)

:: Start PHP built-in server (accessible from network)
:: Use index.php as router script (replaces .htaccess functionality)
php -S 0.0.0.0:%PORT% -t "%PROJECT_DIR%" index.php

pause