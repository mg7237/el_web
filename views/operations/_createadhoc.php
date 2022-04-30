 
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
    Add Adhoc Request
    <button type="button" class="close" data-dismiss="modal">&times;</button>

</div>
<div class="modal-body">		
    <?php
    $form = ActiveForm::begin(['id' => 'adhocform', 'options' => [
                    'class' => 'forminputpopup'
                ], 'enableAjaxValidation' => true]);
    ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'placeholder' => 'Adhoc Title*', 'class' => 'form-control'])->label(false); ?>
    <div class="form-group">

        <input type="text" id="tenant_name" value="" class="form-control isCharacter" placeholder="Tenant Name*">
        <ul class="tennat_list" style="display: none;">

        </ul>
        <?= $form->field($model, 'tenant_id')->hiddenInput()->label(false); ?>

    </div>
    <?= $form->field($model, 'actual_charge')->textInput(['maxlength' => true, 'placeholder' => 'Actual Charge*', 'class' => 'form-control isNumber'])->label(false); ?>

    <?= $form->field($model, 'charge_to_tenant')->textInput(['maxlength' => true, 'placeholder' => 'Charge To Tenant*', 'class' => 'form-control isNumber'])->label(false); ?>
    <?= $form->field($model, 'payment_due_date')->textInput(['maxlength' => true, 'placeholder' => 'Payment Due Date*', 'class' => 'form-control datepicker'])->label(false); ?>

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
    ul.tennat_list {
        list-style: none;
        padding-left: 5px;
        max-height: 100px;
        overflow-y: auto;
        position: absolute;
        width: 92%;
        background-color: white;
        cursor: pointer;
    }
    ul.tennat_list li {
        border-bottom: 1px solid grey;       
    }
    ul.tennat_list li:hover {
        background: grey;
        color: white;
    }
</style>
<script type="text/javascript">
    $(document).on('click', '.tennat_list li', function () {
        $('#adhocrequests-tenant_id').val($(this).attr('data-id'));
        $('#tenant_name').val($(this).text());
        $('.tennat_list').html('');
        $('.tennat_list').hide();
    });

    $(document).on('keyup', '#tenant_name', function () {
        var element = $(this);
        // var str=$(this).val()
        if (element.val().length != 0) {
            $.ajax({
                type: "POST",
                dataType: 'json',
                url: 'getalltenants',
                data: 'str=' + element.val(), //only input
                success: function (response) {
                    var list = '';
                    $(response).each(function (key, value) {
                        list = list + '<li data-id="' + value.id + '">' + value.name + '</li>';
                    })
                    $('.tennat_list').show();
                    $('.tennat_list').html(list);
                }
            });
        }
    })
    $(document).mouseup(function (e)
    {

        var container = $('.tennat_list');
        // if the target of the click isn't the container nor a descendant of the container
        if (!container.is(e.target) && container.has(e.target).length === 0)
        {
            container.hide();
            container.html('');
        }
    });
</script>