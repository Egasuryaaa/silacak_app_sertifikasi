<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'tracking_number',
        'customer_id',
        'origin_branch_id',
        'dest_branch_id',
        'sender_name',
        'sender_phone',
        'receiver_name',
        'receiver_phone',
        'receiver_address',
        'service_code',
        'actual_weight',
        'length_cm',
        'width_cm',
        'height_cm',
        'chargeable_weight',
        'goods_value',
        'base_fare',
        'discount_amount',
        'insurance_fee',
        'total_fee',
        'current_status',
    ];

    protected $casts = [
        'actual_weight'     => 'float',
        'goods_value'       => 'float',
        'base_fare'         => 'float',
        'discount_amount'   => 'float',
        'insurance_fee'     => 'float',
        'total_fee'         => 'float',
        'chargeable_weight' => 'integer',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function originBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'origin_branch_id');
    }

    public function destBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'dest_branch_id');
    }

    public function trackingHistories(): HasMany
    {
        return $this->hasMany(TrackingHistory::class)->orderBy('recorded_at', 'asc');
    }
}