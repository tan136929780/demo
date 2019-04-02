<?php
//检测浏览器版本
require(__DIR__ . '/../helpers/checkExplorer.php');
$explorer = new ExplorerCheck();
$explorer->check();

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

// DB保存时间的默认时区 : 格林威治标准时间
defined('DEFAULT_DATE_ZONE') || define('DEFAULT_DATE_ZONE', 'Etc/GMT');
// 页面显示默认时区 : 中华人民共和国时间
defined('DEFAULT_LOCAL_DATE_ZONE') || define('DEFAULT_LOCAL_DATE_ZONE', 'PRC');

require(__DIR__ . '/../helpers/func.php');
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
