<?php

namespace Core\Markdown\Plugin;

interface Inline
{
    public function parse(string $line): string;
}
