<?php
declare(strict_types=1);

namespace Core\Markdown;

use Core\Markdown\Plugin\BlockPlugin;
use Core\Markdown\Plugin\Inline;
use Core\Markdown\Result\InlineResult;
use Core\Markdown\Result\SimpleResult;

class Markdown
{
    /**
     * @var BlockPlugin[]
     */
    private $blockPlugins = [];

    /**
     * @var Inline[]
     */
    private $inlinePlugins = [];

    /**
     * @var SimpleResult[]
     */
    private $buffer = [];

    public function __construct()
    {
    }

    public function addBlockPlugin(BlockPlugin $plugin): void
    {
        $this->blockPlugins[] = $plugin;
    }

    public function addInlinePlugin(Inline $plugin): void
    {
        $this->inlinePlugins[] = $plugin;
    }

    public function parse(string $text): string
    {
        /** @var InlineResult|null $inlineResult */
        $inlineResult = null;
        $lines = preg_split("/((\r?\n)|(\r\n?))/", $text);
        $this->buffer = [];

        for ($pos = 0; $pos < count($lines); $pos++) {
            $line = trim($lines[$pos]);

            if (empty($line)) {
                if ($inlineResult) {
                    $this->buffer[] = $inlineResult;
                    $inlineResult = null;
                }
                continue;
            }

            $plugin = $this->findMatch($line);
            if ($plugin === null) {
                $text = $this->inlinePluginAction($line);
                if ($inlineResult === null) {
                    $inlineResult = new InlineResult($text, $pos);
                } else {
                    $inlineResult->appendText($text);
                }
                continue;
            }

            if ($inlineResult) {
                $this->buffer[] = $inlineResult;
                $inlineResult = null;
            }

            $plugin->setInlinePlugins($this->inlinePlugins);
            $result = $plugin->parse($lines, $pos);
            $this->buffer[] = $result;
            $pos = $result->getPos();
        }

        if ($inlineResult) {
            $this->buffer[] = $inlineResult;
        }

        return $this->getHtmlByBuffer($this->buffer);
    }

    /**
     * @param SimpleResult[] $buffer
     *
     * @return string
     */
    public function getHtmlByBuffer(array $buffer): string
    {
        $html = '';
        foreach ($buffer as $item) {
            $html .= $item->isDisable() ? '' : $item->getText();
        }

        return $html;
    }

    private function findMatch(string $line): ?BlockPlugin
    {
        foreach ($this->blockPlugins as $plugin) {
            if ($plugin->match($line)) {
                return $plugin;
            }
        }

        return null;
    }

    /**
     * @return SimpleResult[]
     */
    public function getBuffer(): array
    {
        return $this->buffer;
    }

    private function inlinePluginAction(string $textRaw): string
    {
        $text = $textRaw;
        foreach ($this->inlinePlugins as $plugin) {
            $text = $plugin->parse($text);
        }

        return $text;
    }
}
