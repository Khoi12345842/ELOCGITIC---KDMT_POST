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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('tracking_number')->unique(); // Mã vận đơn: SHIP20231107001
            
            // Lịch sử di chuyển
            $table->string('current_location')->nullable(); // Vị trí hiện tại
            $table->string('current_status'); // Trạng thái hiện tại
            $table->text('status_description')->nullable(); // Mô tả chi tiết
            
            // Thông tin vận chuyển
            $table->string('driver_name')->nullable(); // Tài xế
            $table->string('driver_phone')->nullable();
            $table->string('vehicle_number')->nullable(); // Biển số xe
            
            // Tọa độ GPS (để fake tracking)
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Thời gian dự kiến
            $table->timestamp('estimated_delivery')->nullable();
            $table->timestamp('actual_delivery')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
