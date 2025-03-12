<?php

namespace app\models;

use Yii;
use yii\base\Model;

class RegisterForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $confirm_password;
    public $first_name;
    public $last_name;
    public $phone_number;

    public function rules()
    {
        return [
            [['username', 'email', 'password', 'confirm_password', 'first_name', 'last_name', 'phone_number'], 'required'],
            ['email', 'email'],
            ['username', 'unique', 'targetClass' => User::class, 'message' => 'This username is already taken.'],
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'This email is already registered.'],
            ['password', 'string', 'min' => 6],
            ['confirm_password', 'compare', 'compareAttribute' => 'password', 'message' => 'Passwords do not match.'],
            ['phone_number', 'match', 'pattern' => '/^\d{10,15}$/', 'message' => 'Invalid phone number format.'],
        ];
    }

    public function register()
    {
        if (!$this->validate()) {
            return false;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->phone_number = $this->phone_number;
        $user->setPassword($this->password);

        return $user->save();
    }
}
