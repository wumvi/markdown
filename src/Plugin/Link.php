<?php

namespace Core\Markdown\Plugin;

class Link implements InlinePlugin
{
    public function parse(string $line): string
    {
        return preg_replace(
            '/\[(.*?)\]\((.+?)\)/',
            '<a href="$2" class="txt-link" title="$1">$1</a>',
            $line
        );
    }
}
