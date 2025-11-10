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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Người nhận
            $table->string('type'); // new_order, status_update, delivery_success, etc
            $table->string('title'); // Tiêu đề ngắn
            $table->text('message'); // Nội dung chi tiết
            $table->json('data')->nullable(); // Dữ liệu bổ sung (order_id, order_number, etc)
            $table->timestamp('read_at')->nullable(); // Thời gian đã đọc
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('read_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
