<?php

namespace EscolaLms\TemplatesSms\Webinar;

class WebinarTrainerAssignedVariables extends CommonWebinarVariables
{
    public static function defaultSectionsContent(): array
    {
        return [
            'content' => self::wrapWithMjml(__('<h1>Hello :user_name!</h1><p>You assigned of the webinar :webinar.</p>', [
                'user_name' => self::VAR_USER_NAME,
                'webinar' => self::VAR_WEBINAR_TITLE,
            ]),),
        ];
    }
}
