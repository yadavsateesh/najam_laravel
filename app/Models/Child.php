<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Child extends Model
{
    use HasFactory;

    protected $table = 'childs';
    
    protected $fillable = [
        'name',
        'gender',
        'date_of_birth',
        'image',
        'created_at',
        'updated_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
