<?php

namespace EscolaLms\TemplatesSms\Tests\Drivers;

use EscolaLms\TemplatesSms\Enums\SmsDriversEnum;
use EscolaLms\TemplatesSms\Facades\Sms;
use EscolaLms\TemplatesSms\Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class RequestbinDriverTest extends TestCase
{
    use WithFaker;

    public function testSendViaRequestBin(): void
    {
        Http::fake();

        $phone = $this->faker->phoneNumber;
        $message = $this->faker->text;
        $url = $this->faker->url;
        Config::set('sms.drivers.requestbin.path', $url);

        Sms::send($message)->to($phone)->via(SmsDriversEnum::REQUESTBIN)->dispatch();

        Http::assertSent(function (Request $request) use ($url, $phone, $message) {
            $data = $request->data();
            return $request->url() == $url
                && in_array($phone, $data['recipients'])
                && $data['message'] == $message;
        });
    }
}