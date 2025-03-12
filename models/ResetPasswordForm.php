<?php

namespace app\models;

use Yii;
use yii\base\Model;

use app\models\User;

class ResetPasswordForm extends Model
{
    public $password;

    public function rules()
    {
        return [
            [['password'], 'required'],
            [['password'], 'string', 'min' => 6],
        ];
    }

    public function resetPassword($token)
    {
        $user = User::findByPasswordResetToken($token);

        if (!$user) {
            return false;
        }

        $user->password = Yii::$app->security->generatePasswordHash($this->password);
        $user->removePasswordResetToken();
        return $user->save(false);
    }
}
