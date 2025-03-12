<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use Yii;

class User extends ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return 'users'; // Match the database table name
    }

    /**
     * Finds user by ID
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds user by access token (not commonly used unless for API authentication)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null; // Not used since you're not implementing access tokens
    }

    /**
     * Finds user by username
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * Returns user ID
     */
    public function getId()
    {
        return $this->user_id;
    }

    /**
     * Validates password
     */
    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Hashes password before saving
     */
    public function setPassword($password)
    {
        $this->password = \Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Disabling authKey methods since they're not used
     */
    public function getAuthKey()
    {
        return null;
    }

    public function validateAuthKey($authKey)
    {
        return false;
    }

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
        $this->password_reset_expires_at = date('Y-m-d H:i:s', strtotime('+1 hours'));

        if (!$this->save(false)) {
            Yii::error('Failed to save password reset token: ' . json_encode($this->errors), 'password-reset');
        }
    }


    public static function findByPasswordResetToken($token)
    {
        if (!$token) {
            return null;
        }

        $user = static::findOne(['password_reset_token' => $token]);

        if (!$user || strtotime($user->password_reset_expires_at) < time()) {
            return null;
        }

        return $user;
    }

    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
        $this->password_reset_expires_at = null;
        $this->save(false);
    }
}
