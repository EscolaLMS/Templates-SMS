<?php

namespace EscolaLms\TemplatesSms\Testing;

class Sms
{
    public string $to;
    public string $content;
    public ?array $mediaUrls;
    public ?array $params;

    /**
     * @param array $to
     * @param string $content
     * @param array|null $mediaUrls
     * @param array|null $params
     */
    public function __construct(string $to, string $content, ?array $mediaUrls, ?array $params)
    {
        $this->to = $to;
        $this->content = $content;
        $this->mediaUrls = $mediaUrls;
        $this->params = $params;
    }
}
