<?php
/**
 * Created by PhpStorm.
 * User: tanxianchen
 * Date: 19-2-18
 * Time: 下午3:35
 */

namespace app\controllers;

use app\components\AccessComponent;
use Yii;
use app\models\Users;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

class BaseController extends Controller
{
    public function behaviors()
    {
        $behaviors                  = [
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
        $behaviors['access']        = ['class' => AccessComponent::className()];
//        $behaviors['routeRestrict'] = ['class' => RouteRestrictComponent::className()];
        return $behaviors;
    }

    /**
     * Name: _controllers
     * Desc: 取得所有controller名字
     * User: tanxianchen
     * Date: 2019-02-20
     * @return array
     */
    protected function _controllers()
    {
        $files       = scandir(__DIR__);
        $controllers = [];
        if (!empty($files)) {
            foreach ($files as $file) {
                if ($file != '.' && $file != '..') {
                    $controller               = strtolower(preg_replace('/((?<=[a-z])(?=[A-Z]))/', '-', lcfirst(basename($file, 'Controller.php'))));
                    $controllers[$controller] = $controller;
                }
            }
        }
        return $controllers;
    }

    /**
     * Name: returnJsonData
     * Desc: 统一返回Json格式数据
     * User: tanxianchen
     * Date: 2019-02-20
     * @param null $data
     * @return string
     */
    protected function returnJsonData($data = null)
    {
        $response         = Yii::$app->getResponse();
        $response->format = Response::FORMAT_JSON;
        $response->data   = $data;
        $response->send();
        exit;
    }

    /**
     * Name: clearUserCache
     * Desc: 更新User Session数据
     * User: tanxianchen
     * Date: 2019-02-20
     * @return array
     */
    protected function clearUserCache()
    {
        (new Users())->clearUserAllCache();
    }
}