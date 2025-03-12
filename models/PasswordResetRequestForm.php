<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\validators\ExistValidator;

class PasswordResetRequestForm extends Model
{
    public $email;

    public function rules()
    {
        return [
            [['email'], 'required'],
            [['email'], 'email'],
            [['email'], 'string', 'max' => 255],
        ];
    }


    public function sendEmail()
    {
        // Find the user by email
        $user = User::findOne(['email' => $this->email]);

        if (!$user) {
            Yii::error("Password reset request failed: Email not found - " . $this->email, 'password-reset');
            return false;
        }

        // Generate a new password reset token
        $user->generatePasswordResetToken();

        // Save the user model with the new token
        if (!$user->save(false)) {
            Yii::error("Failed to save password reset token for user: " . $this->email . " - Errors: " . json_encode($user->errors), 'password-reset');
            return false;
        }

        // Compose and send the email
        $mailer = Yii::$app->mailer->compose('password-reset-token', ['user' => $user])
            ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->name])
            ->setTo($this->email)
            ->setSubject('Password reset for ' . Yii::$app->name);

        if (!$mailer->send()) {
            Yii::error("Failed to send password reset email to: " . $this->email, 'email');
            return false;
        }

        Yii::info("Password reset email sent successfully to: " . $this->email, 'email');
        return true;
    }
}
