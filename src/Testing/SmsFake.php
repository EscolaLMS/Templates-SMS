<?php

namespace EscolaLms\TemplatesSms\Testing;

use EscolaLms\TemplatesSms\Drivers\Contracts\SmsDriver;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Assert as PHPUnit;

class SmsFake implements SmsDriver
{
    private array $sms;

    public function __construct()
    {
        $this->sms = [];
    }

    public function send(string $to, string $content, array $mediaUrls = [], array $params = []): bool
    {
        $this->sms[] = $to;

        return true;
    }

    public function assertSent($to): void
    {
        PHPUnit::assertTrue(
            $this->sentTo($to)->count() > 0,
        );
    }

    public function assertNotSent($to): void
    {
        PHPUnit::assertCount(
            0, $this->sentTo($to)
        );
    }

    public function assertSentTimes($to, $times = 1): void
    {
        PHPUnit::assertCount(
            $times, $this->sentTo($to),
        );
    }

    private function sentTo($to): Collection
    {
        return (new Collection($this->sms))->filter(fn ($item) => $item === $to);
    }
}
