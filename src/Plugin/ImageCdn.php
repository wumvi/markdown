<?php
declare(strict_types=1);

namespace Core\Markdown\Plugin;

use Core\Markdown\Model\ImageJsonInfo;
use Core\Markdown\Result\ImageCdnResult;
use Core\Markdown\Result\SimpleResult;

class ImageCdn extends BlockAbstract
{
    private const MATCH = '/^\[img-(?\'bucket\'\w+)-(?\'imgId\'\d+)\]$/';
    public const TYPE_SIMPLE = 1;
    public const TYPE_YANDEX = 2;
    public const TYPE_AMP = 3;

    private $type = self::TYPE_SIMPLE;

    /**
     * @var string
     */
    private $cdnUrl;

    public function __construct(string $cdnUrl, int $type)
    {
        $this->cdnUrl = $cdnUrl;
        $this->type = $type;
    }

    public function match(string $line): bool
    {
        return (bool)preg_match(self::MATCH, $line);
    }

    public function parse(array $lines, int $pos): SimpleResult
    {
        $line = $lines[$pos];
        preg_match(self::MATCH, $line, $match);
        $bucket = $match['bucket'];
        $imgId = $match['imgId'];

        $chunkImgId = $this->getSubPathById($imgId);

        $jsonUrl = $this->cdnUrl . 'img/' . $bucket . '/' . $chunkImgId . '/info.json';
        $jsonInfo = file_get_contents($jsonUrl);
        if (!$jsonInfo) {
            throw new \Exception('Json info empty');
        }

        $jsonInfo = json_decode($jsonInfo);
        if (!$jsonInfo) {
            throw new \Exception('Wrong json');
        }

        $info = new ImageJsonInfo($jsonInfo);
        unset($jsonInfo);

        switch ($this->type) {
            case  self::TYPE_SIMPLE:
                $html = $this->getSimpleHtml($info);
                break;
            case self::TYPE_YANDEX:
                $html = $this->getYandexHtml($info);
                break;
            case self::TYPE_AMP:
                $html = $this->getAmpHtml($info);
                break;
            default:
                throw new \Exception('Unsupport type ' . $this->type);
        }

        return new ImageCdnResult($html, $pos, $info);
    }

    private function getSubPathById(string $id): string
    {
        $path = str_pad($id . '', 6, '0', STR_PAD_LEFT);
        $path = str_split($path, 2);

        return implode('/', $path);
    }

    private function getSimpleHtml(ImageJsonInfo $info): string
    {
        $tpl = '<div class="imj-wrap js--imj-wrap"
                    data-size=\'' . json_encode($info->getSizes()) . '\'
                    data-trsp="' . ($info->isTransparent() ? 'true' : 'false') . '"
                    style="--ratio: ' . $info->getRatioPercent() . '%; --max-width: ' . $info->getMaxWidth() . 'px;"
                    data-url="' . $info->getCdnPart() . $info->getName() . '">
                      <figure class="imj-figure">
                          <img class="imj-img js--imj-img"
                               src="' . $info->getCdnPart() . 'preview.svg"
                               alt="' . $info->getCaption() . '"
                               title="' . $info->getCaption() . '">';
        $tpl .= $info->getCaption() ? '<figcaption class="imj-caption">' . $info->getCaption() . '</figcaption>' : '';
        $tpl .= '</figure></div>';

        return $tpl;
    }

    private function getYandexHtml(ImageJsonInfo $info): string
    {
        $tpl = '<figure class="imj-figure">
                  <img class="imj-img js--imj-img"
                       src="' . $info->getCdnPart() . 'preview.svg"
                       alt="' . $info->getCaption() . '"
                       title="' . $info->getCaption() . '">';
        $tpl .= $info->getCaption() ? '<figcaption class="imj-caption">' . $info->getCaption() . '</figcaption>' : '';
        $tpl .= '</figure>';

        return $tpl;
    }

    private function getAmpHtml(ImageJsonInfo $info): string
    {
        $width = $info->getMaxWidth() * 3;
        $height = round($width * $info->getRatio(), 0);
        $tpl = '<amp-img src="' . $info->getMaxImgUrl() .
            '" alt="' . $info->getCaption() . '"' .
            ' width=' . $width .
            ' height=' . $height . '></amp-img>';

        return $tpl;
    }
}
