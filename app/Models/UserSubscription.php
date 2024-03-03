<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class UserSubscription extends Model
{
    use HasFactory;

    protected $table = 'user_subscription';
    
    protected $fillable = [
        'user_id',
        'subscription_start_date',
        'subscription_end_date',
        'status',
        'created_at',
        'updated_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
