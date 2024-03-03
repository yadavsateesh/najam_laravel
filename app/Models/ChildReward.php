<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class ChildReward extends Model
{
    use HasFactory;

    protected $table = 'child_rewards';
    
    protected $fillable = [
        'child_id',
        'reward_name',
        'reward_points',
        'reward_image',
        'status',
        'created_at',
        'updated_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
