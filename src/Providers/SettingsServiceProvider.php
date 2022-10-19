<?php

namespace EscolaLms\TemplatesSms\Providers;

use EscolaLms\Settings\EscolaLmsSettingsServiceProvider;
use EscolaLms\Settings\Facades\AdministrableConfig;
use EscolaLms\TemplatesSms\Enums\ConfigEnum;
use EscolaLms\TemplatesSms\Enums\SmsDriversEnum;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    public function register()
    {
        if (class_exists(\EscolaLms\Settings\EscolaLmsSettingsServiceProvider::class)) {
            if (!$this->app->getProviders(EscolaLmsSettingsServiceProvider::class)) {
                $this->app->register(EscolaLmsSettingsServiceProvider::class);
            }

            AdministrableConfig::registerConfig(ConfigEnum::CONFIG_KEY . '.default', ['required', 'string', 'in:' . implode(',', SmsDriversEnum::getValues())], false);

            AdministrableConfig::registerConfig(ConfigEnum::CONFIG_KEY . '.drivers.requestbin.path', ['string'], false);

            AdministrableConfig::registerConfig(ConfigEnum::CONFIG_KEY . '.drivers.twilio.sid', ['string'], false);
            AdministrableConfig::registerConfig(ConfigEnum::CONFIG_KEY . '.drivers.twilio.token', ['string'], false);
            AdministrableConfig::registerConfig(ConfigEnum::CONFIG_KEY . '.drivers.twilio.from', ['string'], false);
        }
    }
}
