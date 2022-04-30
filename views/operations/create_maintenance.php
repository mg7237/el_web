 
<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
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
        $(".datepicker").datepicker({
            minDate: new Date(),
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: 'yy-mm-dd',

        });
    });

</script>
<div class="modal-header">
    Add Adhoc Request
    <button type="button" class="close" data-dismiss="modal">&times;</button>

</div>
<div class="modal-body">		
    <?php
    $form = ActiveForm::begin(['id' => 'adhocform', 'options' => [
                    'class' => 'forminputpopup',
                    'enctype' => 'multipart/form-data',
                ], 'enableAjaxValidation' => true]);
    ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'placeholder' => 'Title*', 'class' => 'form-control'])->label(false); ?>
    <?= $form->field($model, 'description')->textArea(['maxlength' => true, 'placeholder' => 'Description', 'class' => 'form-control'])->label(false); ?>
    <div class="form-group">

        <input type="text" id="property_name" value="" class="form-control isCharacter" placeholder="Property Name*">
        <ul class="property_list" style="display: none;">

        </ul>
        <?= $form->field($model, 'property_id')->hiddenInput()->label(false); ?>

    </div>
    <?= $form->field($model, 'charges')->textInput(['maxlength' => true, 'placeholder' => 'Actual Charge*', 'class' => 'form-control isNumber'])->label(false); ?>
    <?= $form->field($model, 'charges_to_owner')->textInput(['maxlength' => true, 'placeholder' => 'Charge To Owner*', 'class' => 'form-control isNumber'])->label(false); ?>
    <?= $form->field($model, 'payment_due_date')->textInput(['maxlength' => true, 'placeholder' => 'Payment Due Date*', 'class' => 'form-control datepicker'])->label(false); ?>
    <div class="form-group">
        <label for="exampleInputPassword1">Status</label>
        <?php
        //$states = \app\models\PropertyTypes::find()->all();
        $stateData = ArrayHelper::map($request, 'id', 'name');
        ?>
        <?= $form->field($model, 'status')->dropDownList($stateData, ['prompt' => 'Select Status'])->label(false); ?>

    </div>

    <input type="file" name="attachment">
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
<style>
    ul.property_list {
        list-style: none;
        padding-left: 5px;
        max-height: 100px;
        overflow-y: auto;
        position: absolute;
        width: 92%;
        background-color: white;
        cursor: pointer;
    }
    ul.property_list li {
        border-bottom: 1px solid grey;       
    }
    ul.property_list li:hover {
        background: grey;
        color: white;
    }
</style>
<script type="text/javascript">
    $(document).on('click', '.property_list li', function () {
        $('#maintenancerequest-property_id').val($(this).attr('data-id'));
        $('#property_name').val($(this).text());
        $('.property_list').html('');
        $('.property_list').hide();
    });

    $(document).on('keyup', '#property_name', function () {
        var element = $(this);
        if (element.val().length != 0) {
            $.ajax({
                type: "POST",
                dataType: 'json',
                url: 'getallproperties',
                data: 'str=' + element.val(), //only input
                success: function (response) {
                    var list = '';
                    $(response).each(function (key, value) {
                        list = list + '<li data-id="' + value.id + '">' + value.name + '</li>';
                    })
                    $('.property_list').show();
                    $('.property_list').html(list);
                }
            });
        }
    })
    $(document).mouseup(function (e)
    {

        var container = $('.property_list');
        // if the target of the click isn't the container nor a descendant of the container
        if (!container.is(e.target) && container.has(e.target).length === 0)
        {
            container.hide();
            container.html('');
        }
    });
</script>