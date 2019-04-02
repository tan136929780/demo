<?php

namespace app\models;

use Yii;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "privileges".
 *
 * @property integer $id
 * @property integer $pid
 * @property string  $name
 * @property string  $controller
 * @property string  $action
 * @property string  $params
 * @property integer $sequence
 * @property string  $deleted
 * @property integer $depth
 * @property integer $menu
 * @property integer $created_at
 * @property integer $updated_at
 */
class Privilege extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'privileges';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                ['name'],
                'required'
            ],
            [
                [
                    'pid',
                    'sequence',
                    'depth',
                    'created_at',
                    'updated_at'
                ],
                'integer'
            ],
            [
                ['pid'],
                'default',
                'value' => 0
            ],
            [
                [
                    'menu',
                    'deleted'
                ],
                'in',
                'range' => [
                    'Y',
                    'N'
                ]
            ],
            [
                [
                    'name',
                    'controller',
                    'action',
                    'params'
                ],
                'string',
                'max' => 50
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => Yii::t('app', 'ID'),
            'pid'        => Yii::t('app', '父级'),
            'name'       => Yii::t('app', '名称'),
            'params'     => Yii::t('app', 'Url'),
            'controller' => Yii::t('app', '控制器'),
            'action'     => Yii::t('app', '方法'),
            'sequence'   => Yii::t('app', '排序'),
            'deleted'    => Yii::t('app', '删除'),
            'depth'      => Yii::t('app', '深度'),
            'menu'       => Yii::t('app', '是否作为菜单'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '修改时间'),
        ];
    }

    /**
     * Name: getChildPrivileges
     * Desc: 根据pid查找其下的所有权限
     * User: tanxianchen
     * Date: 2019-02-20
     * @param $id
     * @return array|null|\yii\db\ActiveRecord[]
     */
    public static function getChildPrivileges($id)
    {
        //id,name
        $privileges = self::find()
                          ->select([
                              'id',
                              'name'
                          ])
                          ->where([
                              'pid'     => $id,
                              'deleted' => 'N'
                          ])
                          ->all();
        if (!empty($privileges)) {
            return $privileges;
        }
        return null;
    }

    /**
     * @name: getPidName
     * @desc: 根据pid获取对应id的name
     * @User: tanxianchen
     * @Date: 2019-02-20
     */
    public function getPidName($pid)
    {
        $result = Privilege::findOne(['id' => $pid]);
        return is_null($result) ? "" : $result->name;
    }

    /**
     * @name: savePrivilege
     * @desc: 插入前获取当前同级栏目中最大sequence,并且值+1
     * @User: tanxianchen
     * @Date: 2019-02-20
     */
    public function savePrivilege()
    {
        $transaction = $this->getDb()
                            ->beginTransaction();
        try {
            function changeChildrenDepth(Privilege $parent = NULL)
            {
                if ($parent) {
                    $privileges = Privilege::findAll(['pid' => $parent->id]);
                    if (!empty($privileges)) {
                        foreach ($privileges as $privilege) {
                            $privilege->depth = intval($parent->depth) + 1;
                            $privilege->save(false);
                            changeChildrenDepth($privilege);
                        }
                    }
                }
            }

            if ($this->isNewRecord || $this->pid != $this->getOldAttribute('pid')) {
                $maxSequence    = Privilege::find()
                                           ->where(['pid' => $this->pid])
                                           ->max('sequence');
                $this->sequence = $maxSequence + 1;
                $parent         = Privilege::findOne($this->pid);
                $this->depth    = $parent ? intval($parent->depth) + 1 : 1;
                changeChildrenDepth($this);
            }
            if (!$this->save()) {
                throw new Exception(Yii::t('app', 'Create privilege failed'));
            }
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            $this->addErrors(['error' => Yii::t('app', $e->getMessage())]);
            return false;
        }
    }

    /**
     * @name: getPidList
     * @desc:获取1级2级菜单列表
     * @User: tanxianchen
     * @Date: 2019-02-20
     */
    public function getPidList()
    {
        return ArrayHelper::map(Privilege::find()
                                         ->select(['id','name'])
                                         ->where([
                                             'depth' => [1, 2, 3
                                             ]
                                         ])
                                         ->all(), 'id', 'name');
    }

    /**
     * @name:getControllerList
     * @param:$path
     * @desc:获取所有controllers文件名
     * @User: tanxianchen
     * @Date: 2019-02-20
     */
    public static function getControllerList($path = array())
    {
        if (empty($path)) {
            return [];
        }
        $allControllersList = array();
        foreach ($path as $key) {
            if (strpos($key, 'Controller')) {
                $allControllersList[strtolower(substr($key, 0, strpos($key, 'Controller')))] = strtolower(substr($key, 0, strpos($key, 'Controller')));
            }
        }
        return $allControllersList;
    }
}
