<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tạo 2 user demo: 1 cá nhân, 1 doanh nghiệp
        $individualUser = User::factory()->create([
            'name' => 'Nguyễn Văn An',
            'email' => 'individual@example.com',
            'user_type' => 'individual',
            'phone' => '0912345678',
            'address' => '123 Nguyễn Trãi, Thanh Xuân, Hà Nội',
        ]);

        $businessUser = User::factory()->create([
            'name' => 'Trần Thị Bình',
            'email' => 'business@example.com',
            'user_type' => 'business',
            'company_name' => 'Công ty TNHH Thương mại ABC',
            'tax_code' => '0123456789',
            'company_address' => '456 Trần Hưng Đạo, Hoàn Kiếm, Hà Nội',
            'phone' => '0987654321',
            'address' => '456 Trần Hưng Đạo, Hoàn Kiếm, Hà Nội',
            'shop_platform' => 'Shopee',
            'shop_name' => 'ABC Official Store',
            'shop_id' => 'SHOP12345678',
            'discount_rate' => 10.00,
            'has_contract' => true,
            'contract_start_date' => now()->subMonths(6),
            'contract_end_date' => now()->addMonths(6),
        ]);

        $this->call(StaffUserSeeder::class);
        $staffUser = User::where('role', User::ROLE_STAFF)->first();

        // Tạo 30 đơn hàng FAKE (không có user) - đa dạng status
        \App\Models\Order::factory(30)
            ->create([
                'user_id' => null,
                'order_type' => 'fake',
            ])
            ->each(function ($order) {
                // Mỗi đơn hàng có 1 vận đơn
                $shipment = \App\Models\Shipment::factory()->create([
                    'order_id' => $order->id,
                ]);
                
                $this->createShipmentHistory($order, $shipment);
            });

        // Tạo 10 đơn từ individual user (manual)
        \App\Models\Order::factory(10)
            ->create([
                'user_id' => $individualUser->id,
                'order_type' => 'manual',
                'assigned_to' => $staffUser?->id,
            ])
            ->each(function ($order) {
                $order->update([
                    'route_code' => 'HN-' . rand(100, 999),
                    'scheduled_date' => now()->addDays(rand(0, 3)),
                ]);

                $shipment = \App\Models\Shipment::factory()->create([
                    'order_id' => $order->id,
                ]);
                $this->createShipmentHistory($order, $shipment);
            });

        // Tạo 15 đơn từ business user (shop_sync - giả lập từ shop)
        $platforms = ['Shopee', 'Lazada', 'TikTok Shop'];
        \App\Models\Order::factory(15)
            ->create([
                'user_id' => $businessUser->id,
                'order_type' => 'shop_sync',
            ])
            ->each(function ($order) use ($platforms, $businessUser, $staffUser) {
                $platform = $platforms[array_rand($platforms)];
                $order->update([
                    'shop_platform' => $platform,
                    'shop_name' => $businessUser->shop_name,
                    'shop_order_id' => strtoupper($platform) . rand(100000, 999999),
                    'assigned_to' => $staffUser?->id,
                    'route_code' => 'DN-' . rand(100, 999),
                    'scheduled_date' => now()->addDays(rand(1, 5)),
                ]);

                $shipment = \App\Models\Shipment::factory()->create([
                    'order_id' => $order->id,
                ]);
                $this->createShipmentHistory($order, $shipment);
            });

        // Tạo 5 đơn bulk (lên theo lô) từ business user
        \App\Models\Order::factory(5)
            ->create([
                'user_id' => $businessUser->id,
                'order_type' => 'bulk',
                'assigned_to' => $staffUser?->id,
            ])
            ->each(function ($order) {
                $order->update([
                    'route_code' => 'SG-' . rand(100, 999),
                    'scheduled_date' => now()->addDays(rand(2, 6)),
                ]);

                $shipment = \App\Models\Shipment::factory()->create([
                    'order_id' => $order->id,
                ]);
                $this->createShipmentHistory($order, $shipment);
            });
    }

    /**
     * Tạo lịch sử vận chuyển cho order
     */
    private function createShipmentHistory($order, $shipment)
    {
        // Map trạng thái mô tả sang trạng thái hệ thống
        $statusMapping = [
            'pending' => ['label' => 'Đơn hàng đã được tạo', 'location' => 'Hệ thống'],
            'confirmed' => ['label' => 'Đã xác nhận', 'location' => $order->sender_city],
            'picked_up' => ['label' => 'Đã lấy hàng', 'location' => $order->sender_city],
            'in_transit' => ['label' => 'Đang vận chuyển', 'location' => 'Trung tâm phân loại'],
            'out_delivery' => ['label' => 'Đang giao hàng', 'location' => $order->receiver_city],
            'delivered' => ['label' => 'Giao hàng thành công', 'location' => $order->receiver_address],
        ];
        
        // Lấy các trạng thái theo thứ tự cho đến trạng thái hiện tại
        $statusKeys = array_keys($statusMapping);
        $currentIndex = array_search($order->status, $statusKeys);
        
        if ($currentIndex === false) {
            return; // Nếu không tìm thấy trạng thái, bỏ qua
        }
        
        $baseTime = $order->created_at;
        
        // Tạo lịch sử cho các trạng thái từ đầu đến hiện tại
        for ($i = 0; $i <= $currentIndex; $i++) {
            $status = $statusKeys[$i];
            $info = $statusMapping[$status];
            
            \App\Models\ShipmentHistory::create([
                'order_id' => $order->id,
                'status' => $status,
                'location' => $info['location'],
                'notes' => 'Cập nhật trạng thái: ' . $info['label'],
                'updated_by' => null, // Hệ thống tự động
                'happened_at' => $baseTime->addHours(rand(2, 12)),
            ]);
        }
    }
}
