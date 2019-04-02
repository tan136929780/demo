yii2-commands
=============

Yii 2 extension wrapper for console controllers.

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
$ php composer.phar require webtoucher/yii2-commands "*"
```

or add

```
"webtoucher/yii2-commands": "*"
```

to the ```require``` section of your `composer.json` file.

## Usage

Just use for your console controllers class `webtoucher\commands\Controller` instead of `yii\console\Controller` like this:

```php
<?php

namespace app\components;

use webtoucher\commands\Controller;


class CommandController extends Controller
{
    public function actionRun()
    {
      ...
    }
}
```

You can run your command with flag `--y` to answer 'yes' for every confirms:

```bash
$ php yii command --y
```
