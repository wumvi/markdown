<?php
declare(strict_types=1);

namespace Core\Markdown\Plugin;

abstract class BlockAbstract implements BlockPlugin
{
    /**
     * @var Inline[]
     */
    protected $inlinePlugins = [];

    /**
     * @var bool
     */
    private $clear = false;

    /**
     * @param Inline[] $plugins
     */
    public function setInlinePlugins(array $plugins): void
    {
        $this->inlinePlugins = $plugins;
    }

    protected function inlinePluginAction(string $textRaw): string
    {
        $text = $textRaw;
        foreach ($this->inlinePlugins as $plugin) {
            $text = $plugin->parse($text);
        }

        return $text;
    }

    public function isClear(): bool
    {
        return $this->clear;
    }

    public function setClear(bool $flag): void
    {
        $this->clear = $flag;
    }
}