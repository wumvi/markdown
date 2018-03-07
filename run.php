<?php

use Core\Markdown\Plugin\ImageCdn;

include 'vendor/autoload.php';

$markdown = new \Core\Markdown\Markdown();
$markdown->addInlinePlugin(new \Core\Markdown\Plugin\Link());
$markdown->addInlinePlugin(new \Core\Markdown\Plugin\Bold());

$markdown->addBlockPlugin(new \Core\Markdown\Plugin\Header());
$markdown->addBlockPlugin(new ImageCdn('https://msk.cdn.wumvi.com/data/', ImageCdn::TYPE_AMP));
echo $markdown->parse('
[img-whi-40]
');