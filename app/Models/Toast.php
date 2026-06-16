<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Toast extends Model
{
    use HasFactory;

    protected $fillable = [
        'message', 
        'type', 
        'duration', 
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}