<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
    'role',
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
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'has_contract' => 'boolean',
            'contract_start_date' => 'date',
            'contract_end_date' => 'date',
            'discount_rate' => 'decimal:2',
        ];
    }

    public const ROLE_CUSTOMER = 'customer';
    public const ROLE_STAFF = 'staff';

    /**
     * Check if user is business type
     */
    public function isBusiness(): bool
    {
        return $this->user_type === 'business';
    }

    /**
     * Check if user is individual type
     */
    public function isIndividual(): bool
    {
        return $this->user_type === 'individual';
    }

    public function isStaff(): bool
    {
        return $this->role === self::ROLE_STAFF;
    }

    /**
     * Get orders relationship
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function assignedOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'assigned_to');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class)->orderBy('created_at', 'desc');
    }

    public function unreadNotifications()
    {
        return $this->hasMany(Notification::class)->whereNull('read_at')->orderBy('created_at', 'desc');
    }

    public function incidentReports()
    {
        return $this->hasMany(IncidentReport::class, 'reported_by');
    }
}
