<?php

namespace EscolaLms\TemplatesSms\Tests\Feature;

use Aloha\Twilio\Dummy;
use Aloha\Twilio\Twilio;
use EscolaLms\Core\Tests\ApiTestTrait;
use EscolaLms\Core\Tests\CreatesUsers;
use EscolaLms\Templates\Events\EventWrapper;
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

    public function testSmsChannelNotificationDisabled()
    {
        Event::fake();
        $user = $this->makeStudent();
        $admin = $this->makeAdmin();

        $template = app(TemplateRepositoryContract::class)->findTemplateDefault(TestEvent::class, SmsChannel::class);

        $event = new TestEvent($admin, $user);
        $status = SmsChannel::send(new EventWrapper($event), $template->sections->toArray());

        $this->assertFalse($status);
    }

    public function testSmsChannelNotificationEnabled()
    {
        Event::fake();

        $user = $this->makeStudent(
            [
                'first_name' => 'Test',
                'last_name' => 'Test',
                'phone' => '+48600600601',
                'notification_channels' => json_encode([
                    "EscolaLms\\TemplatesEmail\\Core\\EmailChannel",
                    "EscolaLms\\TemplatesSms\\Core\\SmsChannel"
                ])
            ]
        );
        $admin = $this->makeAdmin();

        $template = app(TemplateRepositoryContract::class)->findTemplateDefault(TestEvent::class, SmsChannel::class);

        $this->mock('overload:' . Twilio::class)
            ->shouldReceive('message')
            ->andReturn((new Dummy())->message($user->phone, $template->sections->first()->toArray()['content']));

        $event = new TestEvent($user, $admin);
        $status = SmsChannel::send(new EventWrapper($event), $template->sections->first()->toArray());

        $this->assertTrue($status);
    }
}
