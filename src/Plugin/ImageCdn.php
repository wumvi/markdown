<?php

namespace Core\Markdown\Plugin;

use Core\Markdown\Result\ImageCdnResult;
use Core\Markdown\Result\SimpleResult;

class ImageCdn extends BlockAbstractPlugin
{
    const MATCH = '/^\[img-(?\'bucket\'(\w+)-(?\'imgId\'\d+))\]$/';

    /**
     * @var string
     */
    private $cdnUrl;

    public function __construct(string $cdnUrl)
    {
        $this->cdnUrl = $cdnUrl;
    }

    public function match(string $line): bool
    {
        return preg_match(self::MATCH, $line);
    }

    public function parse(array $lines, int $pos): SimpleResult
    {
        $line = $lines[$pos];
        preg_match(self::MATCH, $line, $match);
        $bucket = $match['bucket'];
        $imgId = strlen($match['imgId']);


        $text = 'didd-' . $bucket . '-' . $imgId;

        $maxUrl = '';

        return new ImageCdnResult($text, $pos, $maxUrl);
    }
}
