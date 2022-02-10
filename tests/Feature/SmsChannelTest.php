<?php

namespace EscolaLms\TemplatesSms\Tests\Feature;

use EscolaLms\Core\Tests\ApiTestTrait;
use EscolaLms\Core\Tests\CreatesUsers;
use EscolaLms\Templates\Events\EventWrapper;
use EscolaLms\Templates\Facades\Template;
use EscolaLms\Templates\Repository\Contracts\TemplateRepositoryContract;
use EscolaLms\TemplatesSms\Database\Seeders\TemplateSmsSeeder;
use EscolaLms\TemplatesSms\Facades\Sms;
use EscolaLms\TemplatesSms\Tests\Mocks\TestEvent;
use EscolaLms\TemplatesSms\Tests\Mocks\TestVariables;
use EscolaLms\TemplatesSms\Tests\TestCase;
use EscolaLms\TemplatesSms\Core\SmsChannel;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;

class SmsChannelTest extends TestCase
{
    use CreatesUsers, ApiTestTrait, WithoutMiddleware, DatabaseTransactions, WithFaker;

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
        Sms::fake();

        $admin = $this->makeAdmin(['phone' => $this->faker->phoneNumber]);

        $template = app(TemplateRepositoryContract::class)->findTemplateDefault(TestEvent::class, SmsChannel::class);

        $preview = Template::sendPreview($admin, $template);
        $arr = $preview->toArray();

        $this->assertStringContainsString($admin->email, $arr['data']['content']);
        $this->assertTrue($arr['sent']);
    }

    public function testSmsChannelNotificationDisabled()
    {
        Event::fake();
        Sms::fake();

        $user = $this->makeStudent();
        $admin = $this->makeAdmin();

        $template = app(TemplateRepositoryContract::class)->findTemplateDefault(TestEvent::class, SmsChannel::class);

        $event = new TestEvent($admin, $user);
        $status = SmsChannel::send(new EventWrapper($event), $template->sections->toArray());

        Sms::assertNotSent($user->phone);
        Sms::assertNotSent($admin->phone);

        $this->assertFalse($status);
    }

    public function testSmsChannelNotificationEnabled()
    {
        Event::fake();
        Sms::fake();

        $user = $this->makeStudent(
            [
                'first_name' => $this->faker->firstName,
                'last_name' => $this->faker->lastName,
                'phone' => $this->faker->phoneNumber,
                'notification_channels' => json_encode([
                    "EscolaLms\\TemplatesEmail\\Core\\EmailChannel",
                    "EscolaLms\\TemplatesSms\\Core\\SmsChannel"
                ])
            ]
        );
        $admin = $this->makeAdmin();

        $template = app(TemplateRepositoryContract::class)->findTemplateDefault(TestEvent::class, SmsChannel::class);

        $event = new TestEvent($user, $admin);

        $status = SmsChannel::send(new EventWrapper($event), $template->sections->first()->toArray());

        Sms::assertSent($user->phone);
        Sms::assertNotSent($admin->phone);

        $this->assertTrue($status);
    }
}
