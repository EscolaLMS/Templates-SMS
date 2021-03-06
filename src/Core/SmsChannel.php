<?php

namespace EscolaLms\TemplatesSms\Core;

use EscolaLms\Templates\Contracts\TemplateChannelContract;
use EscolaLms\Templates\Core\AbstractTemplateChannelClass;
use EscolaLms\Templates\Core\TemplateSectionSchema;
use EscolaLms\Templates\Enums\TemplateSectionTypeEnum;
use EscolaLms\Templates\Events\EventWrapper;
use EscolaLms\TemplatesSms\Facades\Sms;
use Illuminate\Support\Collection;
use EscolaLms\Core\Models\User;

class SmsChannel extends AbstractTemplateChannelClass implements TemplateChannelContract
{
    public static function send(EventWrapper $event, array $sections): bool
    {
        $user = $event->user();

// TODO       Is it necessary ?

//        $channelEnabled =
//            isset($user->notification_channels)
//            && in_array(SmsChannel::class, json_decode($user->notification_channels));
//
//        if (!$channelEnabled || !$user->phone) {
//            return false;
//        }
        if (!$user->phone) {
            return false;
        }
        return Sms::send($user->phone, $sections['content']);
    }

    public static function preview(User $user, array $sections): bool
    {
        if (!$user->phone) {
            return false;
        }

        return Sms::send($user->phone, $sections['content']);
    }

    public static function sections(): Collection
    {
        return new Collection([
            new TemplateSectionSchema('content', TemplateSectionTypeEnum::SECTION_TEXT(), true),
        ]);
    }
}
