<?php

namespace EscolaLms\TemplatesSms\Tests\Api;

use EscolaLms\Consultations\Enum\ConsultationTermReminderStatusEnum;
use EscolaLms\Consultations\Enum\ConsultationTermStatusEnum;
use EscolaLms\Consultations\Jobs\ReminderAboutConsultationJob;
use EscolaLms\Consultations\Models\Consultation;
use EscolaLms\Consultations\Models\ConsultationUserPivot;
use EscolaLms\Core\Models\User;
use EscolaLms\TemplatesSms\Database\Seeders\TemplateSmsSeeder;
use EscolaLms\TemplatesSms\Facades\Sms;
use EscolaLms\TemplatesSms\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ConsultationApiTest extends TestCase
{
    use DatabaseTransactions;
    private Consultation $consultation;
    private ConsultationUserPivot $consultationUserPivot;

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

        $this->seed(TemplateSmsSeeder::class);
    }

    public function testConsultationReportTerm(): void
    {
        $this->initVariable();
        Sms::fake();
        $this->response = $this->actingAs($this->user, 'api')
            ->json('POST',
                '/api/consultations/report-term/' . $this->consultationUserPivot->getKey(),
                [
                    'term' => now()->modify('+1 day')->format('Y-m-d H:i:s')
                ]
            );

        $this->assertSms($this->consultationUserPivot);
    }

    public function testConsultationApprovedTerm(): void
    {
        $this->initVariable();
        Sms::fake();
        $this->response = $this->actingAs($this->user, 'api')
            ->json('POST',
                '/api/consultations/report-term/' . $this->consultationUserPivot->getKey(),
                [
                    'term' => now()->modify('+1 day')->format('Y-m-d H:i:s')
                ]
            );
        $this->consultationUserPivot->refresh();
        $this->response = $this->actingAs($this->user, 'api')->json(
            'GET',
            '/api/consultations/approve-term/' . $this->consultationUserPivot->getKey()
        );

        $this->assertSms($this->consultationUserPivot);
    }

    public function testReminderAboutConsultationBeforeHour()
    {
        Sms::fake();
        $this->consultation = Consultation::factory()->create();
        $this->consultationUserPivot = ConsultationUserPivot::factory([
            'consultation_id' => $this->consultation->getKey(),
            'user_id' => $this->user->getKey(),
            'executed_at' => now()->modify('+1 hour')->format('Y-m-d H:i:s'),
            'executed_status' => ConsultationTermStatusEnum::APPROVED
        ])->create();
        $this->assertTrue($this->consultationUserPivot->reminder_status === null);
        $job = new ReminderAboutConsultationJob(ConsultationTermReminderStatusEnum::REMINDED_HOUR_BEFORE);
        $job->handle();
        $this->consultationUserPivot->refresh();
        $this->assertSms($this->consultationUserPivot);
        $this->assertTrue(
            $this->consultationUserPivot->reminder_status === ConsultationTermReminderStatusEnum::REMINDED_HOUR_BEFORE
        );
    }

    public function testReminderTrainerAboutConsultationBeforeHour()
    {
        Sms::fake();
        $author =  User::factory()->create([
            'phone' => '666888111',
            'notification_channels' => json_encode([
                "EscolaLms\\TemplatesEmail\\Core\\EmailChannel",
                "EscolaLms\\TemplatesSms\\Core\\SmsChannel"
            ])
        ]);
        $author->guard_name = 'api';
        $author->assignRole('tutor');

        $this->consultation = Consultation::factory([
            'author_id' => $author->getKey(),
        ])->create();
        $this->consultationUserPivot = ConsultationUserPivot::factory([
            'consultation_id' => $this->consultation->getKey(),
            'user_id' => $this->user->getKey(),
            'executed_at' => now()->modify('+1 hour')->format('Y-m-d H:i:s'),
            'executed_status' => ConsultationTermStatusEnum::APPROVED
        ])->create();
        $this->assertTrue($this->consultationUserPivot->reminder_status === null);
        $job = new ReminderAboutConsultationJob(ConsultationTermReminderStatusEnum::REMINDED_HOUR_BEFORE);
        $job->handle();
        $this->consultationUserPivot->refresh();
        $this->assertSms($this->consultationUserPivot);
        $this->assertTrue(
            $this->consultationUserPivot->reminder_status === ConsultationTermReminderStatusEnum::REMINDED_HOUR_BEFORE
        );
    }

    private function initVariable(): void
    {
        $this->consultation = Consultation::factory()->create();
        $this->consultationUserPivot = ConsultationUserPivot::factory([
            'consultation_id' => $this->consultation->getKey(),
            'user_id' => $this->user->getKey(),
            'executed_at' => null,
            'executed_status' => ConsultationTermStatusEnum::NOT_REPORTED,
        ])->create();
    }

    private function assertSms(ConsultationUserPivot $consultationTerm): void
    {
        Sms::assertSent(function ($sms) use ($consultationTerm) {
            return $sms->to === $this->user->phone
                && str_contains($sms->content, ($this->user->first_name . ' ' . $this->user->last_name))
                && str_contains($sms->content, ($consultationTerm->consultation->name))
                && str_contains($sms->content, ($consultationTerm->consultation->executed_at));
        });
    }
}
