<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Login';
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid">

    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Welcome Back!',
                text: '<?= Yii::$app->session->getFlash('success') ?>',
                timer: 3000,
                showConfirmButton: false
            });

            setTimeout(function() {
                window.location.href = "<?= Yii::$app->urlManager->createUrl(['site/index']) ?>";
            }, 3000); // Redirect after 3 seconds
        </script>
    <?php endif; ?>

    <?php if (Yii::$app->session->hasFlash('danger') && Yii::$app->request->isPost): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '<?= Yii::$app->session->getFlash('danger') ?>'
            });
        </script>
    <?php endif; ?>



    <div class="d-flex justify-content-center align-items-start min-vh-100 pt-5">
        <div class="row shadow-lg rounded-3 overflow-hidden w-70">
            <!-- Left Section -->
            <div class="col-md-5 text-white text-center d-flex flex-column justify-content-center align-items-center p-4"
                style="background: #0066FF; border-radius: 20px 0 0 20px;">
                <h2 class="font-weight-bold">Welcome Back!</h2>
                <p>To keep connected with us, please login with your personal info</p>
                <?= Html::a('Register', ['site/register'], ['class' => 'btn btn-outline-light mt-3']) ?>
            </div>

            <!-- Right Section (Login Form) -->
            <div class="col-md-7 p-5">
                <h2 class="text-center text-primary font-weight-bold">Sign in</h2>

                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                <?= $form->field($model, 'username')->textInput(['placeholder' => 'Username']) ?>
                <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Password']) ?>

                <div class="form-group text-center">
                    <?= Html::submitButton('SIGN IN', ['class' => 'btn btn-primary btn-block']) ?>
                </div>
                <?php ActiveForm::end(); ?>

                <div class="text-center">
                    <?= Html::a('Forgot Password?', ['site/request-password-reset'], ['class' => 'btn btn-link']) ?>
                </div>
            </div>
        </div>
    </div>
</div>