<?php

namespace EscolaLms\TemplatesSms\Consultations;

use EscolaLms\Templates\Events\EventWrapper;

class ReminderTrainerAboutTermVariables extends CommonConsultationVariables
{
    const VAR_CONSULTATION_USER_NAME = '@VarConsultationUserName';

    /**
     * @return string[]
     */
    public static function requiredVariables(): array
    {
        return array_merge(parent::requiredVariables(), [
            self::VAR_CONSULTATION_USER_NAME,
        ]);
    }

    /**
     * @return string[]
     */
    public static function requiredVariablesInSection(string $sectionKey): array
    {
        if ($sectionKey === 'content') {
            return array_merge(parent::requiredVariablesInSection($sectionKey), [
                self::VAR_CONSULTATION_USER_NAME,
            ]);
        }
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    public static function variablesFromEvent(EventWrapper $event): array
    {
        return array_merge(parent::variablesFromEvent($event), [
            self::VAR_CONSULTATION_USER_NAME => $event->getConsultationTerm()->user->name,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public static function defaultSectionsContent(): array
    {
        return [
            'title' => __('Remind term ":consultation"', [
                'consultation' => self::VAR_CONSULTATION_TITLE,
            ]),
            'content' => __('Hello :user_name! I would like to remind you about the upcoming consultation :consultation with :consultation_user_name, which will take place :proposed_term.', [
                'user_name' => self::VAR_USER_NAME,
                'consultation_user_name' => self::VAR_CONSULTATION_USER_NAME,
                'consultation' => self::VAR_CONSULTATION_TITLE,
                'proposed_term' => self::VAR_CONSULTATION_PROPOSED_TERM
            ]),
        ];
    }
}
