 
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
    <h4 class="modal-title modaltitletextnew">New Property</h4>
</div>

<div class="modal-body modaltextpargh" style="float:left; width:100%; background:#FFFFFF;">

    <?php
    $form = ActiveForm::begin(['id' => 'addfav', 'options' => [
                    'class' => 'form-inline textsize'
                ],
                'fieldConfig' => [
                    'template' => "{label}{input}{error}",
                    'options' => [
                        'tag' => 'false'
                    ]
                ],
                'enableAjaxValidation' => true]);
    ?>

    <div class="parmentaddress resetbox">
        <label for="exampleInputEmail1">Property name</label>
        <input  id="tagsprop" type="text" class="form-control" placeholder="Search Property" class="form-control">
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
        <script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
        <style>
            ul.ui-autocomplete {
                z-index: 1100;
            }
        </style>

        <script>

            $(function () {
                $("#tagsprop").autocomplete({
                    source: "getproperty?applicant_id=<?php echo Yii::$app->getRequest()->getQueryParam('applicant_id'); ?>",
                    select: function (event, ui) {
                        event.preventDefault();
                        //	console.log(ui.item);
                        $("#tagsprop").val(ui.item.label);
                        $("#favouriteproperties-property_id").val(ui.item.value);
                    },
                });



            });
            $(function () {
                $(".datepicker").datepicker({
                    minDate: 0,
                    changeMonth: true,
                    changeYear: true,
                    // yearRange: "-0:+1",
                    minDate: '0',
                    numberOfMonths: 1,
                    dateFormat: 'dd-M-yy',

                });
            });

        </script>
        <?= $form->field($model, 'property_id')->hiddenInput()->label(false); ?>
        <?= $form->field($model, 'applicant_id')->hiddenInput(['value' => base64_decode(Yii::$app->getRequest()->getQueryParam('applicant_id'))])->label(false); ?>
    </div>

    <div class="parmentaddress resetbox">
        <?= $form->field($model, 'status')->dropDownList([1 => 'Favoirate', 2 => 'Booked', 3 => 'Scheduled'], ['prompt' => 'Select Status']); ?>
    </div>
    <div class="parmentaddress resetbox">
        <?= $form->field($model, 'visit_date')->textInput(['maxlength' => true, 'class' => 'form-control datepicker', 'placeholder' => 'DD-MMM-YYYY*']) ?>

    </div>
    <div class="parmentaddress resetbox">
        <?= $form->field($model, 'visit_time')->textInput(['maxlength' => true, 'class' => 'form-control', 'placeholder' => 'HH:MM']) ?>

    </div>
    <div class="modal-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>

    </div>


    <?php ActiveForm::end(); ?>
</div>

</div>
