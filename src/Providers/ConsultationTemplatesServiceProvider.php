<?php

namespace EscolaLms\TemplatesSms\Providers;

use EscolaLms\Consultations\Events\ApprovedTerm;
use EscolaLms\Consultations\Events\ReportTerm;
use EscolaLms\Templates\Facades\Template;
use EscolaLms\TemplatesSms\Consultations\ApprovedTermVariables;
use EscolaLms\TemplatesSms\Consultations\ReportTermVariables;
use EscolaLms\TemplatesSms\Core\SmsChannel;
use Illuminate\Support\ServiceProvider;

class ConsultationTemplatesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Template::register(ReportTerm::class, SmsChannel::class, ReportTermVariables::class);
        Template::register(ApprovedTerm::class, SmsChannel::class, ApprovedTermVariables::class);
    }
}
