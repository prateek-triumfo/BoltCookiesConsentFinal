<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsentLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'domain_id',
        'domain',
        'cookie_id',
        'ip_address',
        'user_agent',
        'device_type',
        'language',
        'consent_data',
        'categories',
        'consented_at'
    ];

    protected $casts = [
        'consent_data' => 'array',
        'categories' => 'array',
        'consented_at' => 'datetime'
    ];

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }
}