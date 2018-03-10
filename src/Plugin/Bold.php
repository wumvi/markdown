<?php
declare(strict_types=1);

namespace Core\Markdown\Plugin;

class Bold extends InlineAbstract
{
    private const MATCH = '/\*\*(.*?)\*\*/';
    private const REPLACE = '<b class="txt-bold">$1</b>';

    public function parse(string $line): string
    {
        return preg_replace(self::MATCH, $this->isClear() ? '' : self::REPLACE, $line);
    }
}
