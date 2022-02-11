<?php

namespace EscolaLms\TemplatesSms\Drivers;

use Aloha\Twilio\Twilio;
use Aloha\Twilio\TwilioInterface;
use EscolaLms\TemplatesSms\Enums\ConfigEnum;
use EscolaLms\TemplatesSms\Drivers\Contracts\SmsDriver;

class TwilioDriver implements SmsDriver
{
    private TwilioInterface $twilio;

    public function __construct()
    {
        $sid = config(ConfigEnum::CONFIG_KEY . '.twilio.sid');
        $token = config(ConfigEnum::CONFIG_KEY . '.twilio.token');
        $from = config(ConfigEnum::CONFIG_KEY . '.twilio.from');

        $this->twilio = new Twilio($sid, $token, $from);
    }

    public function send(string $to, string $content, array $mediaUrls = [], $params = []): bool
    {
        try {
             $this->twilio->message($to, $content, $mediaUrls, $params);
        } catch (\Exception $exception) {
            return false;
        }

        return true;
    }
}
