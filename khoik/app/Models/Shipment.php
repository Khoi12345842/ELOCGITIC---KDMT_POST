<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    /** @use HasFactory<\Database\Factories\ShipmentFactory> */
    use HasFactory;
    
    protected $fillable = [
        'order_id', 'tracking_number',
        'current_location', 'current_status', 'status_description',
        'driver_name', 'driver_phone', 'vehicle_number',
        'latitude', 'longitude',
        'estimated_delivery', 'actual_delivery'
    ];
    
    protected $casts = [
        'estimated_delivery' => 'datetime',
        'actual_delivery' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];
    
    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    
    public function histories()
    {
        return $this->hasMany(ShipmentHistory::class)->orderBy('happened_at', 'desc');
    }
}
