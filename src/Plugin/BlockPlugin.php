<?php
declare(strict_types=1);

namespace Core\Markdown\Plugin;

use Core\Markdown\Result\SimpleResult;

interface BlockPlugin
{
    public function match(string $line): bool;
    public function parse(array $lines, int $pos): SimpleResult;
    public function setInlinePlugins(array $plugins): void;
}
