<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Order;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;

echo "🧪 Testing Shop Link Flow...\n\n";

// 1. Tạo hoặc lấy user business
echo "1️⃣ Tạo user business...\n";
$businessUser = User::where('email', 'test-business@example.com')->first();

if (!$businessUser) {
    $businessUser = User::create([
        'name' => 'Test Business User',
        'email' => 'test-business@example.com',
        'password' => bcrypt('password123'),
        'phone' => '0987654321',
        'user_type' => 'business',
        'company_name' => 'Công ty Test Shop',
        'company_address' => '123 Nguyễn Huệ, Q1, TP.HCM',
        'tax_code' => '0123456789',
    ]);
    echo "   ✅ Đã tạo user business mới\n";
} else {
    echo "   ✅ Đã có user business\n";
}

// 2. Lấy staff users
echo "\n2️⃣ Kiểm tra staff users...\n";
$staffCount = User::where('role', 'staff')->count();
echo "   📊 Có {$staffCount} nhân viên trong hệ thống\n";

if ($staffCount === 0) {
    echo "   ⚠️ Không có nhân viên! Tạo nhân viên mẫu...\n";
    User::create([
        'name' => 'Nhân viên Test',
        'email' => 'staff-test@example.com',
        'password' => bcrypt('password123'),
        'phone' => '0912345678',
        'role' => 'staff',
    ]);
    echo "   ✅ Đã tạo nhân viên mẫu\n";
}

// 3. Simulate liên kết shop
echo "\n3️⃣ Simulate liên kết shop...\n";
DB::beginTransaction();
try {
    // Update user info
    $businessUser->update([
        'shop_platform' => 'Shopee',
        'shop_id' => 'SHOP123456',
        'shop_name' => 'Test Shopee Store',
        'discount_rate' => 15,
        'has_contract' => true,
        'contract_start_date' => now(),
        'contract_end_date' => now()->addMonths(12),
    ]);
    echo "   ✅ Đã cập nhật thông tin shop\n";

    // Tạo 15 đơn fake
    echo "\n4️⃣ Tạo 15 đơn hàng fake...\n";
    $faker = \Faker\Factory::create('vi_VN');
    $cities = ['Hà Nội', 'TP.HCM', 'Đà Nẵng', 'Hải Phòng', 'Cần Thơ'];
    $statuses = ['pending', 'picked_up', 'in_transit'];
    $createdOrders = [];

    for ($i = 0; $i < 15; $i++) {
        $status = $statuses[array_rand($statuses)];
        $senderCity = $cities[array_rand($cities)];
        $receiverCity = $cities[array_rand($cities)];
        $weight = round(rand(5, 200) / 10, 1);
        
        $order = Order::create([
            'order_number' => 'TEST' . now()->format('Ymd') . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
            'user_id' => $businessUser->id,
            'order_type' => 'shop_sync',
            'shop_platform' => 'Shopee',
            'shop_name' => 'Test Shopee Store',
            'shop_order_id' => 'SHOPEE' . rand(100000, 999999),
            'sender_name' => 'Test Shopee Store',
            'sender_phone' => $businessUser->phone,
            'sender_address' => $businessUser->company_address,
            'sender_city' => $senderCity,
            'receiver_name' => $faker->name,
            'receiver_phone' => '0' . rand(900000000, 999999999),
            'receiver_address' => $faker->address,
            'receiver_city' => $receiverCity,
            'package_description' => $faker->randomElement(['Quần áo', 'Mỹ phẩm', 'Điện tử']),
            'weight' => $weight,
            'cod_amount' => rand(100000, 2000000),
            'shipping_fee' => rand(20000, 50000),
            'total_amount' => rand(120000, 2050000),
            'current_status' => $status,
            'status_description' => 'Đơn từ shop',
            'notes' => 'Đơn tự động từ Shopee',
        ]);
        
        $createdOrders[] = $order;
        echo "   📦 Đơn " . ($i + 1) . ": {$order->order_number}\n";
    }

    // 5. Phân công và gửi thông báo
    echo "\n5️⃣ Phân công đơn hàng cho nhân viên...\n";
    $staffUsers = User::where('role', 'staff')->get();
    
    foreach ($createdOrders as $order) {
        if ($staffUsers->isNotEmpty()) {
            $assignedStaff = $staffUsers->random();
            $order->update(['assigned_to' => $assignedStaff->id]);
            
            Notification::create([
                'user_id' => $assignedStaff->id,
                'type' => 'new_order',
                'title' => '🎉 Đơn hàng mới từ Shop',
                'message' => "Bạn được phân công đơn hàng #{$order->order_number} từ shop Test Shopee Store (Shopee)",
                'data' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'shop_name' => 'Test Shopee Store',
                    'shop_platform' => 'Shopee',
                ],
            ]);
            
            echo "   👤 Đơn {$order->order_number} → {$assignedStaff->name}\n";
        }
    }
    
    DB::commit();
    echo "\n✅ TEST THÀNH CÔNG!\n";
    echo "\n📊 Kết quả:\n";
    echo "   - Shop: Test Shopee Store (Shopee)\n";
    echo "   - Số đơn tạo: 15 đơn\n";
    echo "   - Thông báo gửi: " . Notification::where('type', 'new_order')->count() . " thông báo\n";
    echo "   - User business: {$businessUser->email}\n";
    
} catch (\Exception $e) {
    DB::rollBack();
    echo "\n❌ LỖI: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}

echo "\n🎯 Hãy đăng nhập và kiểm tra:\n";
echo "   1. Business user: test-business@example.com / password123\n";
echo "   2. Staff user: khoi@gmail.com / 15042004\n";
