<?php

namespace EscolaLms\TemplatesSms\Core;

use EscolaLms\Core\Models\User;
use EscolaLms\Templates\Contracts\TemplateVariableContract;
use EscolaLms\Templates\Core\AbstractTemplateVariableClass;
use EscolaLms\Templates\Events\EventWrapper;

abstract class SmsVariables extends AbstractTemplateVariableClass implements TemplateVariableContract
{
    /**
     * @return array<string, mixed>
     */
    public static function mockedVariables(?User $user = null): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    public static function variablesFromEvent(EventWrapper $event): array
    {
        return [];
    }

    /**
     * @return string[]
     */
    public static function requiredSections(): array
    {
        return [];
    }
}
