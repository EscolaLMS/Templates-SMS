<?php

namespace EscolaLms\TemplatesSms\Testing;

use EscolaLms\TemplatesSms\Drivers\Contracts\SmsDriver;

class SmsFake implements SmsDriver
{
    public function send(string $to, string $content, array $mediaUrls = [], array $params = []): bool
    {
        // TODO
        return true;
    }
}
