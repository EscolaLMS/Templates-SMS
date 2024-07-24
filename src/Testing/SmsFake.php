<?php

namespace EscolaLms\TemplatesSms\Testing;

use Closure;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Assert as PHPUnit;

class SmsFake
{
    /** @var array<int, Sms> */
    private array $sms = [];

    private string $recipient;

    private string $body;

    public function to(string $recipient): self
    {
        $this->recipient = $recipient;

        return $this;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function send(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function dispatch(): void
    {
        $this->sms[] = new Sms($this->recipient, $this->body, [], []);
    }

    public function assertSent(Closure $callback): void
    {
        PHPUnit::assertTrue(
            $this->sent($callback)->count() > 0,
        );
    }

    public function assertSentTimes(Closure $callback, int $times = 1): void
    {
        PHPUnit::assertCount(
            $times, $this->sent($callback)
        );
    }

    public function assertNotSent(Closure $callback): void
    {
        PHPUnit::assertCount(
            0, $this->sent($callback)
        );
    }

    /**
     * @return Collection<int, Sms>
     */
    private function sent(Closure $callback = null): Collection
    {
        $callback = $this->prepare($callback);
        return (new Collection($this->sms))->filter($callback);
    }

    private function prepare(Closure $callback = null): Closure
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
