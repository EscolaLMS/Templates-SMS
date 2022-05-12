<?php

namespace EscolaLms\TemplatesSms\Drivers;

use Aloha\Twilio\Twilio;
use Aloha\Twilio\TwilioInterface;
use EscolaLms\TemplatesSms\Drivers\Contracts\SmsDriver;
use Illuminate\Support\Facades\Log;

class TwilioDriver implements SmsDriver
{
    private TwilioInterface $twilio;

    public function __construct(string $sid, string $token, string $from, bool $sslVerify = true)
    {
        $this->twilio = new Twilio($sid, $token, $from, $sslVerify);
    }

    public function send(string $to, string $content, array $mediaUrls = [], $params = []): bool
    {
        try {
             $this->twilio->message($to, $content, $mediaUrls, $params);
        } catch (\Exception $exception) {
            Log::error('[' . __CLASS__ . '] ' . $exception->getMessage());
            return false;
        }

        return true;
    }
}
