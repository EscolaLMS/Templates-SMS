<?php

namespace EscolaLms\TemplatesSms\Consultations;

use EscolaLms\Templates\Events\EventWrapper;

class ReportTermVariables extends CommonConsultationVariables
{
    const VAR_CONSULTATION_BUYER_NAME = '@VarConsultationBuyerName';

    /**
     * @return string[]
     */
    public static function requiredVariables(): array
    {
        return array_merge(parent::requiredVariables(), [
            self::VAR_CONSULTATION_BUYER_NAME,
        ]);
    }

    /**
     * @return string[]
     */
    public static function requiredVariablesInSection(string $sectionKey): array
    {
        if ($sectionKey === 'content') {
            return array_merge(parent::requiredVariablesInSection($sectionKey), [
                self::VAR_CONSULTATION_BUYER_NAME,
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
            // @phpstan-ignore-next-line
            self::VAR_CONSULTATION_BUYER_NAME => $event->getConsultationTerm()->user->name,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public static function defaultSectionsContent(): array
    {
        return [
            'content' => __('Hello :user_name! Buyer :consultation_buyer_name consultation ":consultation" reported proposed term realization: :proposed_term.', [
                'user_name' => self::VAR_USER_NAME,
                'consultation_buyer_name' => self::VAR_CONSULTATION_BUYER_NAME,
                'consultation' => self::VAR_CONSULTATION_TITLE,
                'proposed_term' => self::VAR_CONSULTATION_PROPOSED_TERM
            ]),
        ];
    }
}
