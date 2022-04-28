<?php

namespace EscolaLms\TemplatesSms\Webinar;

use EscolaLms\Core\Models\User;
use EscolaLms\Templates\Events\EventWrapper;
use EscolaLms\TemplatesSms\Core\SmsVariables;
use EscolaLms\Webinar\Models\Webinar;

abstract class CommonWebinarVariables extends SmsVariables
{
    const VAR_USER_NAME = '@VarUserName';
    const VAR_WEBINAR_TITLE = '@VarWebinarTitle';
    const VAR_WEBINAR_TERM = '@VarWebinarTerm';

    public static function mockedVariables(?User $user = null): array
    {
        $faker = \Faker\Factory::create();
        return array_merge(parent::mockedVariables(), [
            self::VAR_USER_NAME => $faker->name(),
            self::VAR_WEBINAR_TITLE => $faker->word(),
        ]);
    }

    public static function variablesFromEvent(EventWrapper $event): array
    {
        return array_merge(parent::variablesFromEvent($event), [
            self::VAR_USER_NAME => $event->getUser()->name,
            self::VAR_WEBINAR_TITLE => $event->getWebinar()->name,
            self::VAR_WEBINAR_TERM => $event->getWebinar()->active_to,
        ]);
    }

    public static function requiredVariables(): array
    {
        return [
            self::VAR_USER_NAME,
            self::VAR_WEBINAR_TITLE,
            self::VAR_WEBINAR_TERM,
        ];
    }

    public static function requiredVariablesInSection(string $sectionKey): array
    {
        if ($sectionKey === 'content') {
            return [
                self::VAR_USER_NAME,
                self::VAR_WEBINAR_TITLE,
                self::VAR_WEBINAR_TERM,
            ];
        }
        return [];
    }

    public static function assignableClass(): ?string
    {
        return Webinar::class;
    }
}
