<?php

namespace Core\Markdown\Plugin;

abstract class BlockAbstractPlugin implements BlockPlugin
{
    /**
     * @var InlinePlugin[]
     */
    protected $inlinePlugins = [];

    /**
     * @param InlinePlugin[] $plugins
     */
    public function setInlinePlugins(array $plugins): void
    {
        $this->inlinePlugins = $plugins;
    }
}