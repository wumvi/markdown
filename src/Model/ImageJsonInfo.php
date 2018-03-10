<?php
declare(strict_types=1);

namespace Core\Markdown\Model;

class ImageJsonInfo
{
    /**
     * @var string
     */
    private $caption;
    /**
     * @var string
     */
    private $cdn;

    /**
     * @var float
     */
    private $ratio;

    /**
     * @var int
     */
    private $modified;

    /**
     * @var boolean
     */
    private $transparent;

    /**
     * @var boolean
     */
    private $preview;

    /**
     * @var string[]
     */
    private $sizes;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $bucket;

    /**
     * @var string
     */
    private $path;

    /**
     * @var int
     */
    private $id;

    public function __construct(\stdClass $info)
    {
        $this->caption = $info->caption ?? '';
        $this->cdn = $info->cdn ?? '';
        $this->name = $info->name ?? '';
        $this->id = $info->id ?? 0;
        $this->bucket = $info->bucket ?? '';
        $this->path = $info->path ?? '';
        $this->ratio = $info->ratio ?? 1.5;
        $this->modified = $info->modified ?? 0;
        $this->transparent = $info->transparent ?? false;
        $this->preview = $info->preview ?? false;
        $this->sizes = (array)$info->sizes ?? [];
    }

    /**
     * @return string
     */
    public function getCaption(): string
    {
        return $this->caption;
    }

    /**
     * @return float
     */
    public function getRatio(): float
    {
        return $this->ratio;
    }

    /**
     * @return int
     */
    public function getModified(): int
    {
        return $this->modified;
    }

    /**
     * @return bool
     */
    public function isTransparent(): bool
    {
        return $this->transparent;
    }

    /**
     * @return bool
     */
    public function isPreview(): bool
    {
        return $this->preview;
    }

    /**
     * @return string[]
     */
    public function getSizes(): array
    {
        return $this->sizes;
    }

    /**
     * @return string
     */
    public function getCdn(): string
    {
        return $this->cdn;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getBucket(): string
    {
        return $this->bucket;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function getRatioPercent(): float
    {
        return $this->ratio * 100;
    }

    public function getMaxWidth(): int
    {
        return max($this->sizes);
    }

    public function getCdnPart(): string
    {
        return $this->cdn . 'img/' . $this->bucket . '/' . $this->path . '/';
    }

    public function getMaxImgUrl(): string
    {
        return $this->cdn . sprintf(
            'img/%s/%s/%s-%s-3.%s',
            $this->bucket,
            $this->path,
            $this->name,
            $this->getMaxWidth(),
            $this->transparent ? 'png': 'jpg'
        );
    }

    public function getContentType(): string
    {
        return $this->transparent ? 'image/png' : 'image/jpeg';
    }
}
