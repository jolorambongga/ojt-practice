<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Request Password Reset';
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid">

    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Check Your Email!',
                text: '<?= Yii::$app->session->getFlash('success') ?>',
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    <?php endif; ?>

    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '<?= Yii::$app->session->getFlash('error') ?>'
            });
        </script>
    <?php endif; ?>

    <div class="d-flex justify-content-center align-items-start min-vh-100 pt-5">
        <div class="row shadow-lg rounded-3 overflow-hidden w-70">
            <!-- Left Section -->
            <div class="col-md-5 text-white text-center d-flex flex-column justify-content-center align-items-center p-4"
                style="background: #0066FF; border-radius: 20px 0 0 20px;">
                <h2 class="font-weight-bold">Request Password Reset</h2>
                <p>Enter your email, and we'll send you a link to reset your password.</p>
            </div>

            <!-- Right Section (Request Password Reset Form) -->
            <div class="col-md-7 p-5">
                <h2 class="text-center text-primary font-weight-bold">Request Password Reset</h2>

                <?php $form = ActiveForm::begin(['id' => 'password-reset-request-form']); ?>
                <?= $form->field($model, 'email')->textInput(['placeholder' => 'Enter your email', 'autofocus' => true]) ?>
                <div class="form-group text-center">
                    <?= Html::submitButton('Send Reset Link', ['class' => 'btn btn-primary btn-block']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>