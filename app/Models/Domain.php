<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Domain extends Model
{
    use HasFactory;

    // Define which fields are mass assignable
    protected $fillable = [
        'name', 
        'description',
        'api_key',
        'is_active',
        'banner_settings',
        'script_id',
        'last_used_at',
        'consent_count',
        'status',
        'user_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'banner_settings' => 'array',
        'last_used_at' => 'datetime',
        'consent_count' => 'integer',
        'status' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($domain) {
            $domain->script_id = Str::random(32);
            $domain->api_key = Str::random(64);
        });

        static::created(function ($domain) {
            // Create default banner settings for the domain
            $defaultSettings = BannerSetting::getDefaultSettings();
            $defaultSettings['domain_id'] = $domain->id;
            BannerSetting::create($defaultSettings);
        });
    }

    public function consentLogs()
    {
        return $this->hasMany(ConsentLog::class);
    }

    public function bannerSetting()
    {
        return $this->hasOne(BannerSetting::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function findOrCreateByName($name)
    {
        return static::firstOrCreate(
            ['name' => $name],
            [
                'description' => 'Auto-generated domain entry',
                'api_key' => Str::random(32),
                'script_id' => Str::random(16)
            ]
        );
    }

    public function generateApiKey()
    {
        $this->api_key = Str::random(32);
        $this->save();
        return $this->api_key;
    }

    public function generateScriptId()
    {
        $this->script_id = Str::random(16);
        $this->save();
        return $this->script_id;
    }

    public function incrementConsentCount()
    {
        $this->increment('consent_count');
        $this->update(['last_used_at' => now()]);
    }

    public function getEmbedScript()
    {
        if (!$this->is_active) {
            return null;
        }

        return sprintf(
            '<script id="%s" src="%s/consent/embed.js"></script>',
            $this->script_id,
            config('app.url')
        );
    }

    public function getEmbedCode()
    {
        if (!$this->is_active) {
            return null;
        }

        return sprintf(
            '<!-- BoltConsent Banner -->
<script>
    window.BOLT_CONSENT_CONFIG = {
        scriptId: "%s",
        apiKey: "%s"
    };
</script>
<script id="%s" src="%s/consent/embed.js"></script>',
            $this->script_id,
            $this->api_key,
            $this->script_id,
            config('app.url')
        );
    }

    public function getBannerSetting()
    {
        return $this->bannerSetting ?? BannerSetting::create([
            'domain_id' => $this->id,
            ...BannerSetting::getDefaultSettings()
        ]);
    }
}