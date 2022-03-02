<?php

namespace EscolaLms\TemplatesSms\Consultations;

class ApprovedTermVariables extends CommonConsultationVariables
{
    const VAR_APPROVED_TERM = '@VarApprovedTermVariables';

    // TODO Add variable to sms
    public static function defaultSectionsContent(): array
    {
        return [
            'content' => ''
        ];
    }
}
