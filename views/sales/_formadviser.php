 
<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>
<style>
    .state {
        float: left;
        width: 33%;
    }
</style>
<div class="modal-header">
    <button type="button" class="close closetext" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title modaltitletextnew">Create Property Advisor Lead</h4>

</div>
<div class="modal-dialog modal-dialognew">

    <div class="modal-body loginbody signupbody">		
        <?php
        $form = ActiveForm::begin(['id' => 'adviserform', 'options' => [
                        'class' => 'forminputpopup'
                    ], 'enableAjaxValidation' => true]);
        ?>

        <?= $form->field($model, 'full_name')->textInput(['maxlength' => true, 'placeholder' => 'Advisor name*'])->label(false); ?>

        <?= $form->field($model, 'email_id')->textInput(['maxlength' => true, 'placeholder' => 'Advisor email id*'])->label(false); ?>

        <?= $form->field($model, 'contact_number')->textInput(['maxlength' => true, 'placeholder' => ' Advisor contact number*', 'class' => 'isPhone'])->label(false); ?>

        <?= $form->field($model, 'communication')->textarea(['rows' => 3, 'placeholder' => 'Comments'])->label(false); ?>

        <?= $form->field($model, 'address')->textInput(['maxlength' => true, 'placeholder' => 'Address Line 1*'])->label(false); ?>
        <?= $form->field($model, 'address_line_2')->textInput(['maxlength' => true, 'placeholder' => 'Address Line 2*'])->label(false); ?>

        <?php
        $states = \app\models\States::find()->all();
        $stateData = \yii\helpers\ArrayHelper::map($states, 'id', 'name');
        ?>
        <div class="state"> 

            <?= $form->field($model, 'state')->dropDownList($stateData, ['prompt' => 'Select state', 'url' => \Yii::$app->getUrlManager()->createUrl('users/getcity')])->label(false); ?>
        </div>
        <?php
        $cities = \app\models\Cities::find()->where(['state_id' => $model->state])->all();
        $cityData = \yii\helpers\ArrayHelper::map($cities, 'id', 'city_name');
        ?>
        <div class="state"> 

            <?= $form->field($model, 'city')->dropDownList($cityData, ['prompt' => 'Select city', 'url' => \Yii::$app->getUrlManager()->createUrl('advisers/getregions')])->label(false); ?>
        </div>
        <?php
        $regions = \app\models\Regions::find()->where(['city_id' => $model->city])->all();
        $cityData = \yii\helpers\ArrayHelper::map($regions, 'id', 'name');
        ?>
        <div class="state"> 

            <?= $form->field($model, 'region')->dropDownList($cityData, ['prompt' => 'Select region'])->label(false); ?>
        </div>

        <?= $form->field($model, 'pincode')->textInput(['maxlength' => true, 'placeholder' => 'Pincode*', 'class' => 'form-control isNumber1'])->label(false); ?>




        <div class="row advisorbox">
            <div class="col-md-6">
                <?= Html::submitButton('Save', ['class' => 'btn savechanges_submit_contractaction']) ?>
            </div>
            <div class="col-md-6">
                <button type="button" class="btn savechanges_submit cancel_confirm" data-dismiss="modal">Cancel</button>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
    <div class="modal-footer">

    </div>
</div>
