<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentHistory extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'order_id', 
        'status', 
        'location', 
        'notes', 
        'updated_by', 
        'happened_at'
    ];
    
    protected $casts = [
        'happened_at' => 'datetime',
    ];
    
    // Relationship với Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    
    // Relationship với User (nhân viên cập nhật)
    public function updatedByUser()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
