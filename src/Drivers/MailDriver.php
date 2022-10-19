<?php

namespace EscolaLms\TemplatesSms\Drivers;

use Illuminate\Support\Facades\Mail;
use Tzsk\Sms\Contracts\Driver;

class MailDriver extends Driver
{
    public function send(): void
    {
        foreach ($this->recipients as $recipient) {
            Mail::send([], [], function ($message) use ($recipient) {
                $message->to(preg_replace('/\s+/', '', $recipient) . "@sms.com")
                    ->subject($this->settings['subject'])
                    ->setBody($this->body);
            });
        }
    }
}