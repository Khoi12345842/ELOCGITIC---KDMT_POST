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
            $table->foreignId('shipment_id')->constrained()->onDelete('cascade');
            
            $table->string('status'); // pending, picked_up, in_transit, delivered...
            $table->string('location'); // Hà Nội Hub, Đang giao tại Hoàn Kiếm...
            $table->text('description'); // Mô tả chi tiết
            $table->string('updated_by')->nullable(); // Nhân viên cập nhật
            
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
