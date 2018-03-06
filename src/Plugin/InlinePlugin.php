<?php

namespace Core\Markdown\Plugin;

interface InlinePlugin
{
    public function parse(string $line): string;
}
