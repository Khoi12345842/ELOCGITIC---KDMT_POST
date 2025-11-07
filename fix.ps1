# Script tự động bật TẤT CẢ PHP extensions cần thiết cho Laravel
Write-Host "=== Tự động bật PHP Extensions cho Laravel ===" -ForegroundColor Cyan

$phpIniPath = "C:\tools\php84\php.ini"

if (!(Test-Path $phpIniPath)) {
    Write-Host "✗ Không tìm thấy php.ini tại: $phpIniPath" -ForegroundColor Red
    Write-Host "Tìm đường dẫn php.ini:" -ForegroundColor Yellow
    php --ini
    pause
    exit
}

Write-Host "✓ Tìm thấy php.ini tại: $phpIniPath" -ForegroundColor Green

# Backup php.ini
$backupPath = "$phpIniPath.backup-$(Get-Date -Format 'yyyyMMdd-HHmmss')"
Copy-Item $phpIniPath $backupPath -Force
Write-Host "✓ Đã backup php.ini -> $backupPath" -ForegroundColor Green

# Danh sách extensions cần thiết cho Laravel
$requiredExtensions = @(
    'zip',
    'fileinfo',
    'pdo_sqlite',
    'pdo_mysql',
    'mbstring',
    'openssl',
    'curl',
    'gd',
    'intl'
)

Write-Host "`nĐang bật các extensions..." -ForegroundColor Yellow

# Đọc nội dung php.ini
$content = Get-Content $phpIniPath

$newContent = @()
$modified = @{}

foreach ($line in $content) {
    $added = $false
    
    # Kiểm tra từng extension
    foreach ($ext in $requiredExtensions) {
        # Nếu tìm thấy dòng bị comment (;extension=xxx)
        if ($line -match "^\s*;extension=$ext\s*$") {
            $newContent += "extension=$ext"
            $modified[$ext] = $true
            Write-Host "  ✓ Bật extension=$ext" -ForegroundColor Green
            $added = $true
            break
        }
    }
    
    if (!$added) {
        $newContent += $line
    }
}

# Thêm các extension chưa có trong file
$newContent += ""
$newContent += "; === Auto-added by fix-php-extensions.ps1 ==="

foreach ($ext in $requiredExtensions) {
    if (!$modified.ContainsKey($ext)) {
        # Kiểm tra xem đã có dòng extension=xxx chưa
        $alreadyEnabled = $content | Where-Object { $_ -match "^\s*extension=$ext\s*$" }
        
        if (!$alreadyEnabled) {
            $newContent += "extension=$ext"
            Write-Host "  ✓ Thêm extension=$ext" -ForegroundColor Green
            $modified[$ext] = $true
        } else {
            Write-Host "  ℹ extension=$ext đã được bật sẵn" -ForegroundColor Cyan
        }
    }
}

# Lưu lại file
Set-Content $phpIniPath $newContent

Write-Host "`n=== Kiểm tra extensions đã bật ===" -ForegroundColor Cyan
$enabledExtensions = php -m

foreach ($ext in $requiredExtensions) {
    if ($enabledExtensions -contains $ext) {
        Write-Host "  ✓ $ext" -ForegroundColor Green
    } else {
        Write-Host "  ✗ $ext - CHƯA BẬT!" -ForegroundColor Red
    }
}

Write-Host "`n=== HOÀN TẤT ===" -ForegroundColor Green
Write-Host "Bây giờ bạn có thể tạo Laravel project:" -ForegroundColor Yellow
Write-Host "  cd D:\" -ForegroundColor White
Write-Host "  composer create-project laravel/laravel 'ELOGICTIC KDMT'" -ForegroundColor White

pause