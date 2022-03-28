<?php

namespace EscolaLms\TemplatesSms\Consultations;

class ReminderAboutTermVariables extends CommonConsultationVariables
{
    public static function defaultSectionsContent(): array
    {
        return [
            'content' => __('Hello :user_name! I would like to remind you about the upcoming consultation :consultation, which will take place :proposed_term.', [
                'user_name' => self::VAR_USER_NAME,
                'consultation' => self::VAR_CONSULTATION_TITLE,
                'proposed_term' => self::VAR_CONSULTATION_PROPOSED_TERM
            ]),
        ];
    }
}
