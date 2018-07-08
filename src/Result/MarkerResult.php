<?php
declare(strict_types=1);

namespace Core\Markdown\Result;

class MarkerResult extends SimpleResult
{
    /**
     * @var string
     */
    private $marker;

    public function __construct(string $text, int $pos, string $marker)
    {
        parent::__construct($text, $pos);

        $this->marker = $marker;
    }

    public function getMarker(): string
    {
        return $this->marker;
    }
}
