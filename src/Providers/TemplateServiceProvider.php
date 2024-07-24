<?php

namespace EscolaLms\TemplatesSms\Providers;

use EscolaLms\Templates\Events\ManuallyTriggeredEvent;
use EscolaLms\Templates\Facades\Template;
use EscolaLms\TemplatesSms\Core\SmsChannel;
use EscolaLms\TemplatesSms\Core\UserVariables;
use Illuminate\Support\ServiceProvider;

class TemplateServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Template::register(ManuallyTriggeredEvent::class, SmsChannel::class, UserVariables::class);
    }
}
