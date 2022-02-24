<?php

namespace EscolaLms\TemplatesSms\Tests;

use EscolaLms\Consultations\EscolaLmsConsultationsServiceProvider;
use EscolaLms\Core\Models\User;
use EscolaLms\Settings\EscolaLmsSettingsServiceProvider;
use EscolaLms\TemplatesSms\EscolaLmsTemplatesSmsServiceProvider;
use EscolaLms\Templates\Database\Seeders\PermissionTableSeeder as TemplatesPermissionTableSeeder;
use EscolaLms\Templates\EscolaLmsTemplatesServiceProvider;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\PassportServiceProvider;
use Spatie\Permission\PermissionServiceProvider;

class TestCase extends \EscolaLms\Core\Tests\TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(TemplatesPermissionTableSeeder::class);
    }

    protected function getPackageProviders($app): array
    {
        return [
            ...parent::getPackageProviders($app),
            PassportServiceProvider::class,
            PermissionServiceProvider::class,
            EscolaLmsTemplatesServiceProvider::class,
            EscolaLmsSettingsServiceProvider::class,
            EscolaLmsConsultationsServiceProvider::class,
            EscolaLmsTemplatesSmsServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('auth.providers.users.model', User::class);
        $app['config']->set('passport.client_uuids', true);

        $app['config']->set('escolalms_templates_sms.twilio.sid', 'XYZ');
        $app['config']->set('escolalms_templates_sms.twilio.token', 'XYZ');
        $app['config']->set('escolalms_templates_sms.twilio.from', '+49600600600');
        $app['config']->set('escolalms_templates_sms.twilio.ssl_verify', true);
    }
}
