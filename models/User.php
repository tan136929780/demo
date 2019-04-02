<?php

namespace app\models;

use Yii;
use app\components\Constant;

class User extends Users implements \yii\web\IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne([
            'id'     => $id,
            'status' => Constant::TYPE_ACTIVE
        ]);;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
//        foreach (self::$users as $user) {
//            if ($user['accessToken'] === $token) {
//                return new static($user);
//            }
//        }
        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne([
            'user_code' => $username,
            'status'    => Constant::TYPE_ACTIVE
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Name: getMenus
     * Desc: 获取用户菜单选项
     * User: tanxianchen
     * Date: 2019-02-20
     * @return mixed
     */
    public function getMenus()
    {
        $key = Constant::USER_MENU_CACHE_KEY;
        //Get user menus from session
        $menus = Yii::$app->getSession()
                          ->get($key);
        if (empty($menus)) {
            //Get user menus from database
            $privileges = $this->getRolePrivileges();
            function queryPrivilege($pid = 0, $depth = 0, $privileges = [])
            {
                $items = Privilege::find()
                                  ->select([
                                      'id',
                                      'pid',
                                      'name',
                                      'controller',
                                      'action',
                                      'params',
                                      'depth'
                                  ])
                                  ->where([
                                      'id'      => $privileges,
                                      'pid'     => $pid,
                                      'deleted' => 'N',
                                      'depth'   => $depth,
                                      'menu'    => 'Y'
                                  ])
                                  ->orderBy('sequence')
                                  ->asArray()
                                  ->all();
                if (!empty($items)) {
                    foreach ($items as $k => $item) {
                        $items[$k]['items'] = queryPrivilege($item['id'], intval($item['depth']) + 1, $privileges);
                    }
                }
                return $items;
            }

            $menus = queryPrivilege(0, 1, $privileges);
            //Set user menus session
            Yii::$app->getSession()
                     ->set($key, $menus);
        }
        return $menus;
    }
}
