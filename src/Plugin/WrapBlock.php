<?php
declare(strict_types=1);

namespace Core\Markdown\Plugin;

use Core\Markdown\Result\SimpleResult;

class WrapBlock extends BlockAbstract
{
    private const MATCH = '/^\{;(?\'class\'[\w-_]+)(?\'phrase\' [^}]+)?\}$/';
    private const TPL = '<div class="%s">%s</div>';

    public function match(string $line): bool
    {
        return (bool)preg_match(self::MATCH, $line);
    }

    public function parse(array $lines, int $pos): SimpleResult
    {
        if ($this->isClear()) {
            return new SimpleResult('', $pos);
        }

        $text = preg_replace_callback(self::MATCH, [$this, 'replace'], $lines[$pos]);

        return new SimpleResult($text, $pos);
    }

    private function replace($match): string
    {
        return sprintf(self::TPL, $match['class'], trim($match['phrase'] ?? ''));
    }
}
