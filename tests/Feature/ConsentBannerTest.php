<?php

namespace Tests\Feature;

use App\Models\ConsentCategory;
use App\Models\BannerSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ConsentBannerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        // Clear all cookies before each test
        $this->withCookies([]);

        // Create banner settings
        BannerSetting::create([
            'title' => 'Cookie Consent',
            'description' => 'We use cookies to improve your experience.',
            'position' => 'bottom',
            'layout' => 'box',
            'theme' => 'light',
            'primary_color' => '#007bff',
            'secondary_color' => '#6c757d',
            'background_color' => '#ffffff',
            'text_color' => '#000000',
            'button_style' => 'filled',
            'show_reject_button' => true,
            'show_settings_button' => true,
            'accept_button_text' => 'Accept All',
            'reject_button_text' => 'Reject All',
            'margin' => 20,
            'padding' => 15,
            'z_index' => 1000,
            'border_radius' => '4px',
            'font_size' => '14px',
            'font_family' => 'Arial, sans-serif',
        ]);

        // Create consent categories
        ConsentCategory::create([
            'name' => 'Necessary',
            'key' => 'necessary',
            'description' => 'Essential cookies for the website to function.',
            'is_required' => true,
        ]);

        ConsentCategory::create([
            'name' => 'Analytics',
            'key' => 'statistics',
            'description' => 'Cookies that help us understand how you use our website.',
            'is_required' => false,
        ]);
    }

    protected function tearDown(): void
    {
        // Clear all cookies after each test
        $this->withCookies([]);
        parent::tearDown();
    }

    /** @test */
    public function test_consent_banner_displays_correctly()
    {
        $response = $this->get('/consent/banner');

        $response->assertStatus(200)
                ->assertViewIs('consent.banner')
                ->assertViewHas('bannerSettings')
                ->assertViewHas('categories')
                ->assertSee('Cookie Consent')
                ->assertSee('We use cookies to improve your experience.')
                ->assertSee('Necessary')
                ->assertSee('Analytics');
    }

    /** @test */
    public function test_can_save_consent_preferences()
    {
        $consentData = [
            'consent' => [
                'necessary' => true,
                'statistics' => false
            ]
        ];

        $response = $this->postJson('/consent/save', $consentData);

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Consent preferences saved successfully',
                    'preferences' => [
                        'necessary' => true,
                        'statistics' => false
                    ]
                ]);
    }

    /** @test */
    public function test_required_categories_cannot_be_disabled()
    {
        $consentData = [
            'consent' => [
                'necessary' => false, // Trying to disable required category
                'statistics' => true
            ]
        ];

        $response = $this->postJson('/consent/save', $consentData);

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Consent preferences saved successfully',
                    'preferences' => [
                        'necessary' => true, // Should still be true
                        'statistics' => true
                    ]
                ]);
    }

    /** @test */
    public function test_can_retrieve_consent_preferences()
    {
        // First save some preferences
        $this->postJson('/consent/save', [
            'consent' => [
                'necessary' => true,
                'statistics' => true
            ]
        ]);

        // Then retrieve them
        $response = $this->getJson('/consent/preferences');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'consented',
                    'rejected',
                    'preferences' => [
                        'necessary',
                        'statistics'
                    ]
                ])
                ->assertJson([
                    'consented' => false, // Initially false until explicitly accepted
                    'rejected' => false,
                    'preferences' => [
                        'necessary' => true,
                        'statistics' => false // Default to false for non-required categories
                    ]
                ]);
    }

    /** @test */
    public function test_consent_state_changes_when_accepted()
    {
        // First save preferences with explicit consent
        $response = $this->postJson('/consent/save', [
            'consent' => [
                'necessary' => true,
                'statistics' => true
            ],
            'action' => 'accept_all'
        ]);

        // Get the cookie ID from the response
        $cookieId = $response->getCookie('consent_cookie_id')->getValue();
        
        // Then retrieve them with the cookies
        $response = $this->withCookies([
            'consent_accepted' => 'true',
            'consent_cookie_id' => $cookieId,
            'consent_preferences' => json_encode([
                'necessary' => true,
                'statistics' => true
            ])
        ])->getJson('/consent/preferences');

        $response->assertStatus(200)
                ->assertJson([
                    'consented' => true,
                    'rejected' => false,
                    'preferences' => [
                        'necessary' => true,
                        'statistics' => true
                    ]
                ]);
    }

    /** @test */
    public function test_consent_state_changes_when_rejected()
    {
        // First reject all cookies except necessary
        $response = $this->postJson('/consent/save', [
            'consent' => [
                'necessary' => true,
                'statistics' => false
            ],
            'action' => 'reject_all'
        ]);

        // Get the cookie ID from the response
        $cookieId = $response->getCookie('consent_cookie_id')->getValue();
        
        // Then retrieve them with the cookies
        $response = $this->withCookies([
            'consent_rejected' => 'true',
            'consent_cookie_id' => $cookieId,
            'consent_preferences' => json_encode([
                'necessary' => true,
                'statistics' => false
            ])
        ])->getJson('/consent/preferences');

        $response->assertStatus(200)
                ->assertJson([
                    'consented' => false,
                    'rejected' => true,
                    'preferences' => [
                        'necessary' => true, // Necessary cookies are always true
                        'statistics' => false
                    ]
                ]);
    }

    /** @test */
    public function test_banner_settings_are_applied()
    {
        $response = $this->get('/consent/banner');

        $response->assertStatus(200)
                ->assertSee('bottom') // Position
                ->assertSee('#007bff') // Primary color
                ->assertSee('#ffffff') // Background color
                ->assertSee('Accept All') // Accept button text
                ->assertSee('Reject All'); // Reject button text
    }

    /** @test */
    public function test_manage_preferences_page_loads()
    {
        $response = $this->get('/consent/manage');

        $response->assertStatus(200)
                ->assertViewIs('consent.manage')
                ->assertSee('Necessary')
                ->assertSee('Analytics')
                ->assertSee('Required'); // Badge for necessary cookies
    }
} 