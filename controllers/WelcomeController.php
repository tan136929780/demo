<?php
/**
 * Created by PhpStorm.
 * User: tanxianchen
 * Date: 19-2-19
 * Time: 下午2:16
 */

namespace app\controllers;


class WelcomeController extends BaseController
{
    public function actionIndex()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['welcome/login']);
        }
        return $this->render('index');
    }

    public function actionLogin()
    {
        $this->layout = 'login';
        return $this->render('login');
    }
}