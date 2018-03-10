<?php
declare(strict_types=1);

namespace Core\Markdown\Plugin;

use Core\Markdown\Result\SimpleResult;

class Example extends BlockAbstract
{
    const MATCH = '/^```$/';

    public function match(string $line): bool
    {
        return (bool)preg_match(self::MATCH, $line);
    }

    public function parse(array $lines, int $pos): SimpleResult
    {
        $flag = false;
        $endPos = 0;
        $html = '';
        for ($i = $pos + 1; $i < count($lines); $i++) {
            if (preg_match(self::MATCH, $lines[$i])) {
                $endPos = $i;
                break;
            }

            if ($flag) {
                $html .= '</div><div class="txt-example--item">';
            }

            if ($lines[$i]) {
                $html .= $lines[$i];
                $flag = true;
            } else {
                $flag = false;
            }
        }

        if ($endPos === 0) {
            return new SimpleResult('```', $pos);
        }

        if ($this->isClear()) {
            return new SimpleResult('', $endPos);
        }

        $html = $this->inlinePluginAction($html);
        $text = '<div class="txt-example"><div class="txt-example--item">' . $html . '</div></div>';

        return new SimpleResult($text, $endPos);
    }
}
