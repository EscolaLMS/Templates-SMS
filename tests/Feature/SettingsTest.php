<?php

namespace EscolaLms\TemplatesSms\Tests\Feature;

use EscolaLms\Core\Tests\CreatesUsers;
use EscolaLms\Settings\Database\Seeders\PermissionTableSeeder;
use EscolaLms\TemplatesSms\Enums\ConfigEnum;
use EscolaLms\TemplatesSms\Enums\SmsDriversEnum;
use EscolaLms\TemplatesSms\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Config;

class SettingsTest extends TestCase
{
    use CreatesUsers, WithoutMiddleware, WithFaker, DatabaseTransactions;

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

    public function testAdministrableConfigApi(): void
    {
        $configKey = ConfigEnum::CONFIG_KEY;

        $driver = $this->faker->randomElement(SmsDriversEnum::getValues());
        $requestBinPath = $this->faker->url;
        $twilioSid = $this->faker->uuid;
        $twilioToken = $this->faker->uuid;
        $twilioFrom = $this->faker->phoneNumber;

        $this->response = $this->actingAs($this->user, 'api')->json(
            'POST',
            '/api/admin/config',
            [
                'config' => [
                    [
                        'key' => "$configKey.default",
                        'value' => $driver,
                    ],
                    [
                        'key' => "$configKey.drivers.requestbin.path",
                        'value' => $requestBinPath,
                    ],
                    [
                        'key' => "$configKey.drivers.twilio.sid",
                        'value' => $twilioSid,
                    ],

                    [
                        'key' => "$configKey.drivers.twilio.token",
                        'value' => $twilioToken,
                    ],
                    [
                        'key' => "$configKey.drivers.twilio.from",
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
                'default' => [
                    'full_key' => "$configKey.default",
                    'key' => 'default',
                    'rules' => [
                        'required',
                        'string',
                        'in:' . implode(',', SmsDriversEnum::getValues()),
                    ],
                    'public' => false,
                    'readonly' => false,
                    'value' => $driver,
                ],
                'drivers' => [
                    'requestbin' => [
                        'path' => [
                            'full_key' => "$configKey.drivers.requestbin.path",
                            'key' => 'drivers.requestbin.path',
                            'rules' => [
                                'string',
                            ],
                            'public' => false,
                            'readonly' => false,
                            'value' => $requestBinPath,
                        ],
                    ],
                    'twilio' => [
                        'sid' => [
                            'full_key' => "$configKey.drivers.twilio.sid",
                            'key' => 'drivers.twilio.sid',
                            'rules' => [
                                'string',
                            ],
                            'public' => false,
                            'readonly' => false,
                            'value' => $twilioSid,
                        ],
                        'token' => [
                            'full_key' => "$configKey.drivers.twilio.token",
                            'key' => 'drivers.twilio.token',
                            'rules' => [
                                'string',
                            ],
                            'public' => false,
                            'readonly' => false,
                            'value' => $twilioToken,
                        ],
                        'from' => [
                            'full_key' => "$configKey.drivers.twilio.from",
                            'key' => 'drivers.twilio.from',
                            'rules' => [
                                'string',
                            ],
                            'public' => false,
                            'readonly' => false,
                            'value' => $twilioFrom,
                        ],
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
