<?php
use yii\captcha\Captcha;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Register';
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid">
    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '<?= Yii::$app->session->getFlash('success') ?>',
                timer: 3000, // Countdown before redirecting
                showConfirmButton: false
            });

            // Redirect after countdown
            setTimeout(function () {
                window.location.href = "<?= Yii::$app->urlManager->createUrl(['site/login']) ?>";
            }, 3000);
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
        <div class="row shadow-lg rounded-3 overflow-hidden w-75">
            <!-- Left Side -->
            <div class="col-md-5 text-white text-center d-flex flex-column justify-content-center align-items-center p-4 left-panel"
                style="background: #0066FF; border-radius: 20px 0 0 20px;">
                <h2 class="font-weight-bold">Get started with us!</h2>
                <p>Sign up for exclusive access</p>
                <a href="<?= Url::to(['site/login']) ?>" class="btn btn-light font-weight-bold px-4">Sign in</a>
            </div>

            <!-- Right Side -->
            <div class="col-md-7 p-5">
                <h2 class="text-center text-primary font-weight-bold">Register</h2>

                <?php $form = ActiveForm::begin(['id' => 'register-form']); ?>

                <?= $form->field($model, 'username')->textInput(['placeholder' => 'Username'])->label(false) ?>
                <?= $form->field($model, 'email')->textInput(['placeholder' => 'Email'])->label(false) ?>
                <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Password'])->label(false) ?>
                <?= $form->field($model, 'confirm_password')->passwordInput(['placeholder' => 'Confirm Password'])->label(false) ?>
                <?= $form->field($model, 'first_name')->textInput(['placeholder' => 'First Name'])->label(false) ?>
                <?= $form->field($model, 'last_name')->textInput(['placeholder' => 'Last Name'])->label(false) ?>
                <?= $form->field($model, 'phone_number')->textInput(['placeholder' => 'Contact Number'])->label(false) ?>

                <!-- CAPTCHA Field (Uncommented) -->


                <div class="text-center">
                    <?= Html::submitButton('REGISTER', ['class' => 'btn btn-primary btn-block font-weight-bold']) ?>
                </div>

                <?php ActiveForm::end(); ?>

                <p class="mt-3 text-center">
                    Already have an account? <a href="<?= Url::to(['site/login']) ?>">Sign in</a>
                </p>
            </div>
        </div>
    </div>

    <!-- <style>
    @media (max-width: 767px) {
        .left-panel {
            border-radius: 20px 20px 0 0 !important;
        }
    }
    </style> -->
</div>