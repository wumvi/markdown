<?php
declare(strict_types=1);

namespace Core\Markdown\Result;

use Core\Markdown\Model\ImageJsonInfo;

class ImageCdnResult extends SimpleResult
{
    /**
     * @var ImageJsonInfo
     */
    private $info;

    /**
     * @var bool
     */
    private $isMain;

    public function __construct(string $text, int $pos, ImageJsonInfo $info, bool $isMain)
    {
        parent::__construct($text, $pos);

        $this->info = $info;
        $this->isMain = $isMain;
    }

    public function getInfo(): ImageJsonInfo
    {
        return $this->info;
    }

    /**
     * @return bool
     */
    public function isMain(): bool
    {
        return $this->isMain;
    }
}
