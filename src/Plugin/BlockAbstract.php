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
}