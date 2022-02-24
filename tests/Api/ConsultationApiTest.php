<?php

namespace EscolaLms\TemplatesSms\Tests\Api;

use EscolaLms\Cart\Events\CartOrderPaid;
use EscolaLms\Cart\Models\Order;
use EscolaLms\Cart\Models\OrderItem;
use EscolaLms\Cart\Models\User;
use EscolaLms\Consultations\Events\ReportTerm;
use EscolaLms\Consultations\Listeners\ReportTermListener;
use EscolaLms\Consultations\Models\Consultation;
use EscolaLms\Consultations\Models\ConsultationTerm;
use EscolaLms\Consultations\Repositories\Contracts\ConsultationTermsRepositoryContract;
use EscolaLms\Templates\Database\Factories\TemplateSectionFactory;
use EscolaLms\Templates\Listeners\TemplateEventListener;
use EscolaLms\Templates\Models\Template;
use EscolaLms\Templates\Models\TemplateSection;
use EscolaLms\TemplatesSms\Core\SmsChannel;
use EscolaLms\TemplatesSms\Database\Seeders\TemplateSmsSeeder;
use EscolaLms\TemplatesSms\Facades\Sms;
use EscolaLms\TemplatesSms\Testing\SmsFake;
use EscolaLms\TemplatesSms\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;

class ConsultationApiTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'phone' => '666888111',
            'notification_channels' => json_encode([
                "EscolaLms\\TemplatesEmail\\Core\\EmailChannel",
                "EscolaLms\\TemplatesSms\\Core\\SmsChannel"
            ])
        ]);
        $this->user->guard_name = 'api';
        $this->user->assignRole('tutor');
    }

    public function testConsultationReportTerm()
    {
        Sms::fake();
        $this->makeTemplate();

        $orderItem = $this->createOrder()->items()->first();
        $this->response = $this->actingAs($this->user, 'api')
            ->json('POST',
                '/api/consultations/report-term/' . $orderItem->getKey(),
                [
                    'term' => now()->modify('+1 day')->format('Y-m-d H:i:s')
                ]
            );

        Sms::assertSent(function ($sms) {
            return $sms->to === $this->user->phone
                && str_contains($sms->content, `{$this->user->first_name} {$this->user->last_name}`);
        });
    }

    private function createOrder(): Order
    {
        $consultationsForOrder = Consultation::factory(3)->create();
        $price = $consultationsForOrder->reduce(fn ($acc, Consultation $consultation) => $acc + $consultation->getBuyablePrice(), 0);
        $order = Order::factory()->afterCreating(
            fn (Order $order) => $order->items()->saveMany(
                $consultationsForOrder->map(
                    function (Consultation $consultation) {
                        return OrderItem::query()->make([
                            'quantity' => 1,
                            'buyable_id' => $consultation->getKey(),
                            'buyable_type' => Consultation::class,
                        ]);
                    }
                )
            )
        )->create([
            'user_id' => $this->user->getKey(),
            'total' => $price,
            'subtotal' => $price,
        ]);

        Event::fakeFor(function () use ($order) {
            $event = new CartOrderPaid($this->user, $order);
            $listener = app(ReportTermListener::class);
            $listener->handle($event);
        });

        return Order::whereUserId($this->user->getKey())->first();
    }

    private function makeTemplate(): void
    {
        $template = Template::factory()->create([
            'name' => 'Sms ReportTerm',
            'channel' => SmsChannel::class,
            'event' => ReportTerm::class,
            'default' => true,
        ]);

        TemplateSection::factory()->create([
            'key' => 'content',
            'content' => 'Simple content sent to @VarUserName',
            'template_id' => $template->getKey()
        ]);
    }
}