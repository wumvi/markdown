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

    /**
     * @var bool
     */
    private $disable = false;

    public function __construct(string $text, int $pos)
    {
        $this->text = $text;
        $this->pos = $pos;
    }

    /**
     * @param bool $flag
     */
    public function setDisable(bool $flag): void
    {
        $this->disable = $flag;
    }

    /**
     * @return bool
     */
    public function isDisable(): bool
    {
        return $this->disable;
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
