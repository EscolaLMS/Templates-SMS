<?php

namespace EscolaLms\TemplatesSms\Tests\Feature;

use EscolaLms\Core\Tests\CreatesUsers;
use EscolaLms\Settings\Database\Seeders\PermissionTableSeeder;
use EscolaLms\TemplatesSms\Enums\ConfigEnum;
use EscolaLms\TemplatesSms\Providers\SettingsServiceProvider;
use EscolaLms\TemplatesSms\Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Config;

class SettingsTest extends TestCase
{
    use CreatesUsers, WithoutMiddleware, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        if (!class_exists(\EscolaLms\Settings\EscolaLmsSettingsServiceProvider::class)) {
            $this->markTestSkipped('Settings package not installed');
        }

        $this->seed(PermissionTableSeeder::class);
        Config::set('escola_settings.use_database', true);

        $this->user = config('auth.providers.users.model')::factory()->create();
        $this->user->guard_name = 'api';
        $this->user->assignRole('admin');
    }

    protected function tearDown(): void
    {
        \EscolaLms\Settings\Models\Config::truncate();
    }

    public function testAdministrableConfigApi(): void
    {
        $configKey = ConfigEnum::CONFIG_KEY;

        $twilioSid = $this->faker->uuid;
        $twilioToken = $this->faker->uuid;
        $twilioFrom = $this->faker->phoneNumber;

        $this->response = $this->actingAs($this->user, 'api')->json(
            'POST',
            '/api/admin/config',
            [
                'config' => [
                    [
                        'key' => "$configKey.twilio.sid",
                        'value' => $twilioSid,
                    ],
                    [
                        'key' => "$configKey.twilio.token",
                        'value' =>  $twilioToken,
                    ],
                    [
                        'key' => "$configKey.twilio.from",
                        'value' => $twilioFrom,
                    ],
                ]
            ]
        );
        $this->response->assertOk();

        $this->response = $this->actingAs($this->user, 'api')->json(
            'GET',
            '/api/admin/config'
        );

        $this->response->assertOk();

        $this->response->assertJsonFragment([
            $configKey => [
                'twilio' => [
                    'sid' => [
                        'full_key' => "$configKey.twilio.sid",
                        'key' => 'twilio.sid',
                        'rules' => [
                            'required',
                            'string'
                        ],
                        'public' => false,
                        'readonly' => false,
                        'value' => $twilioSid,
                    ],
                    'token' => [
                        'full_key' => "$configKey.twilio.token",
                        'key' => 'twilio.token',
                        'rules' => [
                            'required',
                            'string'
                        ],
                        'public' => false,
                        'value' => $twilioToken,
                        'readonly' => false,
                    ],
                    'from' => [
                        'full_key' => "$configKey.twilio.from",
                        'key' => 'twilio.from',
                        'rules' => [
                            'required',
                            'string'
                        ],
                        'public' => false,
                        'value' => $twilioFrom,
                        'readonly' => false,
                    ],
                ],
            ],
        ]);

        $this->response = $this->json(
            'GET',
            '/api/config'
        );

        $this->response->assertOk();
        $this->response->assertJsonMissing([$configKey]);
    }
}
