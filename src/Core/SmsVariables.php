<?php

namespace EscolaLms\TemplatesSms\Core;

use EscolaLms\Core\Models\User;
use EscolaLms\Templates\Contracts\TemplateVariableContract;
use EscolaLms\Templates\Core\AbstractTemplateVariableClass;
use EscolaLms\Templates\Events\EventWrapper;

abstract class SmsVariables extends AbstractTemplateVariableClass implements TemplateVariableContract
{
    public static function mockedVariables(?User $user = null): array
    {
        return [];
    }

    public static function variablesFromEvent(EventWrapper $event): array
    {
        return [];
    }

    public static function requiredSections(): array
    {
        return [];
    }
}
