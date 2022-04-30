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

                <?= $form->field($model, 'account_holder_name')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'bank_name')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'account_number')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'ifsc_code')->textInput(['maxlength' => true]) ?>

                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>
