<?php

namespace EscolaLms\TemplatesSms\Tests;

use EscolaLms\TemplatesSms\EscolaLmsTemplatesSmsServiceProvider;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\PassportServiceProvider;
use Spatie\Permission\PermissionServiceProvider;

class TestCase extends \EscolaLms\Core\Tests\TestCase
{
    use DatabaseTransactions;

    protected function getPackageProviders($app): array
    {
        return [
            ...parent::getPackageProviders($app),
            PassportServiceProvider::class,
            PermissionServiceProvider::class,
            EscolaLmsTemplatesSmsServiceProvider::class,
        ];
    }
}
