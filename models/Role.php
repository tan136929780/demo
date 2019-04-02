<?php

namespace app\models;

use app\components\Constant;
use Yii;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "role".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $status
 * @property integer $create_user_id
 * @property integer $update_user_id
 * @property string $updated_at
 * @property string $created_at
 */
class Role extends BaseModel
{
    // 为提交Post请求时，取得Post中提交的权限而创建，保存Post过来的权限内容
    public $privileges = [];
    public $groups = [];

    public $_vendorRoleNames;

    public function behaviors()
    {
        $parent = parent::behaviors();
        $self = [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'create_user_id',
                'updatedByAttribute' => 'update_user_id',
            ]
        ];
        return array_merge($parent, $self);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['privileges'], 'privilegesFmt'],
            [['name', 'status', 'description'], 'required'],
            [['status', 'updated_at', 'created_at'], 'integer'],
            [['name'], 'unique'],
        ];
    }

    /**
     * Name: privilegesFmt
     * Desc: 將post過來的privilege更新到privilege變量
     * User: tanxianchen
     * Date: 2019-02-20
     * @return mixed
     */
    public function privilegesFmt()
    {
        if (is_array($this->privileges)) {
            $this->privileges = $this->privileges;
        }
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '角色名',
            'description' => '描述',
            'status' => '状态',
            'create_user_id' => '创建者ID',
            'update_user_id' => '修改者ID',
            'updated_at' => '更新时间',
            'created_at' => '创建时间',
        ];
    }

    /**
     * Name: getLayerPrivileges
     * Desc: 獲取角色權限狀態（分層主要為了渲染頁面）
     * User: tanxianchen
     * Date: 2019-02-20
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getLayerPrivileges()
    {
        function privileges(&$privileges = [], $pid = 0, &$activePrivileges)
        {
            $sp = [];
            if (!empty($privileges)) {
                $sp = array_filter($privileges, function ($privilege) use ($pid) {
                    return intval($privilege['pid']) === $pid;
                });
                if (!empty($sp)) {
                    foreach ($sp as $k => $v) {
                        $sp[$k]['active'] = in_array($v['id'], $activePrivileges);
                        $sp[$k]['items'] = privileges($privileges, intval($v['id']), $activePrivileges);
                    }
                }
            }
            return $sp;
        }

        $activePrivileges = $this->getPrivilegesArray();
        $privileges = Privilege::find()->select(['id', 'pid', 'name'])->where(['deleted' => 'N'])->asArray()->all();
        return privileges($privileges, 0, $activePrivileges);
    }

    /**
     * Name: getRolePrivileges
     * Desc: 获取当前role模型的RolePrivileges
     * User: tanxianchen
     * Date: 2019-02-20
     * @return \yii\db\ActiveQuery
     */
    public function getRolePrivileges()
    {
        return $this->hasMany(RolePrivilege::className(), ['role_id' => 'id']);
    }

    /**
     * Name: getPrivilegesArray
     * Desc: 获得当前role的所有Privilege Id
     * User: tanxianchen
     * Date: 2019-02-20
     * @return array
     */
    public function getPrivilegesArray()
    {
        return array_column($this->getRolePrivileges()->asArray()->all(), 'privilege_id');
    }

    /**
     * Name: saveWithPrivileges
     * Desc: 保存Privilege權限
     * User: tanxianchen
     * Date: 2019-02-20
     * @return bool
     */
    public function saveWithPrivileges()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // 保存Model
            if (!$this->save()) {
                throw new Exception(Yii::t('app', '保存权限失败.'));
            }

            //增加的權限
            $plus = array_diff($this->privileges, $this->privilegesArray);
            $plus = array_map(function ($item) {
                return [$this->id, intval($item), time(), time()];
            }, $plus);

            if (!empty($plus)) {
                Yii::$app->getDb()->createCommand()->batchInsert(
                    RolePrivilege::tableName(),
                    ['role_id', 'privilege_id', 'created_at', 'updated_at'],
                    $plus
                )->execute();
            }

            //刪除的權限
            $minus = array_diff($this->privilegesArray, $this->privileges);
            if (!empty($minus)) {
                RolePrivilege::deleteAll(['role_id' => $this->id, 'privilege_id' => $minus]);
            }
            // Commit
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $this->addErrors([
                'error' => Yii::t('app', $e->getMessage()),
            ]);
            $transaction->rollBack();
            return false;
        }
    }

    /**
     * 取得所有的Status种类
     * @return array
     */
    public function status()
    {
        return [
            Constant::TYPE_ACTIVE => Yii::t('app', 'Active'),
            Constant::TYPE_INACTIVE => Yii::t('app', 'InActive '),
        ];
    }

    public function status_string()
    {
        return $this->status()[(int)$this->status];
    }

    /**
     * Name: getRoleType
     * Desc: 获取当前角色的类型（工程师/前台/管理员/SDM等）
     * User: tanxianchen
     * Date: 2019-02-20
     * @return int
     */
    public function getRoleType()
    {
        $role_name = $this->name;

        $roleArr = [
            'Admin' => Constant::ROLE_TYPE_ADMIN,
            'Normal' => Constant::ROLE_TYPE_TYPE_NORMAL,
        ];

        foreach ($roleArr as $key => $value) {
            if (is_numeric(strpos($role_name, $key))) {
                return $value;
            }
        }

        return [];
    }


    /**
     * Name: getActiveRoles
     * Desc: 获取有效角色
     * User: tanxianchen
     * Date: 2019-02-20
     * @return array
     */
    public function getActiveRoles($role_in_use = null)
    {
        $query = $this::find()->where(['status' => 1]);
        if (!empty($role_in_use)) {
            $query->orWhere(['id' => $role_in_use]);
        }
        return ArrayHelper::map($query->all(), 'id', 'name');
    }

    public function getActiveRoleDesc($role_in_use = null)
    {
        $query = $this::find()->where(['status' => 1]);
        if (!empty($role_in_use)) {
            $query->orWhere(['id' => $role_in_use]);
        }
        return ArrayHelper::map($query->all(), 'id', 'description');
    }

    /**
     * Name: nextRoleName
     * Desc: 按顺序获取下一个RoleName
     * User: tanxianchen
     * Date: 2019-02-20
     * @param $roleName
     * @return string
     */
    public static function nextRoleName($roleName)
    {
        $name = '';
        if (!empty($roleName)) {
            $role = self::find()
                ->where("`name` REGEXP '^{$roleName}[0-9]{3}'")
                ->orderBy('name DESC')
                ->limit(1)
                ->one();
            $sequence = 1;
            if ($role) {
                $sequence = intval(substr($role->name, -3)) + 1;
            }
            $name = $roleName . str_pad($sequence, 3, '0', STR_PAD_LEFT);
        }
        return $name;
    }
}
