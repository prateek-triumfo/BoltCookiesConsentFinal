<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsentLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'domain_id',
        'consent_data',
        'user_agent',
        'ip_address',
        'domain',
        'cookie_id',
        'device_type',
        'language',
        'consented_at'
    ];

    protected $casts = [
        'consent_data' => 'array',
        'consented_at' => 'datetime'
    ];

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }
}