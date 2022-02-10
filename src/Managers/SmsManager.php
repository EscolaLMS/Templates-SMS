<?php

namespace EscolaLms\TemplatesSms\Managers;

use EscolaLms\TemplatesSms\Drivers\Contracts\SmsDriver;
use EscolaLms\TemplatesSms\Drivers\TwilioDriver;
use EscolaLms\TemplatesSms\Enums\ConfigEnum;
use EscolaLms\TemplatesSms\Testing\SmsFake;
use Illuminate\Support\Manager;

class SmsManager extends Manager
{
    public function getDefaultDriver()
    {
        return $this->config->get(ConfigEnum::CONFIG_KEY . '.sms_driver') ?? 'twilio';
    }

    protected function createTwilioDriver(): SmsDriver
    {
        return new TwilioDriver();
    }
}
