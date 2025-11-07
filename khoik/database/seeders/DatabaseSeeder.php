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
            ])
            ->each(function ($order) {
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
            ->each(function ($order) use ($platforms, $businessUser) {
                $platform = $platforms[array_rand($platforms)];
                $order->update([
                    'shop_platform' => $platform,
                    'shop_name' => $businessUser->shop_name,
                    'shop_order_id' => strtoupper($platform) . rand(100000, 999999),
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
            ])
            ->each(function ($order) {
                $shipment = \App\Models\Shipment::factory()->create([
                    'order_id' => $order->id,
                ]);
                $this->createShipmentHistory($order, $shipment);
            });
    }

    /**
     * Tạo lịch sử vận chuyển cho shipment
     */
    private function createShipmentHistory($order, $shipment)
    {
        $historyCount = rand(3, 7);
        $statuses = [
            ['status' => 'Đơn hàng đã được tạo', 'location' => 'Hệ thống'],
            ['status' => 'Đã lấy hàng', 'location' => $order->sender_city],
            ['status' => 'Đang vận chuyển', 'location' => 'Trung tâm phân loại'],
            ['status' => 'Hàng đến khu vực giao', 'location' => $order->receiver_city],
            ['status' => 'Đang giao hàng', 'location' => $order->receiver_city],
            ['status' => 'Giao hàng thành công', 'location' => $order->receiver_address],
        ];
        
        $baseTime = $order->created_at;
        for ($i = 0; $i < min($historyCount, count($statuses)); $i++) {
            \App\Models\ShipmentHistory::create([
                'shipment_id' => $shipment->id,
                'status' => $statuses[$i]['status'],
                'location' => $statuses[$i]['location'],
                'description' => 'Cập nhật trạng thái: ' . $statuses[$i]['status'],
                'updated_by' => 'Hệ thống tự động',
                'happened_at' => $baseTime->addHours(rand(2, 12)),
            ]);
        }
    }
}
