<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'class_name',
        'room_name',
        'booking_date',
        'start_time',
        'end_time',
        'status',
        'payment_cost',
        'payment_status',
        'payment_method',
        'payment_transaction_id',
        'earn_points',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
        ];
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}

