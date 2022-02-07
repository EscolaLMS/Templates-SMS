<?php

namespace EscolaLms\TemplatesSms\Tests\Mocks;

use EscolaLms\Core\Models\User;
use EscolaLms\Templates\Contracts\TemplateVariableContract;
use EscolaLms\Templates\Core\AbstractTemplateVariableClass;
use EscolaLms\Templates\Events\EventWrapper;

class TestVariables extends AbstractTemplateVariableClass implements TemplateVariableContract
{
    const VAR_USER_EMAIL   = '@VarUserEmail';
    const VAR_FRIEND_EMAIL = '@VarFriendEmail';

    public static function variablesFromEvent(EventWrapper $event): array
    {
        $user = $event->getUser();
        $friend = $event->getFriend();
        return [
            self::VAR_USER_EMAIL => $user->email,
            self::VAR_FRIEND_EMAIL => $friend->email,
        ];
    }

    public static function mockedVariables(?User $user = null): array
    {
        return [
            self::VAR_USER_EMAIL => $user ? $user->email : 'user.email@test.com',
            self::VAR_FRIEND_EMAIL => 'friend.email@test.com',
        ];
    }

    public static function assignableClass(): ?string
    {
        return null;
    }

    public static function requiredSections(): array
    {
        return [];
    }

    public static function requiredVariables(): array
    {
        return [];
    }

    public static function requiredVariablesInSection(string $sectionKey): array
    {
        if ($sectionKey === 'content') {
            return [
                self::VAR_USER_EMAIL,
                self::VAR_FRIEND_EMAIL,
            ];
        }
        return [];
    }

    public static function defaultSectionsContent(): array
    {
        return [
            'content' => 'Hello ' . self::VAR_USER_EMAIL . '!' . PHP_EOL
                . 'You have new friend request from ' . self::VAR_FRIEND_EMAIL,
        ];
    }
}
