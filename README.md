### Пример использования
```php
<?php

use Core\Markdown\Markdown;
use Core\Markdown\Plugin\ImageCdn;
use Core\Markdown\Plugin\Header;
use Core\Markdown\Plugin\Link;
use Core\Markdown\Plugin\Bold;
use Core\Markdown\Plugin\Example;

include 'vendor/autoload.php';

$markdown = new Markdown();
$markdown->addInlinePlugin(new Link());
$markdown->addInlinePlugin(new Bold());

$markdown->addBlockPlugin(new Header());
$markdown->addBlockPlugin(new Example());
$markdown->addBlockPlugin(new ImageCdn('https://msk.cdn.wumvi.com/data/', ImageCdn::TYPE_SIMPLE));
echo $markdown->parse('## caption');
```



### Поддерживаемые теги

#### Заголовки
##### 
```
# Заголовок первого уровня
## Заголовок второго уровня
### Заголовок третьего уровня
```

#### Ссылка
```
[caption](link)
``` 

#### Выделение
```
`выделено`
``` 

#### Bold
```
**bold**
``` 

#### ImageCdn
```
[img-bucket-imageId]
```
где можно указать доп. параметры

nocaption - без названия изображения

nometa - без мета информации

main - главное изображение статьи

#### Wrap
Вставка блока
```
{;class}
```

Обёртка текста
```
{;class phase}
```

#### Динамичные шаблоны

```
[;js--anim param=1&url=wm]
```
