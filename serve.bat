@echo off
:: ===========================================
:: Simple PHP Dev Server
:: ===========================================
setlocal

:: Get current directory
set "PROJECT_DIR=%cd%"
set "PORT=8000"

echo ===========================================
echo 🚀 Starting PHP Dev Server
echo 📂 Directory: %PROJECT_DIR%
echo 🌐 URL: http://localhost:%PORT%
echo ===========================================

:: Check if PHP is installed
php -v >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ PHP not found! Please install PHP or add it to PATH.
    pause
    exit /b
)

:: Start PHP built-in server
php -S localhost:%PORT% -t "%PROJECT_DIR%"

pause
