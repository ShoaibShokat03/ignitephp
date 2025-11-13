@echo off
REM Script to add admin user to database
REM Usage: add_admin.bat [username] [password] [email] [full_name]

cd /d "%~dp0\..\.."
php database\scripts\add_admin.php %*

pause

