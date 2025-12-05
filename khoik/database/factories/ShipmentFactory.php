<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shipment>
 */
class ShipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $locations = [
            'Kho Hà Nội',
            'Trung tâm phân loại Miền Bắc',
            'Đang vận chuyển đến Đà Nẵng',
            'Hub Hồ Chí Minh',
            'Đang giao tại Quận 1',
            'Bưu cục Hoàn Kiếm'
        ];
        
        $statuses = [
            'Đơn hàng đã được tạo',
            'Đã lấy hàng từ người gửi',
            'Đang vận chuyển',
            'Hàng đến trung tâm phân loại',
            'Đang giao hàng',
            'Giao hàng thành công'
        ];
        
        // Tọa độ giả (Việt Nam)
        $lat = fake()->latitude(8.0, 23.5);
        $lng = fake()->longitude(102.0, 109.5);
        
        return [
            'tracking_number' => 'SHIP' . date('Ymd') . fake()->unique()->numberBetween(10000, 99999),
            'current_location' => fake()->randomElement($locations),
            'current_status' => fake()->randomElement($statuses),
            'status_description' => fake()->sentence(),
            
            // Tài xế (có thể null nếu chưa giao)
            'driver_name' => fake()->optional(0.6)->name(),
            'driver_phone' => fake()->optional(0.6)->numerify('09########'),
            'vehicle_number' => fake()->optional(0.6)->bothify('??-#### ##'),
            
            // GPS
            'latitude' => $lat,
            'longitude' => $lng,
            
            // Thời gian
            'estimated_delivery' => fake()->dateTimeBetween('now', '+3 days'),
            'actual_delivery' => fake()->optional(0.3)->dateTimeBetween('-2 days', 'now'),
            
            'created_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
