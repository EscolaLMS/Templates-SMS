<?php

namespace EscolaLms\TemplatesSms\Testing;

use Closure;
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
        $this->sms[] = new Sms($to, $content, $mediaUrls, $params);

        return true;
    }

    public function assertSent($callback): void
    {
        PHPUnit::assertTrue(
            $this->sent($callback)->count() > 0,
        );
    }

    public function assertSentTimes($callback, $times = 1): void
    {
        PHPUnit::assertCount(
            $times, $this->sent($callback)
        );
    }

    public function assertNotSent($callback): void
    {
        PHPUnit::assertCount(
            0, $this->sent($callback)
        );
    }

    private function sent($callback = null)
    {
        $callback = $this->prepare($callback);
        return (new Collection($this->sms))->filter($callback);
    }

    private function prepare($callback = null): Closure
    {
        if ($callback instanceof Closure) {
            $callback = function (Sms $sms) use ($callback) {
                return $callback($sms);
            };
        }
        else {
            $callback = function (Sms $sms) use ($callback) {
                return $sms->to == $callback;
            };
        }
        return $callback;
    }
}
