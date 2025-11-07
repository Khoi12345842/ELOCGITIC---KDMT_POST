<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;
    
    protected $fillable = [
        'order_number', 'user_id', 'order_type', 'shop_order_id', 'shop_name', 'shop_platform',
        'sender_name', 'sender_phone', 'sender_address', 'sender_city',
        'receiver_name', 'receiver_phone', 'receiver_address', 'receiver_city',
        'package_description', 'weight', 'cod_amount', 'shipping_fee', 'total_amount',
        'status', 'notes', 'delivered_at'
    ];
    
    protected $casts = [
        'delivered_at' => 'datetime',
        'weight' => 'decimal:2',
        'cod_amount' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];
    
    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function shipment()
    {
        return $this->hasOne(Shipment::class);
    }
    
    // Helper methods
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'Chờ xử lý',
            'confirmed' => 'Đã xác nhận',
            'picked_up' => 'Đã lấy hàng',
            'in_transit' => 'Đang vận chuyển',
            'out_delivery' => 'Đang giao hàng',
            'delivered' => 'Đã giao hàng',
            'cancelled' => 'Đã hủy',
            'returned' => 'Hoàn trả',
        };
    }

    public function getOrderTypeLabelAttribute()
    {
        return match($this->order_type) {
            'manual' => 'Đơn thủ công',
            'bulk' => 'Đơn theo lô',
            'shop_sync' => 'Đồng bộ từ shop',
            'fake' => 'Đơn demo',
        };
    }

    public function isFake(): bool
    {
        return $this->order_type === 'fake';
    }

    public function isShopSync(): bool
    {
        return $this->order_type === 'shop_sync';
    }
}
