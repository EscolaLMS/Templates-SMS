<?php

namespace EscolaLms\TemplatesSms\Tests\Feature;

use EscolaLms\Core\Tests\ApiTestTrait;
use EscolaLms\Core\Tests\CreatesUsers;
use EscolaLms\Templates\Facades\Template;
use EscolaLms\Templates\Repository\Contracts\TemplateRepositoryContract;
use EscolaLms\TemplatesSms\Database\Seeders\TemplateSmsSeeder;
use EscolaLms\TemplatesSms\Tests\Mocks\TestEvent;
use EscolaLms\TemplatesSms\Tests\Mocks\TestVariables;
use EscolaLms\TemplatesSms\Tests\TestCase;
use EscolaLms\TemplatesSms\Core\SmsChannel;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;

class SmsChannelTest extends TestCase
{
    use CreatesUsers, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
        Template::register(TestEvent::class, SmsChannel::class, TestVariables::class);
        $this->seed(TemplateSmsSeeder::class);
    }

    public function testPreview()
    {
        Event::fake();
        Notification::fake();

        $admin = $this->makeAdmin();

        $template = app(TemplateRepositoryContract::class)->findTemplateDefault(TestEvent::class, SmsChannel::class);

        $preview = Template::sendPreview($admin, $template);
        $arr = $preview->toArray();
        
        $this->assertStringContainsString($admin->email, $arr['data']['content']);
        $this->assertTrue($arr['sent']);
    }
}
