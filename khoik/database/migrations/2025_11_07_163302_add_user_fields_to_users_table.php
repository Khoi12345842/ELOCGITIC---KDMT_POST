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
        Schema::table('users', function (Blueprint $table) {
            // Loại tài khoản: individual (cá nhân) hoặc business (doanh nghiệp)
            $table->enum('user_type', ['individual', 'business'])->default('individual')->after('email');
            
            // Thông tin doanh nghiệp (chỉ dùng khi user_type = business)
            $table->string('company_name')->nullable()->after('user_type');
            $table->string('tax_code')->nullable()->after('company_name'); // Mã số thuế
            $table->string('business_license')->nullable()->after('tax_code'); // Giấy phép kinh doanh
            $table->text('company_address')->nullable()->after('business_license');
            
            // Thông tin liên hệ
            $table->string('phone')->nullable()->after('company_address');
            $table->text('address')->nullable()->after('phone');
            
            // Thông tin shop (cho business user)
            $table->string('shop_id')->nullable()->after('address'); // ID shop liên kết (fake)
            $table->string('shop_name')->nullable()->after('shop_id');
            $table->string('shop_platform')->nullable()->after('shop_name'); // Shopee, Lazada, TikTok Shop
            
            // Chính sách giá cho business
            $table->decimal('discount_rate', 5, 2)->default(0)->after('shop_platform'); // % giảm giá
            $table->boolean('has_contract')->default(false)->after('discount_rate'); // Có hợp đồng không
            $table->date('contract_start_date')->nullable()->after('has_contract');
            $table->date('contract_end_date')->nullable()->after('contract_start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'user_type',
                'company_name',
                'tax_code',
                'business_license',
                'company_address',
                'phone',
                'address',
                'shop_id',
                'shop_name',
                'shop_platform',
                'discount_rate',
                'has_contract',
                'contract_start_date',
                'contract_end_date',
            ]);
        });
    }
};
