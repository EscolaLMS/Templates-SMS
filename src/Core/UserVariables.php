<?php

namespace EscolaLms\TemplatesSms\Core;

use EscolaLms\Core\Models\User;
use EscolaLms\Templates\Events\EventWrapper;

class UserVariables extends SmsVariables
{
    const VAR_USER_NAME = '@VarUserName';

    public static function mockedVariables(?User $user = null): array
    {
        $faker = \Faker\Factory::create();
        return array_merge(parent::mockedVariables(), [
            self::VAR_USER_NAME => $faker->name(),
        ]);
    }

    public static function variablesFromEvent(EventWrapper $event): array
    {
        return array_merge(parent::variablesFromEvent($event), [
            self::VAR_USER_NAME => $event->getUser()->name,
        ]);
    }

    public static function requiredVariables(): array
    {
        return [
            self::VAR_USER_NAME,
        ];
    }

    public static function requiredVariablesInSection(string $sectionKey): array
    {
        if ($sectionKey === 'content') {
            [
                self::VAR_USER_NAME,
            ];
        }
        return [];
    }

    public static function assignableClass(): ?string
    {
        return null;
    }

    public static function defaultSectionsContent(): array
    {
        return [
            'content' => ''
        ];
    }
}
