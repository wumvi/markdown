<?php
declare(strict_types=1);

namespace Core\Markdown\Plugin;

class Highlight extends InlineAbstract
{
    private const REPLACE = '<span class="txt-highlight">$1</span>';
    private const MATCH = '/`(.*?)`/';

    public function parse(string $line): string
    {
        return preg_replace(self::MATCH, $this->isClear() ? '' : self::REPLACE, $line);
    }
}
