<?php

use hail812\adminlte\widgets as Widget;
use app\models\User;

$this->title = 'Starter Page';
$this->params['breadcrumbs'] = [['label' => $this->title]];
?>
<div class="container-fluid">
    <!-- <?= Yii::$app->session->setFlash('success'); ?> -->
    <!-- <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="row">
            <div class="col-lg-12">
                <?= Widget\Alert::widget([
                    'type' => 'success',
                ]) ?>
            </div>
        </div>
    <?php endif; ?> -->
    <?php
    date_default_timezone_set('Asia/Manila'); // Set timezone to Philippines

    $user = Yii::$app->user->identity;
    $name = $user ? $user->first_name : 'Guest';

    // Get the current hour
    $hour = date("H");

    // Determine the appropriate greeting
    if ($hour >= 5 && $hour < 12) {
        $greeting = "Good Morning";
    } elseif ($hour >= 12 && $hour < 18) {
        $greeting = "Good Afternoon";
    } elseif ($hour >= 18 && $hour < 22) {
        $greeting = "Good Evening";
    } else {
        $greeting = "Good Night";
    }
    ?>

    <div class="row">
        <div class="col-12">
            <h1><?= $greeting ?>, <?= $name ?>!</h1>
        </div>
    </div>



    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
            <?= Widget\SmallBox::widget([
                'title' => '150',
                'text' => 'Total Clients',
                'icon' => 'fas fa-users',
            ]) ?>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
            <?php $smallBox = Widget\SmallBox::begin([
                'title' => '150',
                'text' => 'Total Appointments',
                'icon' => 'fas fa-calendar',
                'theme' => 'success'
            ]) ?>
            <!-- RIBBON -->
            <!-- <?= Widget\Ribbon::widget([
                        'id' => $smallBox->id . '-ribbon',
                        'text' => 'Ribbon',
                        'theme' => 'warning',
                        'size' => 'lg',
                        'textSize' => 'lg'
                    ]) ?> -->
            <?php Widget\SmallBox::end() ?>
        </div>
        <!-- LOADING -->
        <!-- <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <?= Widget\SmallBox::widget([
                'title' => '44',
                'text' => 'User Registrations',
                'icon' => 'fas fa-user-plus',
                'theme' => 'gradient-success',
                'loadingStyle' => true
            ]) ?>
        </div> -->
    </div>
</div>