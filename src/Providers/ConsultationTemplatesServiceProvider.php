<?php

namespace EscolaLms\TemplatesSms\Providers;

use EscolaLms\Consultations\Events\ApprovedTerm;
use EscolaLms\Consultations\Events\ApprovedTermWithTrainer;
use EscolaLms\Consultations\Events\RejectTerm;
use EscolaLms\Consultations\Events\RejectTermWithTrainer;
use EscolaLms\Consultations\Events\ReminderAboutTerm;
use EscolaLms\Consultations\Events\ReminderTrainerAboutTerm;
use EscolaLms\Consultations\Events\ReportTerm;
use EscolaLms\Templates\Facades\Template;
use EscolaLms\TemplatesSms\Consultations\ReminderTrainerAboutTermVariables;
use EscolaLms\TemplatesSms\Consultations\ApprovedTermVariables;
use EscolaLms\TemplatesSms\Consultations\ApprovedTermWithTrainerVariables;
use EscolaLms\TemplatesSms\Consultations\RejectTermVariables;
use EscolaLms\TemplatesSms\Consultations\RejectTermWithTrainerVariables;
use EscolaLms\TemplatesSms\Consultations\ReportTermVariables;
use EscolaLms\TemplatesSms\Core\SmsChannel;
use Illuminate\Support\ServiceProvider;

class ConsultationTemplatesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Template::register(ApprovedTermWithTrainer::class, SmsChannel::class, ApprovedTermWithTrainerVariables::class);
        Template::register(ReportTerm::class, SmsChannel::class, ReportTermVariables::class);
        Template::register(RejectTermWithTrainer::class, SmsChannel::class, RejectTermWithTrainerVariables::class);
        Template::register(RejectTerm::class, SmsChannel::class, RejectTermVariables::class);
        Template::register(ApprovedTerm::class, SmsChannel::class, ApprovedTermVariables::class);
        Template::register(ReminderAboutTerm::class, SmsChannel::class, ReportTermVariables::class);
        Template::register(ReminderTrainerAboutTerm::class, SmsChannel::class, ReminderTrainerAboutTermVariables::class);
    }
}
