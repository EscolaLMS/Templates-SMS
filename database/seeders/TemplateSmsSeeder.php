<?php

namespace EscolaLms\TemplatesSms\Database\Seeders;

use EscolaLms\Templates\Facades\Template;
use EscolaLms\TemplatesSms\Core\SmsChannel;
use Illuminate\Database\Seeder;

class TemplateSmsSeeder extends Seeder
{
    public function run()
    {
        Template::createDefaultTemplatesForChannel(SmsChannel::class);
    }
}
