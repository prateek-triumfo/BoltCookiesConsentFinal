<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Domain extends Model
{
    use HasFactory;

    // Define which fields are mass assignable
    protected $fillable = [
        'name', 
        'description',
    ];

    public function consentLogs()
    {
        return $this->hasMany(ConsentLog::class);
    }

    public static function findOrCreateByName($name)
    {
        return static::firstOrCreate(
            ['name' => $name],
            ['description' => 'Auto-generated domain entry']
        );
    }
}