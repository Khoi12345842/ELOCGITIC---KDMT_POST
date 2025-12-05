<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // Mã đơn hàng: ORD20231107001
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Khách hàng (nullable cho đơn fake)
            
            // Thông tin người gửi
            $table->string('sender_name');
            $table->string('sender_phone');
            $table->text('sender_address');
            $table->string('sender_city');
            
            // Thông tin người nhận
            $table->string('receiver_name');
            $table->string('receiver_phone');
            $table->text('receiver_address');
            $table->string('receiver_city');
            
            // Thông tin hàng hóa
            $table->string('package_description')->nullable();
            $table->decimal('weight', 8, 2)->default(0); // kg
            $table->decimal('cod_amount', 10, 2)->default(0); // Tiền thu hộ
            $table->decimal('shipping_fee', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            
            // Trạng thái
            $table->enum('status', [
                'pending',      // Chờ xử lý
                'confirmed',    // Đã xác nhận
                'picked_up',    // Đã lấy hàng
                'in_transit',   // Đang vận chuyển
                'out_delivery', // Đang giao hàng
                'delivered',    // Đã giao hàng
                'cancelled',    // Đã hủy
                'returned'      // Hoàn trả
            ])->default('pending');
            
            $table->text('notes')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
