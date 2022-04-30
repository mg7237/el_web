 
<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>
<style>
    .advisorinput1 label {
        float: left;
    }
    .datepicker {
        z-index: 100000;
    }
</style>

<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>

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

    var enforceModalFocusFn = $.fn.modal.Constructor.prototype.enforceFocus;

    $.fn.modal.Constructor.prototype.enforceFocus = function () {};

    $confModal.on('hidden', function () {
        $.fn.modal.Constructor.prototype.enforceFocus = enforceModalFocusFn;
    });

    $confModal.modal({backdrop: false});
</script>
<div class="modal-header">
    <button type="button" class="close closetext" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title modaltitletextnew">Set Up The Lease</h4>
</div>

<div class="modal-body" style="float:left; width:100%; background:#FFFFFF;">




    <div class="row" style="width: 80%; margin: auto;">

        <?php
        $form = ActiveForm::begin(['id' => 'ownerform', 'options' => [
                        'class' => 'forminputpopup',
                        'enctype' => 'multipart/form-data'
                    ],
                    'fieldConfig' => [
                        'template' => "{label}{input}{error}",
                        'options' => [
                            'tag' => 'false'
                        ]
                    ],
                    'enableAjaxValidation' => true]);
        ?>
        <div class="advisorinput1">

            <?= $form->field($model, 'tenant_id')->hiddenInput(['value' => Yii::$app->getRequest()->getQueryParam('applicant_id')])->label(false); ?>
            <?= $form->field($model, 'property_id')->hiddenInput(['value' => Yii::$app->getRequest()->getQueryParam('property_id')])->label(false); ?>
            <?= $form->field($model, 'parent_id')->hiddenInput(['value' => Yii::$app->getRequest()->getQueryParam('parent_id')])->label(false); ?>
            <?= $form->field($model, 'lease_start_date')->textInput(['maxlength' => true, 'class' => 'form-control datepicker', 'placeholder' => 'DD-MM-YYYY*']) ?>
            <?= $form->field($model, 'property_type')->hiddenInput(['value' => Yii::$app->userdata->getPropertyTypeId(Yii::$app->getRequest()->getQueryParam('parent_id'))])->label(false); ?>
        </div>
        <div class="advisorinput1">
            <?= $form->field($model, 'lease_end_date')->textInput(['maxlength' => true, 'class' => 'form-control datepicker', 'placeholder' => 'DD-MM-YYYY*']) ?>
        </div>

        <div class="advisorinput1">
            <?= $form->field($model, 'rent')->textInput(['maxlength' => true, 'class' => 'form-control isNumber', 'placeholder' => 'Rent']) ?>
        </div>

        <div class="advisorinput1">
            <?= $form->field($model, 'maintainance')->textInput(['maxlength' => true, 'class' => 'form-control isNumber', 'placeholder' => 'Maintainance']) ?>
        </div>

        <div class="advisorinput1">
            <?= $form->field($model, 'deposit')->textInput(['maxlength' => true, 'class' => 'form-control isNumber', 'placeholder' => 'Deposit']) ?>
        </div>

        <div class="advisorinput1">
            <?= $form->field($model, 'min_stay')->textInput(['maxlength' => true, 'class' => 'form-control isNumber', 'placeholder' => 'Minimum Stay']) ?>
        </div>

        <div class="advisorinput1">
            <?= $form->field($model, 'agreement_url')->fileInput() ?> 
        </div>

        <div class="advisorinput1">
            <?= $form->field($model, 'notice_period')->textInput(['maxlength' => true, 'class' => 'form-control isNumber', 'placeholder' => 'Notice Period']) ?>

            <?= $form->field($model, 'late_penalty_percent')->hiddenInput(['value' => Yii::$app->userdata->getPenalty()])->label(false); ?>
            <?= $form->field($model, 'min_penalty')->hiddenInput(['value' => Yii::$app->userdata->getMinPenalty()])->label(false); ?>
        </div>
        <p class="lineheightbg"></p>
        <div class="col-lg-6">
            <?= Html::submitButton('Activate lease', ['class' => 'btn btn savechanges_submit']) ?>
        </div>
        <div class="col-lg-5 pull-right"><button class="btn savechanges_submit cancel_confirm" type="button">Cancel </button></div>
        <?php ActiveForm::end(); ?>

    </div>


</div>

<div class="modal-footer">

</div>




