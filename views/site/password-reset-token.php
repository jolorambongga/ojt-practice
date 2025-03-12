<?php
use yii\helpers\Html;

$resetLink = Yii::$app->urlManager->createAbsoluteUrl([
    'site/reset-password', 
    'token' => $user->password_reset_token
]);
?>

<div style="font-family: Arial, sans-serif; color: #333;">
    <h2 style="color: #007bff;">Hello <?= Html::encode($user->username) ?>,</h2>

    <p>
        We received a request to reset your password. Click the button below to proceed:
    </p>

    <div style="text-align: center; margin: 20px 0;">
        <a href="<?= $resetLink ?>" 
           style="
               background-color: #007bff;
               color: #fff;
               padding: 10px 20px;
               text-decoration: none;
               border-radius: 5px;
               display: inline-block;
           ">
           Reset Password
        </a>
    </div>

    <p>If you didn't request a password reset, you can ignore this email.</p>
</div>
