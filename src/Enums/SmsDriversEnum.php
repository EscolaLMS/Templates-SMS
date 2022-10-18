<?php

namespace EscolaLms\TemplatesSms\Enums;

use EscolaLms\Core\Enums\BasicEnum;

class SmsDriversEnum extends BasicEnum
{
    const MAIL = 'mail';
    const TWILIO = 'twilio';
    const REQUESTBIN = 'requestbin';
}