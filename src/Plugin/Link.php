<?php
declare(strict_types=1);

namespace Core\Markdown\Plugin;

class Link extends InlineAbstract
{
    private const MATCH = '/\[(.*?)\]\((.+?)\)/';
    private const REPLACE = '<a href="$2" class="txt-link" title="$1">$1</a>';

    public function parse(string $line): string
    {
        return preg_replace(
            self::MATCH,
            $this->isClear() ? '' : self::REPLACE,
            $line
        );
    }
}
