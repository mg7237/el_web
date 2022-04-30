 
<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>
<style>
    .modal-content {
        position: relative !important;
        background-color: #fff !important;
        border: 1px solid #999 !important;
        border: 1px solid rgba(0,0,0,0.2);
        border-radius: 6px !important;
        box-shadow: 0 3px 9px rgba(0,0,0,0.5) !important;
        background-clip: padding-box !important;
        outline: 0;
        padding: 20px;
    }
    ul.time_suggestion {
        height: 119px;
        position: absolute;
        background-color: white;
        overflow-y: scroll;
        width: 100%;
        margin-top: 0px;
        /* display: inline-flex; */
        padding-left: 5px;
        z-index: 9999;
    }
    li.apply_time {
        display: inline-flex;
        width: 100%;
        padding-left: 42%;
        cursor: pointer;
    }

    li.apply_time:hover {
        background-color: grey;
    }

    span.close_time_picker {
        position: absolute;
        z-index: 10000;
        position: absolute;
        right: 9px;
        background: red;
        padding-left: 5px; 
        border-radius: 16px;
        width: 20px;
        height: 20px;
        color: white;
        cursor: pointer;
        margin-top: -35px;
    }
    .field-propertyvisits-visit_time{
        position: relative;
    }
</style>
<script>
    $(document).on('click', '.close_time_picker', function () {
        $(this).siblings('.time_suggestion').html();
        $(this).siblings('.time_suggestion').hide();
        $(this).closest('.field-propertyvisits-visit_time .help-block').show();
        $(this).remove();
    });
    $(document).on('click', '.apply_time', function () {
        $(this).closest('ul').siblings('.visit_time_class').val($(this).text());
        $(this).closest('ul').html();
        $(this).closest('ul').hide();
        $(this).closest('.field-propertyvisits-visit_time .help-block').show();
        $(this).closest('ul').siblings('.close_time_picker').remove();

    });
    // $(document).ready(function(){



    $(document).on('focus', '.visit_time_class', function () {
        var list = '';
        var hh = '';
        var mm = '';
        for (var i = 11; i <= 21; i++) {
            if (i <= 9) {
                hh = '0' + i;
            } else {
                hh = '' + i;
            }
            var j = 0;
            while (j <= 55) {
                if (j < 30) {
                    mm = '0' + j;
                } else {
                    mm = '' + j;
                }
                j = j + 30;
                if (hh == 21 && mm == 30) {

                } else {
                    list = list + '<li class="apply_time">' + hh + ':' + mm + '</li>';
                }
            }


        }
        $(this).siblings('.time_suggestion').html(list);
        $(this).closest('.close_time_picker').remove();
        $(this).siblings('.time_suggestion').show();
        $(this).closest('.field-propertyvisits-visit_time .help-block').hide();
        $(this).closest('div').append('<span class="close_time_picker">X</span>');
    });

    $(function () {
        $(".datepicker1").datepicker({
            minDate: 0,
            changeMonth: true,
            changeYear: true,
            // yearRange: "-0:+1",
            minDate: '0',
            numberOfMonths: 1,
            dateFormat: 'yy-mm-dd',

        });

        var enforceModalFocusFn = $.fn.modal.Constructor.prototype.enforceFocus;

        $.fn.modal.Constructor.prototype.enforceFocus = function () {};

        $confModal.on('hidden', function () {
            $.fn.modal.Constructor.prototype.enforceFocus = enforceModalFocusFn;
        });

        $confModal.modal({backdrop: false});
    });

</script>
<div class="modal-header">
    Add Applicant
    <button type="button" class="close" data-dismiss="modal">&times;</button>

</div>
<div class="modal-body">		
    <?php
    $form = ActiveForm::begin(['id' => 'applicantform', 'options' => [
                    'class' => 'forminputpopup'
                ], 'enableAjaxValidation' => true]);
    ?>

    <?= $form->field($model, 'full_name')->textInput(['maxlength' => true, 'placeholder' => 'Applicant name*', 'class' => 'form-control isCharacter'])->label(false); ?>

    <?= $form->field($model, 'email_id')->textInput(['maxlength' => true, 'placeholder' => 'Applicant email id*'])->label(false); ?>

    <?= $form->field($model, 'contact_number')->textInput(['maxlength' => true, 'placeholder' => 'Applicant contact number*', 'class' => 'form-control isPhone'])->label(false); ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true, 'placeholder' => 'Address Line 1*'])->label(false); ?>
    <?= $form->field($model, 'address_line_2')->textInput(['maxlength' => true, 'placeholder' => 'Address Line 2'])->label(false); ?>
    <?php
    $states = \app\models\States::find()->all();
    $stateData = \yii\helpers\ArrayHelper::map($states, 'id', 'name');
    ?>
    <?= $form->field($model, 'state')->dropDownList($stateData, ['prompt' => 'Select state', 'url' => \Yii::$app->getUrlManager()->createUrl('users/getcity')])->label(false); ?>

    <?php
    $cities = \app\models\Cities::find()->where(['state_id' => $model->state])->all();
    $cityData = \yii\helpers\ArrayHelper::map($cities, 'id', 'city_name');
    ?>
    <?= $form->field($model, 'city')->dropDownList($cityData, ['prompt' => 'Select city', 'url' => \Yii::$app->getUrlManager()->createUrl('advisers/getregions')])->label(false); ?>

    <?php
    $regions = \app\models\Regions::find()->where(['city_id' => $model->city])->all();
    $cityData = \yii\helpers\ArrayHelper::map($regions, 'id', 'name');
    ?>
    <?= $form->field($model, 'region')->dropDownList($cityData, ['prompt' => 'Select region'])->label(false); ?>


    <?= $form->field($model, 'pincode')->textInput(['maxlength' => true, 'placeholder' => 'Pincode*', 'class' => 'form-control isNumber'])->label(false); ?>

    <?= $form->field($model, 'communication')->textarea(['rows' => 3, 'placeholder' => ''])->label(false); ?>
    <div class="form-group">
        <div class="col-md-6">
            <input type="text" name="follow_up_date" class="form-control datepicker1" placeholder="Follow Up Date" required>
        </div>
        <div class="col-md-6">
            <input type="text" name="follow_up_time" class="form-control visit_time_class" placeholder="Follow Up Time" required>
            <ul class="time_suggestion" style="display: none"></ul>
        </div>
    </div>
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
