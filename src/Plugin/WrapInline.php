<?php
declare(strict_types=1);

namespace Core\Markdown\Plugin;

class WrapInline implements Inline
{
    private const MATCH = '/\{;(?\'class\'[\w-_]+)(?\'phrase\' [^}]+)?\}/';
    private const TPL = '<span class="%s">%s</span>';

    public function parse(string $line): string
    {
        return preg_replace_callback(self::MATCH, [$this, 'replace'], $line);
    }

    private function replace($match): string
    {
        return sprintf(self::TPL, $match['class'], trim($match['phrase'] ?? ''));
    }
}
