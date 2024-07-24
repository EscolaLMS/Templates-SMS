<?php

namespace EscolaLms\TemplatesSms\Consultations;

use Carbon\Carbon;
use EscolaLms\Consultations\Models\ConsultationUserPivot;
use EscolaLms\Core\Models\User;
use EscolaLms\Templates\Events\EventWrapper;
use EscolaLms\TemplatesSms\Core\SmsVariables;

abstract class CommonConsultationVariables extends SmsVariables
{
    const VAR_USER_NAME = '@VarUserName';
    const VAR_CONSULTATION_TITLE = '@VarConsultationTitle';
    const VAR_CONSULTATION_PROPOSED_TERM = '@VarConsultationProposedTerm';

    /**
     * @return array<string, mixed>
     */
    public static function mockedVariables(?User $user = null): array
    {
        $faker = \Faker\Factory::create();
        return array_merge(parent::mockedVariables(), [
            self::VAR_USER_NAME => $faker->name(),
            self::VAR_CONSULTATION_TITLE => $faker->word(),
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
            // @phpstan-ignore-next-line
            self::VAR_CONSULTATION_TITLE => $event->getConsultationTerm()->consultation->name,
            // @phpstan-ignore-next-line
            self::VAR_CONSULTATION_PROPOSED_TERM => Carbon::make($event->getConsultationTerm()->executed_at)
                // @phpstan-ignore-next-line
                ->setTimezone($event->getUser()->current_timezone)
                ->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * @return string[]
     */
    public static function requiredVariables(): array
    {
        return [
            self::VAR_USER_NAME,
            self::VAR_CONSULTATION_TITLE,
            self::VAR_CONSULTATION_PROPOSED_TERM,
        ];
    }

    /**
     * @return string[]
     */
    public static function requiredVariablesInSection(string $sectionKey): array
    {
        if ($sectionKey === 'content') {
            return [
                self::VAR_USER_NAME,
                self::VAR_CONSULTATION_TITLE,
                self::VAR_CONSULTATION_PROPOSED_TERM,
            ];
        }
        return [];
    }

    public static function assignableClass(): ?string
    {
        return ConsultationUserPivot::class;
    }
}
