<?php
declare(strict_types=1);

namespace Core\Markdown\Plugin;

use Core\Markdown\Model\ImageJsonInfo;
use Core\Markdown\Result\ImageCdnResult;
use Core\Markdown\Result\SimpleResult;
use JsonLd\ImageObject;
use LightweightCurl\Curl;
use LightweightCurl\Request;

class ImageCdn extends BlockAbstract
{
    private const MATCH = '/^\[img-(?\'bucket\'\w+)-(?\'imgId\'\d+)(?\'settings\'(?:;\w+)*)\]$/';
    public const TYPE_SIMPLE = 1;
    public const TYPE_YANDEX = 2;
    public const TYPE_AMP = 3;

    private $type = self::TYPE_SIMPLE;

    private const SETTINGS_NO_CAPTION = 'nocaption';
    private const SETTINGS_NO_META = 'nometa';
    private const SETTINGS_MAIN = 'main';

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

    /**
     * @param array $lines
     * @param int $pos
     * @return SimpleResult
     *
     * @throws
     */
    public function parse(array $lines, int $pos): SimpleResult
    {
        if ($this->isClear()) {
            return new SimpleResult('', $pos);
        }

        $line = $lines[$pos];
        preg_match(self::MATCH, $line, $matches);
        $bucket = $matches['bucket'];
        $imgId = $matches['imgId'];
        $settings = array_filter(explode(';', $matches['settings']));

        $chunkImgId = $this->getSubPathById($imgId);

        $jsonUrl = $this->cdnUrl . 'img/' . $bucket . '/' . $chunkImgId . '/info.json';
        // $jsonInfo = file_get_contents($jsonUrl);
        $curl = new Curl();
        $request = new Request();
        $request->setUrl($jsonUrl);
        $request->setTimeout(1);
        $jsonInfo = $curl->call($request);
        if ($jsonInfo->getHttpCode() !== 200) {
            return new SimpleResult(
                sprintf('<!-- Json in "%s" return %s -->', htmlspecialchars($jsonUrl), $jsonInfo->getHttpCode()),
                $pos
            );
        }

        $jsonInfo = json_decode($jsonInfo->getData());
        if (!$jsonInfo) {
            return new SimpleResult(
                sprintf('<!-- Wrong json in "%s" -->', htmlspecialchars($jsonUrl)),
                $pos
            );
        }

        $info = new ImageJsonInfo($jsonInfo);
        unset($jsonInfo);

        $isCaption = !in_array(self::SETTINGS_NO_CAPTION, $settings);
        $isMeta = !in_array(self::SETTINGS_NO_META, $settings);
        $isMain = in_array(self::SETTINGS_MAIN, $settings);

        switch ($this->type) {
            case  self::TYPE_SIMPLE:
                $html = $this->getSimpleHtml($info, $isCaption, $isMeta, $isMain);
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

        return new ImageCdnResult($html, $pos, $info, $isMain);
    }

    private function getSubPathById(string $id): string
    {
        $path = str_pad($id . '', 6, '0', STR_PAD_LEFT);
        $path = str_split($path, 2);

        return implode('/', $path);
    }

    private function getSimpleHtml(ImageJsonInfo $info, bool $isCaption, bool $isMeta, bool $isMain): string
    {
        $tpl = '<div class="imj-wrap js--imj-wrap"
                    data-size=\'' . json_encode($info->getSizes()) . '\'
                    data-trsp="' . ($info->isTransparent() ? 'true' : 'false') . '"
                    data-modified="' . $info->getModified() . '"
                    style="--ratio: ' . $info->getRatioPercent() . '%; --max-width: ' . $info->getMaxWidth() . 'px;"
                    data-url="' . $info->getCdnPart() . $info->getName() . '">
                      <figure class="imj-figure">
                          <img class="imj-img js--imj-img"
                               src="' . $info->getCdnPart() . 'preview.svg"
                               alt="' . $info->getCaption() . '"
                               title="' . $info->getCaption() . '">';
        $tpl .= $info->getCaption() && $isCaption ?
            '<figcaption class="imj-caption">' . $info->getCaption() . '</figcaption>' : '';
        $tpl .= '</figure></div>';

        if ($isMeta) {
            $modified = new \DateTime();
            $modified->setTimestamp($info->getModified());
            $imageObject = new ImageObject();
            $imageObject->setContentUrl($info->getMaxImgUrl());
            $imageObject->setName($info->getCaption());
            $imageObject->setCaption($info->getCaption());
            $imageObject->setRepresentativeOfPage($isMain);
            $imageObject->setDatePublished($modified->format('Y-m-d'));

            $tpl .= '<script type="application/ld+json">' . $imageObject->getJson() . '</script>';
        }

        return $tpl;
    }

    private function getYandexHtml(ImageJsonInfo $info): string
    {
        $tpl = '<figure class="imj-figure">
                  <img class="imj-img js--imj-img"
                       src="' . $info->getMaxImgUrl() . '"
                       alt="' . $info->getCaption() . '"
                       title="' . $info->getCaption() . '">';
        $tpl .= $info->getCaption() ? '<figcaption class="imj-caption">' . $info->getCaption() . '</figcaption>' : '';
        $tpl .= '</figure>';

        return $tpl;
    }

    private function getAmpHtml(ImageJsonInfo $info): string
    {
        $width = $info->getMaxWidth() * 3;
        $height = round($width / $info->getRatio(), 0);
        $tpl = '<amp-img src="' . $info->getMaxImgUrl() . '"' .
            ' alt="' . $info->getCaption() . '"' .
            ' width=' . $width .
            ' height=' . $height . ' class="amp-img" layout="responsive"></amp-img>';

        return $tpl;
    }
}
