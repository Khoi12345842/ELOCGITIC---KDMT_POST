# 🚀 Hướng Dẫn Cài Đặt Tự Động

## Phương Án : Cài Bằng File .BAT (Một lệnh duy nhất)

```batch
# Click phải file → Run as Administrator
quick-install.bat
```

⚠️ **Lưu ý:** Cần kết nối Internet ổn định

---

## Phương Án 3: Cài Thủ Công Từng Bước

### 1. Cài Chocolatey
```powershell
# PowerShell (Administrator)
Set-ExecutionPolicy Bypass -Scope Process -Force
[System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072
iex ((New-Object System.Net.WebClient).DownloadString('https://community.chocolatey.org/install.ps1'))
```

### 2. Cài PHP + Composer + Node.js
```powershell
# PowerShell (Administrator)
choco install php composer nodejs -y
```

### 3. Refresh Environment
```powershell
# ĐÓNG và MỞ LẠI PowerShell
```

### 4. Tạo Laravel Project
```powershell
cd "D:\ELOGICTIC KDMT"
composer create-project laravel/laravel . --prefer-dist
```

### 5. Cấu hình
```powershell
# Copy .env
copy .env.example .env

# Generate key
php artisan key:generate

# Tạo SQLite database
New-Item -ItemType File -Path database\database.sqlite
```

### 6. Chỉnh .env
```env
DB_CONNECTION=sqlite
# Comment hoặc xóa: DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD
```

### 7. Migrate & Serve
```powershell
php artisan migrate
php artisan serve
```

---

## ✅ Kiểm Tra Cài Đặt

```powershell
# Kiểm tra PHP
php -v
# Output: PHP 8.2.x

# Kiểm tra Composer
composer -V
# Output: Composer version 2.x.x

# Kiểm tra Node.js
node -v
# Output: v20.x.x

# Kiểm tra Laravel
php artisan --version
# Output: Laravel Framework 10.x.x
```

---

## 🐛 Xử Lý Lỗi



trong trường hợp tạo laravel lỗi , chạy fix.ps1 dưới quyền administrator để sửa lỗi tự động.

### Lỗi: "Running scripts is disabled"
```powershell
Set-ExecutionPolicy -Scope Process -Force Bypass
```

### Lỗi: "composer not recognized"
```powershell
# Đóng và mở lại PowerShell để refresh PATH
```

### Lỗi: "could not find driver"
```powershell
# Kiểm tra PHP extensions
php -m | findstr pdo

# Nếu không có, chỉnh php.ini:
# Bỏ dấu ; trước: extension=pdo_sqlite
```

### Lỗi: Chocolatey cài chậm
- Kiểm tra Internet
- Tắt Antivirus tạm thời
- Dùng VPN nếu cần

---

## 📦 Sau Khi Cài Xong

Bạn sẽ có cấu trúc:
```
D:\ELOGICTIC KDMT\
├── app/
├── bootstrap/
├── config/
├── database/
│   └── database.sqlite
├── public/
├── resources/
├── routes/
├── .env
├── artisan
└── composer.json
```

**Sẵn sàng để phát triển! 🎉**

---

## 🎯 Các Lệnh Hữu Ích

```powershell
# Khởi động server
php artisan serve

# Tạo controller
php artisan make:controller OrderController

# Tạo model + migration
php artisan make:model Order -m

# Chạy migrations
php artisan migrate

# Xem routes
php artisan route:list

# Clear cache
php artisan optimize:clear
```

---

