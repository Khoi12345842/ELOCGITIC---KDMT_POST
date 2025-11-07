<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentHistory extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'shipment_id', 'status', 'location', 
        'description', 'updated_by', 'happened_at'
    ];
    
    protected $casts = [
        'happened_at' => 'datetime',
    ];
    
    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }
}
