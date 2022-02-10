<?php

namespace EscolaLms\TemplatesSms\Facades;

use EscolaLms\TemplatesSms\Drivers\Contracts\SmsDriver;
use EscolaLms\TemplatesSms\Managers\SmsManager;
use EscolaLms\TemplatesSms\Testing\SmsFake;
use Illuminate\Support\Facades\Facade;


/**
 * @method static           SmsDriver driver(string $name)
 * @method static           SmsManager extend(string $driver, \Closure $callback)
 * @method                  void send(string $to, string $content, array $mediaUrls = [], $params = [])
 * @method                  void assertSent($callback)
 * @method                  void assertSentTimes($callback, int $times = 1)
 * @method                  void assertNotSent($callback)
 *
 * @see \EscolaLms\TemplatesSms\Managers\SmsManager
 * @see \EscolaLms\TemplatesSms\Testing\SmsFake
 */
class Sms extends Facade
{
    /**
     * Replace the bound instance with a fake.
     */
    public static function fake()
    {
        $fake = app(SmsFake::class);

        static::swap($fake);

        return $fake;
    }

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return SmsManager::class;
    }
}
