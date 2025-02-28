<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsentLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'cookie_id',
        'consent_data',
        'ip_address',
        'user_agent',
        'consented_at',
    ];

    protected $casts = [
        'consent_data' => 'array',
        'consented_at' => 'datetime',
    ];
}