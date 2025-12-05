<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cities = ['Hà Nội', 'Hồ Chí Minh', 'Đà Nẵng', 'Hải Phòng', 'Cần Thơ', 'Nha Trang', 'Huế'];
        $statuses = ['pending', 'confirmed', 'picked_up', 'in_transit', 'out_delivery', 'delivered'];
        $status = fake()->randomElement($statuses);
        
        $shippingFee = fake()->randomFloat(2, 20000, 100000);
        $codAmount = fake()->randomFloat(2, 0, 5000000);
        
        return [
            'order_number' => 'ORD' . date('Ymd') . fake()->unique()->numberBetween(1000, 9999),
            'user_id' => 1, // Sẽ tạo user sau
            
            // Người gửi
            'sender_name' => fake()->name(),
            'sender_phone' => '0' . fake()->numberBetween(900000000, 999999999),
            'sender_address' => fake()->streetAddress(),
            'sender_city' => fake()->randomElement($cities),
            
            // Người nhận
            'receiver_name' => fake()->name(),
            'receiver_phone' => '0' . fake()->numberBetween(900000000, 999999999),
            'receiver_address' => fake()->streetAddress(),
            'receiver_city' => fake()->randomElement($cities),
            
            // Hàng hóa
            'package_description' => fake()->randomElement([
                'Quần áo',
                'Đồ điện tử',
                'Sách vở',
                'Thực phẩm',
                'Mỹ phẩm',
                'Phụ kiện điện thoại'
            ]),
            'weight' => fake()->randomFloat(2, 0.5, 50),
            'cod_amount' => $codAmount,
            'shipping_fee' => $shippingFee,
            'total_amount' => $codAmount + $shippingFee,
            
            // Trạng thái
            'status' => $status,
            'notes' => fake()->optional()->sentence(),
            'delivered_at' => $status === 'delivered' ? fake()->dateTimeBetween('-7 days', 'now') : null,
            'created_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
