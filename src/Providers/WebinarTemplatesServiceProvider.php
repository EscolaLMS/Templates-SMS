<?php

namespace EscolaLms\TemplatesSms\Providers;

use EscolaLms\Templates\Facades\Template;
use EscolaLms\TemplatesSms\Core\SmsChannel;
use EscolaLms\TemplatesSms\Webinar\ReminderAboutTermVariables;
use EscolaLms\TemplatesSms\Webinar\WebinarTrainerAssignedVariables;
use EscolaLms\TemplatesSms\Webinar\WebinarTrainerUnassignedVariables;
use EscolaLms\Webinar\Events\ReminderAboutTerm;
use EscolaLms\Webinar\Events\WebinarTrainerAssigned;
use EscolaLms\Webinar\Events\WebinarTrainerUnassigned;
use Illuminate\Support\ServiceProvider;

class WebinarTemplatesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Template::register(ReminderAboutTerm::class, SmsChannel::class, ReminderAboutTermVariables::class);
        Template::register(WebinarTrainerAssigned::class, SmsChannel::class, WebinarTrainerAssignedVariables::class);
        Template::register(WebinarTrainerUnassigned::class, SmsChannel::class, WebinarTrainerUnassignedVariables::class);
    }
}
