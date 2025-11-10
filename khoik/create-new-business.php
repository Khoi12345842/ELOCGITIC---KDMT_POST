<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

echo "👤 Tạo tài khoản Business mới (chưa liên kết shop)...\n\n";

$user = User::create([
    'name' => 'Nguyễn Văn Business',
    'email' => 'business@test.com',
    'password' => bcrypt('123456'),
    'phone' => '0987654321',
    'user_type' => 'business',
    'company_name' => 'Công ty TNHH Thương Mại ABC',
    'company_address' => '123 Nguyễn Huệ, Quận 1, TP.HCM',
    'tax_code' => '0123456789',
    'business_type' => 'retail',
    // Các trường shop để NULL - chưa liên kết
    'shop_platform' => null,
    'shop_id' => null,
    'shop_name' => null,
    'discount_rate' => 0,
    'has_contract' => false,
    'contract_start_date' => null,
    'contract_end_date' => null,
]);

echo "✅ Tạo thành công!\n\n";
echo "📋 Thông tin tài khoản:\n";
echo "   Email: business@test.com\n";
echo "   Mật khẩu: 123456\n";
echo "   Loại: Doanh nghiệp\n";
echo "   Công ty: {$user->company_name}\n";
echo "   MST: {$user->tax_code}\n";
echo "\n🔗 Trạng thái shop:\n";
echo "   ❌ Chưa liên kết shop\n";
echo "   ❌ Chưa có hợp đồng\n";
echo "   ❌ Chưa có đơn hàng\n";
echo "\n🎯 Bước tiếp theo:\n";
echo "   1. Đăng nhập: business@test.com / 123456\n";
echo "   2. Vào 'Lên đơn theo lô'\n";
echo "   3. Click 'Liên kết Shop ngay'\n";
echo "   4. Điền form và ký hợp đồng\n";
echo "   5. → Popup thành công + Tạo 15 đơn fake\n";
