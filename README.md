# Templates-SMS
[![codecov](https://codecov.io/gh/EscolaLMS/Templates-SMS/branch/main/graph/badge.svg?token=O91FHNKI6R)](https://codecov.io/gh/EscolaLMS/Templates-SMS)
[![Tests PHPUnit in environments](https://github.com/EscolaLMS/Templates-SMS/actions/workflows/test.yml/badge.svg)](https://github.com/EscolaLMS/Templates-SMS/actions/workflows/test.yml)
[![downloads](https://img.shields.io/packagist/dt/escolalms/templates-sms)](https://packagist.org/packages/escolalms/templates-sms)
[![downloads](https://img.shields.io/packagist/v/escolalms/templates-sms)](https://packagist.org/packages/escolalms/templates-sms)
[![downloads](https://img.shields.io/packagist/l/escolalms/templates-sms)](https://packagist.org/packages/escolalms/templates-sms)

## What does it do
Package for sms notifications with editable templates (for important user-related events).
This package supports sending sms via twilio.

## Installing
- `composer require escolalms/templates-sms`
- `php artisan db:seed --class="EscolaLms\Templates-SMS\Database\Seeders\TemplateSmsSeeder"`

## Configuration
You can configure the connection to Twilio through keys in the `.env` file:
- `TWILIO_SID` - twilio SID unique key 
- `TWILIO_TOKEN` - twilio auth token
- `TWILIO_FROM` - twilio phone number
- `TWILIO_SSL_VERIFY` - twilio ssl verify

You can also change the default driver in `SMS_DRIVER`

## Example
### Sending SMS
Sending an SMS using the `Sms` facade
```php
Sms::driver('twilio')->send('123456789', 'SMS message');
````
or
```php
Sms::send('123456789', 'SMS message');
```

### Custom driver
You can define your own driver for sending sms. The driver must implement the interface `\EscolaLms\TemplatesSms\Drivers\Contracts\SmsDriver`.

```php
interface SmsDriver
{
    public function send(string $to, string $content, array $mediaUrls = [], array $params = []): bool;
}
```

Example custom driver:
```php
class CustomDriver implements \EscolaLms\TemplatesSms\Drivers\Contracts\SmsDriver
{
    public function send(string $to, string $content, array $mediaUrls = [], $params = []): bool
    {
        // Implement send() method.
    }
}
```

Register a new driver, we would do the following:
```php
Sms::extend('custom', function($app) {
    return new CustomDriver($app);
});
```


## Tests
Run `./vendor/bin/phpunit` to run tests. See [tests](tests) folder as it's quite good staring point as documentation appendix.

[![codecov](https://codecov.io/gh/EscolaLMS/Templates-SMS/branch/main/graph/badge.svg?token=O91FHNKI6R)](https://codecov.io/gh/EscolaLMS/Templates-SMS)
[![Tests PHPUnit in environments](https://github.com/EscolaLMS/Templates-SMS/actions/workflows/test.yml/badge.svg)](https://github.com/EscolaLMS/Templates-SMS/actions/workflows/test.yml)

This package has a facade for testing.
The Sms facade's fake method allows you to easily a fake sms driver.

```php
public function testSms() {
    Sms::fake();
    ...
    $service->sendSms($phone1);
    ...
    Sms::assertSent($phone1);
    Sms::assertNotSent($phone2);
}
```

```php
public function testSms() {
    Sms::fake();
    ...
    Sms::assertSent($phone1, fn($sms) => $sms->content === 'Sms message');
}
```

