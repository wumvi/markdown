<?php
declare(strict_types=1);

namespace Core\Markdown\Plugin;

use Core\Markdown\Result\HeaderResult;
use Core\Markdown\Result\SimpleResult;

class Header extends BlockAbstract
{
    const MATCH = '/^(?\'level\'#{1,6})(?\'text\'.*)$/';

    public function match(string $line): bool
    {
        return (bool) preg_match(self::MATCH, $line);
    }

    public function parse(array $lines, int $pos): SimpleResult
    {
        $line = $lines[$pos];
        preg_match(self::MATCH, $line, $match);
        $level = strlen($match['level']);
        $caption = $this->inlinePluginAction(trim($match['text']));
        $text = '<h' . $level . ' class="txt-header txt-header--level' . $level . '">' . $caption . '</h' . $level . '>';

        return new HeaderResult($text, $pos, $caption);
    }

    private function inlinePluginAction($textRaw): string
    {
        $text = $textRaw;
        foreach($this->inlinePlugins as $plugin) {
            if ($plugin instanceof Link) {
                $text = $plugin->parse($textRaw);
            }
        }

        return $text;
    }
}
