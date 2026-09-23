<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveBalance extends Model
{
    protected $fillable = [
        'user_id', 'leave_type_id', 'year',
        'total_days', 'used_days', 'pending_days',
    ];

    protected $casts = [
        'total_days' => 'decimal:1',
        'used_days' => 'decimal:1',
        'pending_days' => 'decimal:1',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function getAvailableDaysAttribute(): float
    {
        return $this->total_days - $this->used_days - $this->pending_days;
    }
}
