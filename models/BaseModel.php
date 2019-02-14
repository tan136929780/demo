<?php
/**
 * Created by PhpStorm.
 * User: tanxianchen
 * Date: 2019-02-20
 * Time: 下午4:25
 */
namespace app\models;

use app\components\Constant;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class BaseModel extends ActiveRecord
{
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
            ],
        ];
    }

    /**
     * Name: status
     * Desc: Get the Active status
     * User: tanxianchen
     * Date: 2019-02-20
     * @return array
     */
    public function status()
    {
        return [
            Constant::STATUS_ENABLE => Yii::t('app', 'Active')/*'有效'*/,
            Constant::STATUS_DISABLE => Yii::t('app', 'Inactive')/*'冻结'*/,
        ];
    }

    /**
     * Name: getFirstErrorMessage
     * Desc: 获取第一个错误信息
     * User: tanxianchen
     * Date: 2019-02-20
     * @return mixed|string
     */
    public function getFirstErrorMessage()
    {
        return $this->hasErrors() ? current($this->getFirstErrors()) : '';
    }

    /**
     * Name: _tableName
     * Desc: 返回子类的数据表名
     * User: tanxianchen
     * Date: 2019-02-20
     * returnFormat: string
     */
    private function _tableName()
    {
        $childClass = get_called_class();
        return $childClass::tableName();
    }
}



