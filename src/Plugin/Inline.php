<?php
declare(strict_types=1);

namespace Core\Markdown\Plugin;

interface Inline
{
    public function parse(string $line): string;
    public function isClear(): bool;
    public function setClear(bool $flag): void;
}
