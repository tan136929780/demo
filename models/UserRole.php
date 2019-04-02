<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_role".
 *
 * @property int $id
 * @property int $user_id 用户ID
 * @property int $role_id 角色ID
 * @property int $updated_at
 * @property int $created_at
 */
class UserRole extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_role';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                [
                    'user_id',
                    'role_id',
                    'updated_at',
                    'created_at'
                ],
                'integer'
            ],
            [
                [
                    'user_id',
                    'role_id',
                ],
                'required'
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'user_id'    => 'User ID',
            'role_id'    => 'Role ID',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}
