<?php
include 'vendor/autoload.php';

$markdown = new \Core\Markdown\Markdown();
$markdown->addInlinePlugin(new \Core\Markdown\Plugin\Link());
$markdown->addInlinePlugin(new \Core\Markdown\Plugin\Bold());

$markdown->addBlockPlugin(new \Core\Markdown\Plugin\Header());
$markdown->addBlockPlugin(new \Core\Markdown\Plugin\ImageCdn('http://msk'));
echo $markdown->parse('
[img-whi-50]
');