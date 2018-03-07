<?php
declare(strict_types=1);

namespace Core\Markdown\Result;

class SimpleResult
{
    /**
     * @var string
     */
    protected $text;

    /**
     * @var int
     */
    private $pos;

    public function __construct(string $text, int $pos)
    {
        $this->text = $text;
        $this->pos = $pos;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return int
     */
    public function getPos(): int
    {
        return $this->pos;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }
}
