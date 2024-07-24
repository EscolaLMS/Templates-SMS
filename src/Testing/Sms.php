<?php

namespace EscolaLms\TemplatesSms\Testing;

class Sms
{
    public string $to;
    public string $content;
    /** @var array<int, string>|null  */
    public ?array $mediaUrls;
    /** @var array<string, mixed>|null  */
    public ?array $params;

    /**
     * @param string $to
     * @param string $content
     * @param array<int, string>|null $mediaUrls
     * @param array<string, mixed>|null $params
     */
    public function __construct(string $to, string $content, ?array $mediaUrls, ?array $params)
    {
        $this->to = $to;
        $this->content = $content;
        $this->mediaUrls = $mediaUrls;
        $this->params = $params;
    }
}
