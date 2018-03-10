<?php
declare(strict_types=1);

namespace Core\Markdown\Result;

class HeaderResult extends SimpleResult
{
    /**
     * @var string
     */
    private $caption;

    /**
     * @var int
     */
    private $level;

    public function __construct(string $text, int $pos, string $caption, int $level)
    {
        parent::__construct($text, $pos);

        $this->caption = $caption;
        $this->level = $level;
    }

    public function getCaption(): string
    {
        return $this->caption;
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }
}
