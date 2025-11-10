<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentReport extends Model
{
    /** @use HasFactory<\Database\Factories\IncidentReportFactory> */
    use HasFactory;

    protected $fillable = [
        'order_id',
        'reported_by',
        'issue_type',
        'summary',
        'description',
        'status',
        'resolution_notes',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public const STATUS_OPEN = 'open';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_RESOLVED = 'resolved';
    public const STATUS_CLOSED = 'closed';

    public static function statusOptions(): array
    {
        return [
            self::STATUS_OPEN => 'Mới ghi nhận',
            self::STATUS_IN_PROGRESS => 'Đang xử lý',
            self::STATUS_RESOLVED => 'Đã xử lý',
            self::STATUS_CLOSED => 'Đã đóng',
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
}
