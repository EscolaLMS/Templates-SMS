<?php

namespace EscolaLms\TemplatesSms\Consultations;

class ApprovedTermVariables extends CommonConsultationVariables
{
    public static function defaultSectionsContent(): array
    {
        return [
            'content' => __('Hello :user_name! Reported term :proposed_term for consultation ":consultation" was approved.', [
                'user_name' => self::VAR_USER_NAME,
                'consultation' => self::VAR_CONSULTATION_TITLE,
                'proposed_term' => self::VAR_CONSULTATION_PROPOSED_TERM
            ]),
        ];
    }
}
