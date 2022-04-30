<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PropertiesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="properties-search">

    <?php
    $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
    ]);
    ?>


    <?= $form->field($model, 'property_code') ?>

    <?= $form->field($model, 'rent') ?>

    <?php //$form->field($model, 'deposit') ?>

    <?php //$form->field($model, 'token_amount') ?>

    <?php // echo $form->field($model, 'maintenance') ?>

    <?php // echo $form->field($model, 'maintenance_included') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'no_of_rooms') ?>

    <?php // echo $form->field($model, 'is_room') ?>

    <?php // echo $form->field($model, 'owner_id') ?>

    <?php // echo $form->field($model, 'property_type') ?>

    <?php // echo $form->field($model, 'flat_bhk') ?>

    <?php // echo $form->field($model, 'flat_area') ?>

    <?php // echo $form->field($model, 'flat_type') ?>

    <?php // echo $form->field($model, 'availability_from') ?>

    <?php // echo $form->field($model, 'property_description') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'created_time') ?>

    <?php // echo $form->field($model, 'update_time') ?>

    <?php // echo $form->field($model, 'no_of_beds')  ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
