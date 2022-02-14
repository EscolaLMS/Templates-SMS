<?php

namespace EscolaLms\TemplatesSms;

use EscolaLms\TemplatesSms\Enums\ConfigEnum;
use EscolaLms\TemplatesSms\Providers\SettingsServiceProvider;
use Illuminate\Support\ServiceProvider;

class EscolaLmsTemplatesSmsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config.php', ConfigEnum::CONFIG_KEY);

        $this->app->register(SettingsServiceProvider::class);
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
            __DIR__ . '/config.php' => config_path(ConfigEnum::CONFIG_KEY . '.php'),
        ], ConfigEnum::CONFIG_KEY . '.config');
    }
}
