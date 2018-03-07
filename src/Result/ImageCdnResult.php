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

    public function __construct(string $text, int $pos, ImageJsonInfo $info)
    {
        parent::__construct($text, $pos);

        $this->info = $info;
    }

    public function getInfo(): ImageJsonInfo
    {
        return $this->info;
    }
}
