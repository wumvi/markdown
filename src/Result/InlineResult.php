<?php
declare(strict_types=1);

namespace Core\Markdown\Result;

class InlineResult extends SimpleResult
{
    public function appendText(string $text): void
    {
        $this->setText($this->text . $text);
    }

    public function getText(): string
    {
        return '<p class="txt-paragraph">' . parent::getText() . '</p>';
    }
}
