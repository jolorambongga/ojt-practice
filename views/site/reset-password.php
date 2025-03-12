<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="container mt-5">
    <div class="card shadow-sm p-4">
        <h1 class="text-center text-success">🔒 Reset Password</h1>
        <p class="text-center text-muted">
            Create a strong password to protect your account.
        </p>

        <?php $form = ActiveForm::begin(['options' => ['class' => 'mt-4']]); ?>
            <?= $form->field($model, 'password')->passwordInput([
                'class' => 'form-control',
                'placeholder' => 'Enter new password'
            ]) ?>
            <div class="text-center">
                <?= Html::submitButton('Save Password', ['class' => 'btn btn-success w-100 mt-3']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
