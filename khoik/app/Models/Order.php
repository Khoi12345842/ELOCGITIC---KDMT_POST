<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;
    
    public const STATUS_LABELS = [
        'pending' => 'Chờ xử lý',
        'confirmed' => 'Đã xác nhận',
        'picked_up' => 'Đã lấy hàng',
        'in_transit' => 'Đang vận chuyển',
        'out_delivery' => 'Đang giao hàng',
        'delivered' => 'Đã giao hàng',
        'cancelled' => 'Đã hủy',
        'returned' => 'Hoàn trả',
    ];

    public const FAILURE_STATUSES = ['cancelled', 'returned'];

    protected $fillable = [
        'order_number', 'user_id', 'assigned_to', 'order_type', 'shop_order_id', 'shop_name', 'shop_platform',
        'sender_name', 'sender_phone', 'sender_address', 'sender_city',
        'receiver_name', 'receiver_phone', 'receiver_address', 'receiver_city',
        'package_description', 'weight', 'cod_amount', 'shipping_fee', 'total_amount',
        'status', 'notes', 'delivered_at', 'route_code', 'scheduled_date'
    ];
    
    protected $casts = [
        'delivered_at' => 'datetime',
        'weight' => 'decimal:2',
        'cod_amount' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'scheduled_date' => 'date',
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

    public function assignedStaff()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function shipmentHistories()
    {
        return $this->hasMany(ShipmentHistory::class)->orderBy('happened_at', 'desc');
    }

    public function incidentReports()
    {
        return $this->hasMany(IncidentReport::class)->orderByDesc('created_at');
    }
    
    // Helper methods
    public function getStatusLabelAttribute()
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
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
