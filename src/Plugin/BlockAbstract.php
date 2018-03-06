<?php

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
}