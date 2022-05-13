<?php

namespace EscolaLms\TemplatesSms\Providers;

use EscolaLms\Templates\Facades\Template;
use EscolaLms\TemplatesSms\Core\SmsChannel;
use EscolaLms\TemplatesSms\Webinar\ReminderAboutTermVariables;
use EscolaLms\TemplatesSms\Webinar\WebinarTrainerAssignedVariables;
use EscolaLms\TemplatesSms\Webinar\WebinarTrainerUnassignedVariables;
use EscolaLms\TemplatesSms\Webinar\WebinarUserAssignedVariables;
use EscolaLms\TemplatesSms\Webinar\WebinarUserUnassignedVariables;
use EscolaLms\Webinar\Events\ReminderAboutTerm;
use EscolaLms\Webinar\Events\WebinarTrainerAssigned;
use EscolaLms\Webinar\Events\WebinarTrainerUnassigned;
use EscolaLms\Webinar\Events\WebinarUserAssigned;
use EscolaLms\Webinar\Events\WebinarUserUnassigned;
use Illuminate\Support\ServiceProvider;

class WebinarTemplatesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Template::register(ReminderAboutTerm::class, SmsChannel::class, ReminderAboutTermVariables::class);
        Template::register(WebinarTrainerAssigned::class, SmsChannel::class, WebinarTrainerAssignedVariables::class);
        Template::register(WebinarTrainerUnassigned::class, SmsChannel::class, WebinarTrainerUnassignedVariables::class);
        Template::register(WebinarUserAssigned::class, SmsChannel::class, WebinarUserAssignedVariables::class);
        Template::register(WebinarUserUnassigned::class, SmsChannel::class, WebinarUserUnassignedVariables::class);
    }
}
