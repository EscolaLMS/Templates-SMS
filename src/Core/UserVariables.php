<?php

namespace EscolaLms\TemplatesSms\Core;

use EscolaLms\Core\Models\User;
use EscolaLms\Templates\Events\EventWrapper;

class UserVariables extends SmsVariables
{
    const VAR_USER_NAME = '@VarUserName';

    /**
     * @return array<string, mixed>
     */
    public static function mockedVariables(?User $user = null): array
    {
        $faker = \Faker\Factory::create();
        return array_merge(parent::mockedVariables(), [
            self::VAR_USER_NAME => $faker->name(),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public static function variablesFromEvent(EventWrapper $event): array
    {
        return array_merge(parent::variablesFromEvent($event), [
            // @phpstan-ignore-next-line
            self::VAR_USER_NAME => $event->getUser()->name,
        ]);
    }

    /**
     * @return string[]
     */
    public static function requiredVariables(): array
    {
        return [
            self::VAR_USER_NAME,
        ];
    }

    /**
     * @return string[]
     */
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

    /**
     * @return array<string, string>
     */
    public static function defaultSectionsContent(): array
    {
        return [
            'content' => ''
        ];
    }
}
