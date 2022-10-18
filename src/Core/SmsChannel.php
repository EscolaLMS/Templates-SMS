<?php

namespace EscolaLms\TemplatesSms\Core;

use EscolaLms\Templates\Contracts\TemplateChannelContract;
use EscolaLms\Templates\Core\AbstractTemplateChannelClass;
use EscolaLms\Templates\Core\TemplateSectionSchema;
use EscolaLms\Templates\Enums\TemplateSectionTypeEnum;
use EscolaLms\Templates\Events\EventWrapper;
use EscolaLms\TemplatesSms\Facades\Sms;
use Illuminate\Support\Facades\Log;
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

        return self::sendMessage($user, $sections);
    }

    public static function preview(User $user, array $sections): bool
    {
        return self::sendMessage($user, $sections);
    }

    public static function sections(): Collection
    {
        return new Collection([
            new TemplateSectionSchema('content', TemplateSectionTypeEnum::SECTION_TEXT(), true),
        ]);
    }

    private static function sendMessage(User $user, array $sections): bool
    {
        if (!$user->phone) {
            return false;
        }

        try {
           Sms::send($sections['content'])->to([$user->phone])->dispatch();
        } catch (\Exception $exception) {
            Log::error('[' . __CLASS__ . '] ' . $exception->getMessage());
            return false;
        }

        return true;
    }
}
