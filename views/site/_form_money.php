<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $form yii\widgets\ActiveForm */
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 wallettop">
            <div class="bank-form">

                <?php $form = ActiveForm::begin(); ?>
                <?php // $form->errorSummary($model); ?>

                <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>


                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>
