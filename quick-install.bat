@echo off
chcp 65001 >nul
echo ===================================
echo VNPost Logistics - Quick Install
echo ===================================
echo.

REM Kiểm tra quyền Administrator
net session >nul 2>&1
if %errorlevel% neq 0 (
    echo [LỖI] Vui lòng chạy với quyền Administrator!
    echo Cách chạy: Click phải file này → Run as administrator
    pause
    exit /b
)

echo [1/3] Đang cài đặt Chocolatey...
powershell -NoProfile -ExecutionPolicy Bypass -Command "Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://community.chocolatey.org/install.ps1'))"

echo.
echo [2/3] Đang cài đặt PHP, Composer, Node.js...
call refreshenv
choco install php composer nodejs -y

echo.
echo [3/3] Đang tạo Laravel project...
call refreshenv

cd /d "%~dp0"
composer create-project laravel/laravel . --prefer-dist

echo.
echo ===================================
echo       CÀI ĐẶT HOÀN TẤT!
echo ===================================
echo.
echo Các lệnh tiếp theo:
echo   php artisan serve
echo.
echo Mở trình duyệt: http://localhost:8000
echo.
pause
