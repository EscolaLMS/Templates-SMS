<?php

namespace EscolaLms\TemplatesSms\Testing;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Assert as PHPUnit;

class SmsFake
{
    private array $sms = [];

    protected array $recipients = [];

    protected string $body;

    protected ?string $driver = null;

    public function to($recipients): self
    {
        $this->recipients = Arr::wrap($recipients);

        return $this;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function send($body): self
    {
        $this->body = $body;

        return $this;
    }

    public function dispatch()
    {
        $this->sms[] = new Sms($this->recipients, $this->body, [], []);
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
                return in_array($callback, $sms->to);
            };
        }
        return $callback;
    }
}
