<?php

namespace EscolaLms\TemplatesSms;

use EscolaLms\Consultations\EscolaLmsConsultationsServiceProvider;
use EscolaLms\TemplatesSms\Enums\ConfigEnum;
use EscolaLms\TemplatesSms\Providers\ConsultationTemplatesServiceProvider;
use EscolaLms\TemplatesSms\Providers\SettingsServiceProvider;
use EscolaLms\TemplatesSms\Providers\TemplateServiceProvider;
use Illuminate\Support\ServiceProvider;

class EscolaLmsTemplatesSmsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/sms.php', ConfigEnum::CONFIG_KEY);

        $this->app->register(SettingsServiceProvider::class);

        if (class_exists(EscolaLmsConsultationsServiceProvider::class)) {
            $this->app->register(ConsultationTemplatesServiceProvider::class);
        }

        $this->app->register(TemplateServiceProvider::class);
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    public function bootForConsole()
    {
        $this->publishes([
            __DIR__ . '/../config/sms.php' => config_path(ConfigEnum::CONFIG_KEY . '.php'),
        ], ConfigEnum::CONFIG_KEY . '.config');
    }
}
