<?php

namespace EscolaLms\TemplatesSms\Consultations;

class ReportTermVariables extends CommonConsultationVariables
{
    const VAR_REPORT_TERM = '@VarReportTerm';

    // TODO Add variable to sms
    public static function defaultSectionsContent(): array
    {
        return [
            'content' => ''
        ];
    }
}
