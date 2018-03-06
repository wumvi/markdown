<?php

namespace Core\Markdown\Plugin;

class Highlight implements Inline
{
    public function parse(string $line): string
    {
        return preg_replace('/`(.*?)`/', '<span class="txt-highlight">$1</span>', $line);
    }
}
