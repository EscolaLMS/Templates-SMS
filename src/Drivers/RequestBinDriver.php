<?php

namespace EscolaLms\TemplatesSms\Drivers;

use Illuminate\Support\Facades\Http;
use Tzsk\Sms\Contracts\Driver;

class RequestBinDriver extends Driver
{
    public function send(): void
    {
        Http::post($this->settings['path'], [
            'recipients' => $this->recipients,
            'message' => $this->body,
        ]);
    }
}