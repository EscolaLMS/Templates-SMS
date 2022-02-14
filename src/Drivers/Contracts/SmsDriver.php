<?php

namespace EscolaLms\TemplatesSms\Drivers\Contracts;

interface SmsDriver
{
    public function send(string $to, string $content, array $mediaUrls = [], array $params = []): bool;
}
