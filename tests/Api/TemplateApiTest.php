<?php

namespace EscolaLms\TemplatesSms\Tests\Api;

use EscolaLms\Core\Models\User;
use EscolaLms\Core\Tests\CreatesUsers;
use EscolaLms\Templates\Events\ManuallyTriggeredEvent;
use EscolaLms\Templates\Listeners\TemplateEventListener;
use EscolaLms\Templates\Models\Template;
use EscolaLms\Templates\Models\TemplateSection;
use EscolaLms\TemplatesSms\Core\SmsChannel;
use EscolaLms\TemplatesSms\Facades\Sms;
use EscolaLms\TemplatesSms\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Event;

class TemplateApiTest extends TestCase
{
    use DatabaseTransactions, CreatesUsers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tutor = User::factory()->create([
            'phone' => '666888111',
            'notification_channels' => json_encode([
                "EscolaLms\\TemplatesEmail\\Core\\EmailChannel",
                "EscolaLms\\TemplatesSms\\Core\\SmsChannel"
            ])
        ]);
        $this->tutor->guard_name = 'api';
        $this->tutor->assignRole('tutor');
    }

    public function testManuallyTriggeredEvent(): void
    {
        Sms::fake();
        Event::fake([ManuallyTriggeredEvent::class]);

        $template = Template::factory()->create([
            'name' => 'Sms',
            'channel' => SmsChannel::class,
            'event' => ManuallyTriggeredEvent::class,
            'default' => true,
        ]);

        TemplateSection::factory()->create([
            'key' => 'content',
            'content' => 'Simple content sent to @VarUserName',
            'template_id' => $template->getKey()
        ]);

        $admin = $this->makeAdmin();
        $this->response = $this->actingAs($admin, 'api')->postJson(
            '/api/admin/events/trigger-manually',
            ['users' => [$this->tutor->getKey()]]
        )->assertOk();

        Event::assertDispatched(ManuallyTriggeredEvent::class, function (ManuallyTriggeredEvent $event) {
            $this->assertEquals($this->tutor->getKey(), $event->getUser()->getKey());
            return true;
        });

        $listener = app(TemplateEventListener::class);
        $listener->handle(new ManuallyTriggeredEvent($this->tutor));

        Sms::assertSent(function ($sms) {
            return $sms->to === $this->tutor->phone
                && str_contains($sms->content, ($this->tutor->first_name . ' ' . $this->tutor->last_name));
        });
    }
}
