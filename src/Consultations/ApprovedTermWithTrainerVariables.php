<?php

namespace EscolaLms\TemplatesSms\Consultations;

class ApprovedTermWithTrainerVariables extends CommonConsultationVariables
{
    public static function defaultSectionsContent(): array
    {
        return [
            'content' => __('Hello :user_name! You approved term :proposed_term for consultation ":consultation".', [
                'user_name' => self::VAR_USER_NAME,
                'consultation' => self::VAR_CONSULTATION_TITLE,
                'proposed_term' => self::VAR_CONSULTATION_PROPOSED_TERM
            ]),
        ];
    }
}
