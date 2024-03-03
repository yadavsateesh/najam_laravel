<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class ShareChild extends Model
{
    use HasFactory;

    protected $table = 'child_shares';
    
    protected $fillable = [
        'is_skill',
        'is_issue',
        'is_score',
        'is_gifts',
        'qr_code',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
