<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="navbar navbar-default padding-left-0" role="navigation">
    <div class="navbar-collapse collapse padding-left-0">
        <a class="menutoggle show_left_menu" href="javascript:void(0);">
            <span class="navbar-brand"><span class="fa fa-bars"/></span>
        </a>
        <a href="<?= Url::to(['welcome/index']); ?>">
        <span class="navbar-brand padding-left-0"
              style="font-size: 23px;padding-right: 22px;"><?= Yii::t('app', '管理系统') ?></span>
        </a>
        <ul id="main-menu" class="nav navbar-nav navbar-right">
            <li>
                <a tabindex="-1" href="<?= Url::to(['/site/index'])?>"><?= Yii::t('app', '主页')?></a>
            </li>
            <?php if (Yii::$app->user->isGuest) { ?>
                <li>
                    <a tabindex="-1" href="<?= Url::to(['/site/login'])?>"><?= Yii::t('app', '登录')?></a>
                </li>
            <?php } else {?>
                <li>
                    <a tabindex="-1" href="<?= Url::to(['/site/logout'])?>"><?= Yii::t('app', '退出('.Yii::$app->user->identity->username.')')?></a>
                </li>
            <?php }?>
            <li>
                <a tabindex="-1" href="<?= Url::to(['/site/about'])?>"><?= Yii::t('app', '关于我们')?></a>
            </li>
            <li>
                <a tabindex="-1" href="<?= Url::to(['/site/contact'])?>"><?= Yii::t('app', '联系我们')?></a>
            </li>
        </ul>
    </div>
</div>

<div class="content">
    <header class="header">
    </header>

    <div class="main-content">
        <?= $content ?>
    </div>
    <footer>
        <hr>
        <p class="text-center"><a href="javascript:void(0)" target="_blank">&copy; My Company <?= date('Y') ?> <?= ExplorerCheck::SOFT_VERSION ?></a></p>
    </footer>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
