<?php
/**
 * Created by PhpStorm.
 * User: tanxianchen
 * Date: 2019-02-20
 * Time: 14:29
 */

namespace app\components;

use Yii;
use yii\base\ActionFilter;
use yii\di\Instance;
use yii\web\User;

class AccessComponent extends ActionFilter
{
    public $user = 'user';

    public function init()
    {
        parent::init();
        $this->user = Instance::ensure($this->user, User::className());
    }

    /**
     * Name: beforeAction
     * Desc: Validate user privilege
     * User: tanxianchen
     * Date: 2019-02-20
     * @param \yii\base\Action $action
     * @return bool
     */
    public function beforeAction($action)
    {
        parent::beforeAction($action);
        //如果是继承了BaseController的Controller都允许进入
//        if (get_parent_class($action->controller) === BaseController::className()) {
//            return true;
//        }
        //如果用于未登录直接跳转到登录页面
        if ($this->user->getIsGuest()) {
            $this->user->loginRequired();
            return false;
        }
        //如果是登录用户,要判断是否有权限
        if (!$this->user->getIdentity()
                        ->hasPrivilege([
                            'controller' => $action->controller->id,
                            'action'     => $action->id
                        ])
        ) {
            //如果是ajax请求,输出没有权限字符串提示信息
            if (Yii::$app->getRequest()->isAjax) {
                echo Yii::t('yii', 'You are not allowed to perform this action.');
                exit;
            }
            $action->controller->redirect(['welcome/index']);
            return false;
        }
        return true;
    }
}