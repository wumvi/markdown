<?php

namespace Core\Markdown\Plugin;

use Core\Markdown\Result\ImageCdnResult;
use Core\Markdown\Result\SimpleResult;

class ImageCdn extends BlockAbstract
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

        $tpl = '<div class="imj-wrap js--imj-wrap" 
                    data-size=\'sizesObject\' 
                    data-trsp="isTransparent"
                    style="--ratio: ration * 100%; --max-width: maxSize px;" 
                    data-url="partCdnUrl name">
                      <figure class="imj-figure">
                          <img class="imj-img js--imj-img"
                               src="partCdnUrl preview.svg" 
                               alt="caption"
                               title="caption">';
    // tpl += imgJsonInfo.getCaption() ? `<figcaption class="imj-caption">caption</figcaption>` : ''
    // tpl += '</figure></div>'

        $maxUrl = '';

        return new ImageCdnResult($text, $pos, $maxUrl);
    }
}
