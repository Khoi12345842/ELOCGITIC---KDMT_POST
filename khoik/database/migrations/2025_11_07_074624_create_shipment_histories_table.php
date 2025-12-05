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
        Schema::create('shipment_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            
            $table->string('status'); // pending, picked_up, in_transit, delivered...
            $table->string('location')->nullable(); // Địa chỉ/GPS hiện tại
            $table->text('notes')->nullable(); // Ghi chú chi tiết
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null'); // Nhân viên cập nhật
            
            $table->timestamp('happened_at'); // Thời gian xảy ra sự kiện
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipment_histories');
    }
};
