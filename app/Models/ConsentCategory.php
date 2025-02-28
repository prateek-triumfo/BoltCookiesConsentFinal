<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsentCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'key',
        'description',
        'is_required',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];
}