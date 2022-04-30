 
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
        /*border-radius: 6px !important;*/
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
            minDate: new Date(),
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: 'dd-M-yy',

        });

// var enforceModalFocusFn = $.fn.modal.Constructor.prototype.enforceFocus;

// $.fn.modal.Constructor.prototype.enforceFocus = function() {};

// $confModal.on('hidden', function() {
//     $.fn.modal.Constructor.prototype.enforceFocus = enforceModalFocusFn;
// });

// $confModal.modal({ backdrop : false });
    });

</script>
<div class="modal-header">
    Add Applicant
    <button type="button" class="close" data-dismiss="modal">&times;</button>

</div><br>
<div class="modal-body">		
    <?php
    $form = ActiveForm::begin(['id' => 'applicantform', 'options' => [
                    'class' => 'forminputpopup'
                ], 'enableAjaxValidation' => true]);
    ?>

    <?= $form->field($model, 'full_name')->textInput(['maxlength' => true, 'placeholder' => 'Applicant Name*', 'class' => 'form-control isCharacter'])->label(false); ?>

    <?= $form->field($model, 'email_id')->textInput(['maxlength' => true, 'placeholder' => 'Applicant Email Id*'])->label(false); ?>

    <?= $form->field($model, 'contact_number')->textInput(['maxlength' => 15, 'placeholder' => 'Applicant Contact Number*', 'class' => 'form-control '])->label(false); ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true, 'placeholder' => 'Address Line 1*'])->label(false); ?>
    <?= $form->field($model, 'address_line_2')->textInput(['maxlength' => true, 'placeholder' => 'Address Line 2'])->label(false); ?>
    <?= $form->field($modelprofile, 'city_name')->textInput(['placeholder' => 'Applicant city *', 'class' => ''])->label(false); ?>

    <?php
    $states = \app\models\States::find()->where(['status' => 1])->all();
    $stateData = \yii\helpers\ArrayHelper::map($states, 'code', 'name');
    ?>

    <?= $form->field($modelprofile, 'state')->dropDownList($stateData, ['prompt' => 'Select state*', 'url' => \Yii::$app->getUrlManager()->createUrl('users/getcity')])->label(false); ?>

    <?= $form->field($model, 'pincode')->textInput(['maxlength' => 15, 'placeholder' => 'Pincode*', 'class' => 'form-control'])->label(false); ?>

    <?= $form->field($model, 'communication')->textarea(['rows' => 3, 'placeholder' => 'Communication*'])->label(false); ?>

    <?php
    $userType = Yii::$app->user->identity->user_type;
    $userId = Yii::$app->user->identity->id;
    if ($userType == 7) {
        $opsModel = \app\models\OperationsProfile::findOne(['operations_id' => $userId]);
        if ($opsModel->role_code == 'OPEXE' && $opsModel->role_value == 'ELHQ') {
            $cities = \app\models\Cities::find()->where(['status' => 1])->all();
        } else if ($opsModel->role_code == 'OPSTMG') {
            $cities = \app\models\Cities::find()->where(['status' => 1, 'state_code' => $opsModel->role_value])->all();
        } else if ($opsModel->role_code == 'OPCTMG') {
            $cities = \app\models\Cities::find()->where(['status' => 1, 'code' => $opsModel->role_value])->all();
        } else if ($opsModel->role_code == 'OPBRMG') {
            $branchModel = \app\models\Branches::findOne(['branch_code' => $opsModel->role_value]);
            $cities = \app\models\Cities::find()->where(['status' => 1, 'code' => $branchModel->city_code])->all();
        } else if ($opsModel->role_code == 'OPEXE') {
            $branchModel = \app\models\Branches::findOne(['branch_code' => $opsModel->role_value]);
            $cities = \app\models\Cities::find()->where(['status' => 1, 'code' => $branchModel->city_code])->all();
        }
    } else if ($userType == 6) {
        $saleModel = \app\models\SalesProfile::findOne(['sale_id' => $userId]);
        if ($saleModel->role_code == 'SLEXE' && $saleModel->role_value == 'ELHQ') {
            $cities = \app\models\Cities::find()->where(['status' => 1])->all();
        } else if ($saleModel->role_code == 'SLSTMG') {
            $cities = \app\models\Cities::find()->where(['status' => 1, 'state_code' => $saleModel->role_value])->all();
        } else if ($saleModel->role_code == 'SLCTMG') {
            $cities = \app\models\Cities::find()->where(['status' => 1, 'code' => $saleModel->role_value])->all();
        } else if ($saleModel->role_code == 'SLBRMG') {
            $branchModel = \app\models\Branches::findOne(['branch_code' => $saleModel->role_value]);
            $cities = \app\models\Cities::find()->where(['status' => 1, 'code' => $branchModel->city_code])->all();
        } else if ($saleModel->role_code == 'SLEXE') {
            $branchModel = \app\models\Branches::findOne(['branch_code' => $saleModel->role_value]);
            $cities = \app\models\Cities::find()->where(['status' => 1, 'code' => $branchModel->city_code])->all();
        }
    }

    $cityData = \yii\helpers\ArrayHelper::map($cities, 'code', 'city_name');
    ?>
    <?= $form->field($model, 'lead_city')->dropDownList($cityData, ['prompt' => 'Select city*'])->label('Search City'); ?>

    <?php
    if ($userType == 7) {
        $opsModel = \app\models\OperationsProfile::findOne(['operations_id' => $userId]);
        if ($opsModel->role_code == 'OPEXE' && $opsModel->role_value == 'ELHQ') {
            $branches = \app\models\Branches::find()->where('branch_code != :branch_code', ['branch_code' => 'NA'])->all();
        } else if ($opsModel->role_code == 'OPSTMG') {
            $branches = \app\models\Branches::find()->where('branch_code != :branch_code AND state_code = :state_code', ['branch_code' => 'NA', 'state_code' => $opsModel->role_value])->all();
        } else if ($opsModel->role_code == 'OPCTMG') {
            $branches = \app\models\Branches::find()->where('branch_code != :branch_code AND city_code = :city_code', ['branch_code' => 'NA', 'city_code' => $opsModel->role_value])->all();
        } else if ($opsModel->role_code == 'OPBRMG') {
            $branches = \app\models\Branches::find()->where('branch_code = :branch_code', ['branch_code' => $opsModel->role_value])->all();
        } else if ($opsModel->role_code == 'OPEXE') {
            $branches = \app\models\Branches::find()->where('branch_code = :branch_code', ['branch_code' => $opsModel->role_value])->all();
        }
    } else if ($userType == 6) {
        $saleModel = \app\models\SalesProfile::findOne(['sale_id' => $userId]);
        if ($saleModel->role_code == 'SLEXE' && $saleModel->role_value == 'ELHQ') {
            $branches = \app\models\Branches::find()->where('branch_code != :branch_code', ['branch_code' => 'NA'])->all();
        } else if ($saleModel->role_code == 'SLSTMG') {
            $branches = \app\models\Branches::find()->where('branch_code != :branch_code AND state_code = :state_code', ['branch_code' => 'NA', 'state_code' => $saleModel->role_value])->all();
        } else if ($saleModel->role_code == 'SLCTMG') {
            $branches = \app\models\Branches::find()->where('branch_code != :branch_code AND city_code = :city_code', ['branch_code' => 'NA', 'city_code' => $saleModel->role_value])->all();
        } else if ($saleModel->role_code == 'SLBRMG') {
            $branches = \app\models\Branches::find()->where('branch_code = :branch_code', ['branch_code' => $saleModel->role_value])->all();
        } else if ($saleModel->role_code == 'SLEXE') {
            $branches = \app\models\Branches::find()->where('branch_code = :branch_code', ['branch_code' => $saleModel->role_value])->all();
        }
    }

    $branchData = \yii\helpers\ArrayHelper::map($branches, 'branch_code', 'name');
    ?>

    <?= $form->field($model, 'branch_code')->dropDownList($branchData, ['prompt' => 'Select branch *'])->label('Select Branch *'); ?>

    <div class="form-group">
        <div class="col-md-6">
            <input type="text" name="follow_up_date" class="form-control datepicker1" placeholder="Follow Up Date" required>
        </div>
        <div class="col-md-6">
            <input type="text" name="follow_up_time" class="form-control visit_time_class" placeholder="Follow Up Time" required>
            <ul class="time_suggestion" style="display: none"></ul>
        </div>
    </div>
    <br>
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

