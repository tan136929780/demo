<?php

namespace app\modules\api;


class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\api\controllers';
    public $requestedRoute = null;

    public function init()
    {
        parent::init();
        $this->requestedRoute = \Yii::$app->requestedRoute;
    }
}