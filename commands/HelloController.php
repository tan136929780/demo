<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\Users;
use yii\console\Controller;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    /**
     * Name: actionInitPassWord
     * Desc: init user passWord for admin
     * User: tanxianchen
     * Date: 2019-02-20
     * @return string
     */
    public function actionInitPassWord($id = 1)
    {
        Users::updateAll(['password' => '$2y$13$Y/5FeLoUpFe4zBBuRhNo9.4w3IFAKXcPRgTUW.hmwGoLSIV8F3Rci'], ['id' => $id]);
    }
}
