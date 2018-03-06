<?php

namespace Core\Markdown\Plugin;

use Core\Markdown\Result\SimpleResult;

class Header extends BlockAbstractPlugin
{
    const MATCH = '/^(?\'level\'#{1,6})(?\'text\'.*)$/';

    public function match(string $line): bool
    {
        return preg_match(self::MATCH, $line);
    }

    public function parse(array $lines, int $pos): SimpleResult
    {
        $line = $lines[$pos];
        preg_match(self::MATCH, $line, $match);
        $level = strlen($match['level']);
        $text = '<h' . $level . ' class="txt-header txt-header--level' . $level . '">' .
            $this->inlinePluginAction(trim($match['text'])) .
            '</h' . $level . '>';

        return new SimpleResult($text, $pos);
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
