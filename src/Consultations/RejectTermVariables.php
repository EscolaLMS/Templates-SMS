<?php

namespace EscolaLms\TemplatesSms\Consultations;

class RejectTermVariables extends CommonConsultationVariables
{
    public static function defaultSectionsContent(): array
    {
        return [
            'title' => __('Reject term ":consultation"', [
                'consultation' => self::VAR_CONSULTATION_TITLE,
            ]),
            'content' => __('Hello :user_name! Reported term :proposed_term for consultation ":consultation" was rejected.', [
                'user_name' => self::VAR_USER_NAME,
                'consultation' => self::VAR_CONSULTATION_TITLE,
                'proposed_term' => self::VAR_CONSULTATION_PROPOSED_TERM
            ]),
        ];
    }
}
