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
        Schema::table('orders', function (Blueprint $table) {
            // Loại đơn: manual (lên đơn thủ công), bulk (lên đơn theo lô), shop_sync (đồng bộ từ shop), fake (đơn fake)
            $table->enum('order_type', ['manual', 'bulk', 'shop_sync', 'fake'])->default('fake')->after('user_id');
            
            // Thông tin shop (cho đơn từ shop)
            $table->string('shop_order_id')->nullable()->after('order_type'); // Mã đơn từ shop
            $table->string('shop_name')->nullable()->after('shop_order_id');
            $table->string('shop_platform')->nullable()->after('shop_name'); // Shopee, Lazada, TikTok
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['order_type', 'shop_order_id', 'shop_name', 'shop_platform']);
        });
    }
};
