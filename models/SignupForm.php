<?php

namespace app\models;

use Yii;
use yii\base\Model;

class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'email', 'password'], 'required'],
            [['email'], 'email'],
            [['username', 'email'], 'unique', 'targetClass' => '\app\models\User'],
        ];
    }

    /**
     * Signs up a user.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->auth_key = $user->generateAuthKey();
            $user->setPassword($this->password);
            $user->save();
            return $user ?? null;
        }
        return null;
    }
}
