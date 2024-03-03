<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class ChildIssue extends Model
{
    use HasFactory;

    protected $table = 'child_issues';
    
    protected $fillable = [
        'child_id',
        'skill_id',
        'points',
        'created_at',
        'updated_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
