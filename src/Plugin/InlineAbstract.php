<?php
declare(strict_types=1);


namespace Core\Markdown\Plugin;


abstract class InlineAbstract implements Inline
{
    /**
     * @var bool
     */
    private $clear = false;

    public function isClear(): bool
    {
        return $this->clear;
    }

    public function setClear(bool $flag): void
    {
        $this->clear = $flag;
    }
}
