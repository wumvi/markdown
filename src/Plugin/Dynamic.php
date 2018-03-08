<?php
declare(strict_types=1);

namespace Core\Markdown\Plugin;

use Core\Markdown\Result\SimpleResult;

class Dynamic extends BlockAbstract
{
    private const MATCH = '/^\[;(?\'class\'[\w-_]+)(?\'data\' [^}]+)?\]$/';
    private const TPL = '<div class="%s" data-attr="%s"></div>';

    public function match(string $line): bool
    {
        return (bool) preg_match(self::MATCH, $line);
    }

    public function parse(array $lines, int $pos): SimpleResult
    {
        $text = preg_replace_callback(self::MATCH, [$this, 'replace'], $lines[$pos]);

        return new SimpleResult($text, $pos);
    }

    private function replace($match): string
    {
        return sprintf(self::TPL, $match['class'],trim($match['data'] ?? ''));
    }
}
