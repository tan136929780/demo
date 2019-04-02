<?php

namespace app\models;

use app\components\Constant;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "users".
 *
 * @property int    $id
 * @property string $user_code 登录名
 * @property string $password 密码
 * @property string $name 名字
 * @property string $phone 联系电话
 * @property string $email 邮箱
 * @property string $province 省份
 * @property string $city 城市
 * @property string $address 地址
 * @property string $post_code 邮编
 * @property int    $category 客户种类：1-普通客户，2-大客户
 * @property int    $status 客户状态：1-激活，0-禁止
 * @property string $session_token 客户状态
 * @property string $create_user_id 创建人
 * @property string $update_user_id 创建人
 * @property int    $updated_at
 * @property int    $created_at
 */
class Users extends BaseModel
{
    public $roles = [];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                [
                    'category',
                    'status',
                    'updated_at',
                    'created_at',
                    'create_user_id',
                    'update_user_id'
                ],
                'integer'
            ],
            [
                [
                    'user_code',
                    'name'
                ],
                'string',
                'max' => 100
            ],
            [
                [
                    'password',
                    'phone'
                ],
                'string',
                'max' => 200
            ],
            [
                [
                    'email',
                    'city',
                    'address'
                ],
                'string',
                'max' => 255
            ],
            [
                [
                    'province',
                    'session_token',
                ],
                'string',
                'max' => 50
            ],
            [
                ['post_code'],
                'string',
                'max' => 20
            ],
            [
                [
                    'user_code',
                    'password',
                    'name',
                    'phone',
                    'email',
                    'province',
                    'city',
                    'address',
                    'post_code',
                    'category',
                    'status',
                    'create_user_id',
                    'update_user_id',
                ],
                'required'
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'             => 'ID',
            'user_code'      => '账户名',
            'password'       => '密码',
            'name'           => '用户名',
            'phone'          => '联系电话',
            'email'          => '邮箱',
            'province'       => '省/市/自治区',
            'city'           => '市',
            'address'        => '详细地址',
            'post_code'      => '邮编',
            'category'       => '用户类别',
            'status'         => '用户状态',
            'session_token'  => '登录识别',
            'create_user_id' => '创建用户',
            'update_user_id' => '修改用户',
            'updated_at'     => '更新时间',
            'created_at'     => '创建时间',
        ];
    }

    public function getRole()
    {
        return $this->hasOne(Role::className(), ['id' => 'role_id'])
                    ->viaTable('user_role', ['user_id' => 'id']);
    }

    /**
     * Name: checkpass
     * Desc: check password
     * User: tanxianchen
     * Date: 2019-02-20
     * @return array
     */
    public static function checkpass($str)
    {
        $modes = 0;
        //正则表达式验证符合要求的
        if (strlen($str) < 8) {
            return $modes;
        }
        if (preg_match("/\\d/", $str)) {
            $modes++; //数字
        }
        if (preg_match("/[a-zA-Z]/", $str)) {
            $modes++; //小写
        }
        if (preg_match("/\\W/", $str)) {
            $modes++; //特殊字符
        }
        //用户自己重置密码后登录后强制修改密码 jira-3767 zangxiaoxiao
        if (strlen($str) == 19) {
            $i = '_init_';
            if (strpos($str, $i) !== false) {
                $modes = -1;
            }

        }
        //逻辑处理
        switch ($modes) {
            case -1:
                return -1;
                break;
            case 1:
                return 1;
                break;
            case 2:
                return 2;
                break;
            case 3:
            case 4:
                return strlen($str) < 8 ? 1 : 4;
                break;
        }
    }

    /**
     * Name: saveUser
     * Desc: Set password
     * User: tanxianchen
     * Date: 2019-02-20
     * @return boolean
     */

    public function saveUser()
    {
        if ($this->isNewRecord) {
            $this->password       = $this->password == '' ? Yii::$app->security->generatePasswordHash(Yii::$app->params['DEFAULT_PASSWORD']) : Yii::$app->security->generatePasswordHash($this->password);
            $this->create_user_id = 1;
            $this->update_user_id = 1;
        }

        if (!$this->save()) {
            throw new Exception(Yii::t('app', $this->getFirstErrorMessage()));
        }

        $roleModel = UserRole::findOne(['user_id' => $this->id]);
        if ($roleModel == null) {
            $roleModel = new UserRole();
        }
        //Delete old role for user
        $roleModel->user_id = $this->id;
        if (!$roleModel->load(Yii::$app->getRequest()
                                       ->post()) || !$roleModel->save()
        ) {
            throw new Exception(Yii::t('app', $roleModel->getFirstErrorMessage()));
        }
        return true;
    }

    /**
     * Name: status
     * Desc: Get the Active status
     * User: tanxianchen
     * Date: 2019-02-20
     * @return array
     */
    public function category()
    {
        return [
            Constant::TYPE_NORMAL => Yii::t('app', '普通用户'),
            Constant::TYPE_VIP    => Yii::t('app', '贵宾用户'),
        ];
    }

    /**
     * Name: getUserName
     * Desc: Get the Active status
     * User: tanxianchen
     * Date: 2019-02-20
     * @return string
     */
    public function getUserName()
    {
        $user = self::findOne(['id' => $this->id]);
        return $user->name;
    }

    /**
     * Name: getCreateUserName
     * Desc: Get the Active status
     * User: tanxianchen
     * Date: 2019-02-20
     * @return string
     */
    public function getCreateUserName()
    {
        $user = self::findOne(['id' => $this->create_user_id]);
        return $user->name;
    }

    /**
     * Name: getUpdateUserName
     * Desc: Get the Active status
     * User: tanxianchen
     * Date: 2019-02-20
     * @return string
     */
    public function getUpdateUserName()
    {
        $user = self::findOne(['id' => $this->update_user_id]);
        return $user->name;
    }

    /**
     * Name: currentUser
     * Desc: 取得当前登录用户
     * User: tanxianchen
     * Date: 2019-02-20
     * @return string
     */
    public static function currentUser()
    {
        return Yii::$app->getUser()
                        ->getIdentity();
    }

    /**
     * Name: hasPrivilege
     * Desc: 判断用户是否有权限
     * User: tanxianchen
     * Date: 2019-02-20
     * @param mixed $privilege
     * @return bool|mixed
     */
    public function hasPrivilege($privilege = null)
    {
        //如果是超级管理员,全部有权限
        $roles = $this->getUserRoles();
        if (array_search(1, $roles)) {
            return true;
        }
        //非管理员
        $model = is_array($privilege) ? Privilege::findOne($privilege) : Privilege::findOne(['name' => $privilege]);
        //如果查不到权限信息,则默认有权限
        if ($model) {
            $privileges = $this->getRolePrivileges();
            return in_array($model->id, $privileges);
        }
        return true;
    }

    /**
     * Name: getUserRole
     * Desc: 获取当前用户Role name
     * User: tanxianchen
     * Date: 2019-02-20
     * @return array
     */
    public function getUserRole()
    {
        $role     = '';
        $userRole = UserRole::findOne(['user_id' => $this->id]);
        if ($userRole) {
            $role = Role::findOne(['id' => $userRole->role_id]);
        }
        return $role ? $role->name : '';
    }

    /**
     * Name: getUserRoles
     * Desc: 获取当前用户所有Roles
     * User: tanxianchen
     * Date: 2019-02-20
     * @return array
     */
    public function getUserRoles()
    {
        //Get user roles from session
        $roles = Yii::$app->getSession()
                          ->get(Constant::USER_ROLE_CACHE_KEY);
        if (empty($roles)) {
            //Get user roles from database
            $roles = ArrayHelper::getColumn(UserRole::findAll(['user_id' => $this->id]), 'role_id');
            //Set user roles session
            Yii::$app->getSession()
                     ->set(Constant::USER_ROLE_CACHE_KEY, $roles);
        }
        return $roles;
    }

    /**
     * Name: getRolePrivileges
     * Desc: 获取当前用户所有角色下的所有权限
     * User: tanxianchen
     * Date: 2019-02-20
     * @return array|mixed
     */
    public function getRolePrivileges()
    {
        //Get privileges from session
        $privileges = Yii::$app->getSession()
                               ->get(Constant::USER_PRIVILEGE_CACHE_KEY);
        if (empty($privileges)) {
            $roles = $this->getUserRoles();
            //Get privilege from database
            $privileges = array_search(1, $roles) === false ? ArrayHelper::getColumn(RolePrivilege::findAll(['role_id' => $roles]), 'privilege_id') : ArrayHelper::getColumn(Privilege::find()
                                                                                                                                                                                      ->all(), 'id');
            //Set privileges session
            Yii::$app->getSession()
                     ->set(Constant::USER_PRIVILEGE_CACHE_KEY, $privileges);
        }
        return $privileges;
    }

    /**
     * Name: clearUserAllCache
     * Desc: clear three user session info
     * User: tanxianchen
     * Date: 2019-02-20
     * @return mixed
     */
    public function clearUserAllCache()
    {
        $clearArr = [
            Constant::USER_ROLE_CACHE_KEY,
            Constant::USER_PRIVILEGE_CACHE_KEY,
            Constant::USER_MENU_CACHE_KEY,
        ];
        foreach ($clearArr as $citem) {
            $this->clearUserEachCache([1], $citem, true);
        }
    }

    /**
     * Name: clearUserEachCache
     * Desc: clear single session
     * User: tanxianchen
     * Date: 2019-02-20
     * @return none
     */
    private function clearUserEachCache($data, $cache_key, $isUpdate = false)
    {
        // 判断是否清除 session 数据
        if (!empty($data) && $isUpdate === true) {
            Yii::$app->session->remove($cache_key);
        }
    }
}
