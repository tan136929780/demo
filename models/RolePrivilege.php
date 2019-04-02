<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "role_privilege".
 *
 * @property int $id
 * @property int $role_id 角色ID
 * @property int $privilege_id 权限ID
 * @property int $updated_at
 * @property int $created_at
 */
class RolePrivilege extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'role_privilege';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                ['role_id', 'privilege_id'], 'required',
                ['role_id', 'privilege_id', 'updated_at', 'created_at'], 'integer',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role_id' => 'Role ID',
            'privilege_id' => 'Privilege ID',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}
