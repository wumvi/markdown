<?php

namespace Core\Markdown\Plugin;

class Bold implements Inline
{
    public function parse(string $line): string
    {
        return preg_replace('/\*\*(.*?)\*\*/', '<b class="txt-bold">$1</b>', $line);
    }
}
