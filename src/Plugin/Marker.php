<?php
declare(strict_types=1);

namespace Core\Markdown\Plugin;

use Core\Markdown\Result\MarketResult;
use Core\Markdown\Result\SimpleResult;

class Marker extends BlockAbstract
{
    private const MATCH = '/^===(?<marker>[^=])+===$/';

    public function match(string $line): bool
    {
        return (bool) preg_match(self::MATCH, $line);
    }

    public function parse(array $lines, int $pos): SimpleResult
    {
        preg_match(self::MATCH, $lines[$pos], $match);

        return new MarketResult('', $pos, $match['marker']);
    }
}
