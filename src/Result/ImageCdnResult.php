<?php

namespace Core\Markdown\Result;

class ImageCdnResult extends SimpleResult
{
    /**
     * @var string
     */
    private $url;

    public function __construct(string $text, int $pos, string $url)
    {
        parent::__construct($text, $pos);

        $this->url = $url;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
