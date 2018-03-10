<?php

use Core\Markdown\Markdown;
use Core\Markdown\Plugin\ImageCdn;
use Core\Markdown\Plugin\Header;
use Core\Markdown\Plugin\Link;
use Core\Markdown\Plugin\Bold;
use Core\Markdown\Plugin\Example;
use Core\Markdown\Plugin\WrapInline;
use Core\Markdown\Plugin\WrapBlock;
use Core\Markdown\Plugin\Dynamic;

include 'vendor/autoload.php';

$markdown = new Markdown();
$markdown->addInlinePlugin(new Link());
$markdown->addInlinePlugin(new Bold());
$markdown->addInlinePlugin(new WrapInline());

$markdown->addBlockPlugin(new Header());
$markdown->addBlockPlugin(new Example());
$markdown->addBlockPlugin(new WrapBlock());
$markdown->addBlockPlugin(new Dynamic());
$markdown->addBlockPlugin(new ImageCdn('https://msk.cdn.wumvi.com/data/', ImageCdn::TYPE_YANDEX));
echo $markdown->parse(' 
[img-whi-40]

[img-whi-40]

');