<?php

namespace app\models;

use app\components\Constant;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            if (Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0)) {
                $user = Users::currentUser();

                $this->setUserSession($user);

                //把用户的角色保存在session中
                if (isset($this->getUser()->role)) {
                    Yii::$app->getSession()->set(Constant::USER_ROLE_TYPE_KEY, $this->getUser()->role->roleType);
                }
                User::updateAll(['session_token' => '1'], ['id' => $user->id]);
            }
            return true;
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }

    public function setUserSession($user)
    {
        Yii::$app->session['user'] = [
            'user_code' => $user->user_code,
            'name' => $user->name,
            'phone' => $user->phone,
            'email' => $user->email,
            'province' => $user->province,
            'city' => $user->city,
            'address' => $user->address,
            'post_code' => $user->post_code,
            'category' => $user->category,
        ];
    }
}
