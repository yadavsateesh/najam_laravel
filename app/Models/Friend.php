<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Friend extends Model
{
    use HasFactory;

    protected $table = 'friends';
    
    protected $fillable = [
        'user_id',
        'child_id',
        'reward_name',
        'reward_points',
        'reward_image',
        'created_at',
        'updated_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
