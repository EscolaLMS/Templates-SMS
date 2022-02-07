<?php

namespace EscolaLms\TemplatesSms\Core;

use Aloha\Twilio\Support\Laravel\Facade as Twilio;
use EscolaLms\Templates\Contracts\TemplateChannelContract;
use EscolaLms\Templates\Core\AbstractTemplateChannelClass;
use EscolaLms\Templates\Core\TemplateSectionSchema;
use EscolaLms\Templates\Enums\TemplateSectionTypeEnum;
use EscolaLms\Templates\Events\EventWrapper;
use EscolaLms\Templates\Models\Template;
use EscolaLms\Templates\Models\TemplateSection;
use Illuminate\Support\Collection;
use EscolaLms\Core\Models\User;

class SmsChannel extends AbstractTemplateChannelClass implements TemplateChannelContract
{
    public static function send(EventWrapper $event, array $sections): bool
    {
        $user = $event->user();
        if ($user->phone) {
            // TODO get from config
            // config('escolalms_templates_sms.twilio.sid'),
            // config('escolalms_templates_sms.twilio.token'),
            // config('escolalms_templates_sms.twilio.from'),
            Twilio::message($user->phone, $sections['content']);

            return true;
        }

        return false;
    }

    public static function preview(User $user, array $sections): bool
    {
        return true;
    }

    public static function sections(): Collection
    {
        return new Collection([
            new TemplateSectionSchema('content', TemplateSectionTypeEnum::SECTION_TEXT(), true),
        ]);
    }

    public static function processTemplateAfterSaving(Template $template): Template
    {
        $content = $template->sections()->where('key', 'content')->first()->content;

        TemplateSection::updateOrCreate(['template_id' => $template->getKey(), 'key' => 'content'], ['content' => $content]);

        return $template->refresh();
    }
}
