# Script tạo Laravel project
# Chạy SAU KHI đã cài PHP, Composer

Write-Host "=== Tạo Laravel Project - VNPost Logistics ===" -ForegroundColor Cyan
Write-Host ""

# Kiểm tra dependencies
$phpExists = Get-Command php -ErrorAction SilentlyContinue
$composerExists = Get-Command composer -ErrorAction SilentlyContinue

if (!$phpExists) {
    Write-Host "✗ Chưa cài PHP! Hãy chạy install.ps1 trước" -ForegroundColor Red
    pause
    exit
}

if (!$composerExists) {
    Write-Host "✗ Chưa cài Composer! Hãy chạy install.ps1 trước" -ForegroundColor Red
    pause
    exit
}

Write-Host "✓ PHP và Composer đã sẵn sàng" -ForegroundColor Green
Write-Host ""

# Xóa thư mục cũ nếu có (cẩn thận!)
$currentDir = Get-Location
if (Test-Path "$currentDir\app") {
    Write-Host "Phát hiện project Laravel cũ. Bạn có muốn xóa không? (y/n)" -ForegroundColor Yellow
    $confirm = Read-Host
    if ($confirm -eq 'y') {
        Write-Host "Đang xóa files cũ..." -ForegroundColor Yellow
        Remove-Item * -Recurse -Force -Exclude "*.ps1","*.bat","README.md"
        Write-Host "✓ Đã xóa files cũ" -ForegroundColor Green
    } else {
        Write-Host "Hủy bỏ. Vui lòng xóa thủ công hoặc chọn thư mục khác" -ForegroundColor Red
        pause
        exit
    }
}

# Tạo Laravel project
Write-Host "Đang tạo Laravel project..." -ForegroundColor Green
Write-Host "Quá trình này mất 2-5 phút, vui lòng chờ..." -ForegroundColor Yellow
Write-Host ""

# Tạo project trong thư mục tạm
$parentDir = Split-Path $currentDir -Parent
$projectName = Split-Path $currentDir -Leaf

Set-Location $parentDir

# Xóa folder cũ nếu tồn tại
if (Test-Path "temp-laravel-install") {
    Remove-Item "temp-laravel-install" -Recurse -Force
}

composer create-project laravel/laravel "temp-laravel-install" --prefer-dist

if ($LASTEXITCODE -eq 0) {
    Write-Host "✓ Laravel đã được tạo!" -ForegroundColor Green
    
    # Di chuyển files vào thư mục hiện tại
    Write-Host "Đang di chuyển files..." -ForegroundColor Yellow
    Get-ChildItem "temp-laravel-install\*" | Move-Item -Destination $currentDir -Force
    Remove-Item "temp-laravel-install" -Force
    
    Set-Location $currentDir
    
    # Cấu hình Laravel
    Write-Host "`nĐang cấu hình Laravel..." -ForegroundColor Green
    
    # Copy .env
    if (!(Test-Path ".env")) {
        Copy-Item ".env.example" ".env"
    }
    
    # Generate key
    php artisan key:generate
    
    # Tạo SQLite database
    New-Item -ItemType File -Path "database\database.sqlite" -Force | Out-Null
    
    # Cập nhật .env để dùng SQLite
    $envContent = Get-Content ".env"
    $envContent = $envContent -replace 'DB_CONNECTION=mysql', 'DB_CONNECTION=sqlite'
    $envContent = $envContent -replace 'DB_HOST=.*', '# DB_HOST=127.0.0.1'
    $envContent = $envContent -replace 'DB_PORT=.*', '# DB_PORT=3306'
    $envContent = $envContent -replace 'DB_DATABASE=.*', '# DB_DATABASE=laravel'
    $envContent = $envContent -replace 'DB_USERNAME=.*', '# DB_USERNAME=root'
    $envContent = $envContent -replace 'DB_PASSWORD=.*', '# DB_PASSWORD='
    Set-Content ".env" $envContent
    
    Write-Host "✓ SQLite database đã được tạo" -ForegroundColor Green
    
    # Chạy migrations mặc định
    Write-Host "`nĐang chạy migrations..." -ForegroundColor Yellow
    php artisan migrate --force
    
    Write-Host "`n=== HOÀN TẤT ===" -ForegroundColor Green
    Write-Host "Laravel project đã sẵn sàng tại: $currentDir" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "Các lệnh tiếp theo:" -ForegroundColor Yellow
    Write-Host "  php artisan serve          # Khởi động server" -ForegroundColor White
    Write-Host "  php artisan migrate        # Chạy migrations" -ForegroundColor White
    Write-Host "  php artisan db:seed        # Chạy seeders" -ForegroundColor White
    Write-Host "  php artisan route:list     # Xem routes" -ForegroundColor White
    Write-Host ""
    Write-Host "Mở trình duyệt: http://localhost:8000" -ForegroundColor Cyan
    
} else {
    Write-Host "✗ Lỗi khi tạo Laravel project" -ForegroundColor Red
    Set-Location $currentDir
}

pause
