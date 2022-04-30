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
</style>
<script type="text/javascript">
    $(function () {
        $(".datepicker").datepicker({
            minDate: 0,
            changeMonth: true,
            changeYear: true,
            // yearRange: "-0:+1",
            minDate: '0',
            numberOfMonths: 1,
            dateFormat: 'yy-mm-dd',

        });
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
    <?= $form->field($model, 'id')->hiddenInput(['maxlength' => true, 'placeholder' => 'Rent*', 'class' => 'form-control isNumber'])->label(false); ?>        
    <?= $form->field($model, 'rent')->textInput(['maxlength' => true, 'placeholder' => 'Rent*', 'class' => 'form-control isNumber']); ?>
    <?= $form->field($model, 'deposit')->textInput(['maxlength' => true, 'placeholder' => 'Deposite*', 'class' => 'form-control isNumber'])->label('Deposite'); ?>
    <?= $form->field($model, 'token_amount')->textInput(['maxlength' => true, 'placeholder' => 'Token Amount*', 'class' => 'form-control isNumber']); ?>
    <?= $form->field($model, 'maintenance')->textInput(['maxlength' => true, 'placeholder' => 'Maintenance*', 'class' => 'form-control isNumber']); ?>
    <?= $form->field($model, 'availability_from')->textInput(['maxlength' => true, 'placeholder' => 'Availability From*', 'class' => 'form-control datepicker']); ?>
    <?php if ($type == '3') { ?>
        <?= $form->field($model, 'status')->dropDownList([0 => 'Disabled', 1 => 'Enable'], ['prompt' => 'Select region'])->label(false); ?>
        <?php
    } else {
        ?>
        <?= $form->field($modelListing, 'status')->dropDownList([0 => 'Disabled', 1 => 'Enable'], ['prompt' => 'Select Status']); ?>
        <?php
    }
    ?>
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