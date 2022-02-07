<?php

namespace EscolaLms\TemplatesSms;

use Illuminate\Support\ServiceProvider;

class EscolaLmsTemplatesSmsServiceProvider extends ServiceProvider
{
    const CONFIG_KEY = 'escolalms_templates_sms';

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config.php', self::CONFIG_KEY);
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
            __DIR__ . '/config.php' => config_path(self::CONFIG_KEY . '.php'),
        ], self::CONFIG_KEY . '.config');
    }
}
