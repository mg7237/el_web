 
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
    .help-block {
        margin-left: 31% !important;
    }
    .pull-right a.btn.savechanges_submit {
        background: #642493 !important;
        border: 1px solid #642493 !important;
    }
    .pull-right a.btn.savechanges_submit:hover {
        background: #642493 !important;
        border: 1px solid #642493 !important;
    }
    .savechanges_submit{
        background: #347c17 !important;
        border: 1px solid #347c17 !important;
    }
    .savechanges_submit:hover{
        background: #347c17 !important;
        border: 1px solid #347c17 !important;
    }
</style>

<script>

    $(function () {

        $("#tenantagreements-lease_start_date").datepicker({
            // minDate: new Date(),
            numberOfMonths: 1,
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-M-yy',
            onSelect: function (selected) {
                $("#tenantagreements-lease_end_date").datepicker("option", "minDate", selected)
            }
        });

        $("#tenantagreements-lease_end_date").datepicker({
            //minDate: new Date(),
            numberOfMonths: 1,
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-M-yy',
            onSelect: function (selected) {
                $("#tenantagreements-lease_start_date").datepicker("option", "maxDate", selected)
            }
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
        $form = ActiveForm::begin(['id' => 'ownerform2', 'options' => [
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
            <label class="control-label" for="">For Bed / Room</label>
            <label class="radio-inline"><input id="select-room-for" type="radio" name="for_bed_room" value="1">Room</label>
            <label class="radio-inline"><input id="select-bed-for" checked="checked" type="radio" name="for_bed_room" value="2">Bed</label>
            <br />
        </div>

        <div class="advisorinput1">
            <label class="control-label" for="">Select Room</label>
            <select onchange="getAvailableBeds(<?= Yii::$app->getRequest()->getQueryParam('parent_id') ?>, this.value)" name="rooms_id" id="rooms_id" class="form-control" aria-required="true" style="width: 60%;">
                <option value="">Select Room</option>
                <?php
                $array = Array();
                $beds = Array();
                foreach ($listing as $key => $value) {
                    if (!in_array($value['parent_id'], $array)) {
                        $array[] = $value['parent_id'];
                    }
                    $beds[$value['parent_id']][] = $value['child_id'];
                    if (Yii::$app->userdata->getChildPropertyTypeWithActiveStatus2($value['child_id']) == 1) {
                        ?>
                        <option value="<?= $value['parent_id'] ?>"><?= Yii::$app->userdata->getRoomName($value['parent_id']) ?></option>
                        <?php
                    }
                }
                ?>
            </select>
        </div>

        <div class="advisorinput1 hidden" id="beds-collection">

        </div>
        <div class="advisorinput1">
            <?php //echo "<pre>";print_r($model);echo "</pre>";?>
            <?= $form->field($model, 'tenant_id')->hiddenInput(['value' => Yii::$app->getRequest()->getQueryParam('applicant_id')])->label(false); ?>
            <?= $form->field($model, 'property_id')->hiddenInput(['value' => Yii::$app->getRequest()->getQueryParam('property_id')])->label(false); ?>
            <?= $form->field($model, 'parent_id')->hiddenInput(['value' => Yii::$app->getRequest()->getQueryParam('parent_id')])->label(false); ?>
            <?= $form->field($model, 'property_type')->hiddenInput(['value' => Yii::$app->userdata->getPropertyTypeId(Yii::$app->getRequest()->getQueryParam('parent_id'))])->label(false); ?>
            <?= $form->field($model, 'lease_start_date')->textInput(['maxlength' => true, 'class' => 'form-control datepicker', 'placeholder' => 'DD-MM-YYYY', 'autocomplete' => 'off']) ?>
        </div>
        <div class="advisorinput1">
            <?= $form->field($model, 'lease_end_date')->textInput(['maxlength' => true, 'class' => 'form-control datepicker', 'placeholder' => 'DD-MM-YYYY', 'autocomplete' => 'off']) ?>
        </div>

        <div class="advisorinput1">
            <?= $form->field($model, 'rent')->textInput(['maxlength' => true, 'class' => 'form-control isNumber', 'placeholder' => 'Rent']) ?>
        </div>

        <div class="advisorinput1">
            <?= $form->field($model, 'maintainance')->textInput(['maxlength' => true, 'class' => 'form-control isNumber', 'placeholder' => 'Maintenance'])->label('Maintenance') ?>
        </div>

        <div class="advisorinput1">
            <?= $form->field($model, 'deposit')->textInput(['maxlength' => true, 'class' => 'form-control isNumber', 'placeholder' => 'Deposit']) ?>
        </div>

        <div class="advisorinput1">
            <?= $form->field($model, 'late_penalty_percent')->textInput(['maxlength' => true, 'class' => 'form-control isNumber', 'placeholder' => 'Late Fee Penalty %'])->label('Late Fee Penalty %') ?>
        </div>

        <div class="advisorinput1">
            <?= $form->field($model, 'min_penalty')->textInput(['maxlength' => true, 'class' => 'form-control isNumber', 'placeholder' => 'Late Fee Minimum Penalty Amount'])->label('Late Fee Minimum Penalty Amount') ?>
        </div>

        <div class="advisorinput1">
            <?= $form->field($model, 'min_stay')->textInput(['onkeyup' => 'this.value=this.value.replace(/[^0-9]/g,"");', 'maxlength' => true, 'class' => 'form-control isNumber', 'placeholder' => 'Minimum Stay'])->label('Minimum Stay (Months)') ?>
        </div>
        <input type="hidden" name="" value="12"/>
        <input type="hidden" name="" value=""/>
        <input type="hidden" name="" value="12:12:2017 00:00:00"/>
        <input type="hidden" name="" value="12"/>
        <input type="hidden" name="" value="12:12:2017 00:00:00"/>
        <?= $form->field($model, 'created_by')->hiddenInput(['value' => 12])->label(false); ?>
        <?= $form->field($model, 'created_date')->hiddenInput(['value' => "12:12:2017 00:00:00"])->label(false); ?>
        <?= $form->field($model, 'end_date')->hiddenInput(['value' => "12:12:2017 00:00:00"])->label(false); ?>
        <?= $form->field($model, 'updated_by')->hiddenInput(['value' => 12])->label(false); ?>
        <?= $form->field($model, 'updated_date')->hiddenInput(['value' => "12:12:2017 00:00:00"])->label(false); ?>
        <div class="advisorinput1">
            <?= $form->field($model, 'notice_period')->textInput(['onkeyup' => 'this.value=this.value.replace(/[^0-9]/g,"");', 'maxlength' => true, 'class' => 'form-control isNumber', 'placeholder' => 'Notice Period'])->label('Notice Period (Months)') ?>

            <?php //echo $form->field($model, 'late_penalty_percent')->hiddenInput(['value' => Yii::$app->userdata->getPenalty()])->label(false);  ?>
            <?php //echo $form->field($model, 'min_penalty')->hiddenInput(['value' => Yii::$app->userdata->getMinPenalty()])->label(false);  ?>
        </div>
        <div class="advisorinput1">
            <?= $form->field($model, 'token_amount')->textInput(['onkeyup' => 'this.value=this.value.replace(/[^0-9]/g,"");', 'maxlength' => true, 'class' => 'form-control isNumber', 'placeholder' => 'Token Amount'])->label() ?>
        </div>
        <div class="advisorinput1">
            <?= $form->field($model, 'agreement_url')->fileInput() ?> 
        </div>
        <p class="lineheightbg"></p>
        <div class="col-lg-6">
            <?= Html::submitButton('Activate lease', ['class' => 'btn btn savechanges_submit']) ?>
        </div>
        <div class="col-lg-5 pull-right"><a style="color:#FFF" class="btn savechanges_submit cancel_confirm_1" href="<?= Url::home(true); ?>sales/applicants" type="button">Cancel</a></div>
        <?php ActiveForm::end(); ?>

    </div>


</div>

<div class="modal-footer">

</div>
