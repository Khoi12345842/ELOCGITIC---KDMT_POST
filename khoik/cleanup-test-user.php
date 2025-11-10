<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Order;
use App\Models\Notification;

echo "🧹 Xóa user test cũ...\n";

$user = User::where('email', 'test-business@example.com')->first();

if ($user) {
    echo "Tìm thấy user: {$user->email}\n";
    
    // Xóa đơn hàng
    $orderCount = Order::where('user_id', $user->id)->count();
    Order::where('user_id', $user->id)->delete();
    echo "✅ Xóa {$orderCount} đơn hàng\n";
    
    // Xóa thông báo
    $notifCount = Notification::where('user_id', $user->id)->count();
    Notification::where('user_id', $user->id)->delete();
    echo "✅ Xóa {$notifCount} thông báo\n";
    
    // Xóa user
    $user->delete();
    echo "✅ Xóa user\n";
    
    echo "\n✅ Hoàn tất!\n";
} else {
    echo "❌ Không tìm thấy user test-business@example.com\n";
}
