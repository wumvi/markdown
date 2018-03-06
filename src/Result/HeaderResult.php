<?php

namespace Core\Markdown\Result;

class HeaderResult extends SimpleResult
{
    /**
     * @var string
     */
    private $caption;

    public function __construct(string $text, int $pos, string $caption)
    {
        parent::__construct($text, $pos);

        $this->caption = $caption;
    }

    public function getCaption(): string
    {
        return $this->caption;
    }
}
