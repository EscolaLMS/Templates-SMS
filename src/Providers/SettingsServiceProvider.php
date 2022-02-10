<?php

namespace EscolaLms\TemplatesSms\Providers;

use EscolaLms\Settings\EscolaLmsSettingsServiceProvider;
use EscolaLms\Settings\Facades\AdministrableConfig;
use EscolaLms\TemplatesSms\Enums\ConfigEnum;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    public function register()
    {
        if (class_exists(\EscolaLms\Settings\EscolaLmsSettingsServiceProvider::class)) {
            if (!$this->app->getProviders(EscolaLmsSettingsServiceProvider::class)) {
                $this->app->register(EscolaLmsSettingsServiceProvider::class);
            }

            AdministrableConfig::registerConfig(ConfigEnum::CONFIG_KEY . '.twilio.sid', ['required', 'string'], false);
            AdministrableConfig::registerConfig(ConfigEnum::CONFIG_KEY . '.twilio.token', ['required', 'string'], false);
            AdministrableConfig::registerConfig(ConfigEnum::CONFIG_KEY . '.twilio.from', ['required', 'string'], false);
        }
    }
}
