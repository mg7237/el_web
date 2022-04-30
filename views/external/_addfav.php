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
    <br>
</div>

<div class="modal-body modaltextpargh" style="float:left; width:600px; margin-left: -20px; background:#FFFFFF;">

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
        <input id="tagsprop" autocomplete="off" type="text" class="form-control" placeholder="Search property" class="form-control">
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
                var today = new Date();
                var day = today.getDate() + 4;
                var month = today.getMonth() + 1;
                var year = today.getYear() - 100;

                $(".datepicker").datepicker({
                    minDate: new Date(),
                    maxDate: '+4D',
                    changeMonth: true,
                    changeYear: true,
                    numberOfMonths: 1,
                    dateFormat: 'dd-M-yy',

                });
            });

        </script>
        <?= $form->field($model, 'property_id')->hiddenInput()->label(false); ?>
        <?= $form->field($model, 'applicant_id')->hiddenInput(['value' => Yii::$app->userdata->getUserEmailById($_GET['applicant_id'])])->label(false); ?>
    </div>

    <div class="parmentaddress resetbox">
        <?= $form->field($model, 'status')->dropDownList([1 => 'Favourite', 3 => 'Scheduled'], ['prompt' => 'Select Status', ' style' => 'width:67%']); ?>
    </div>
    <div class="parmentaddress resetbox">
        <?= $form->field($model, 'visit_date')->textInput(['maxlength' => true, 'class' => 'form-control datepicker', 'placeholder' => 'DD-MM-YYYY*', 'autocomplete' => 'off']) ?>

    </div>
    <div class="parmentaddress resetbox">
        <?php //echo $form->field($model, 'visit_time')->textInput(['maxlength' => true, 'class' => 'form-control', 'id' => 'datetimepicker4', 'placeholder' => 'HH:MM']) ?>
        <label class="control-label" for="favouriteproperties-visit_time">Visit Time</label>
        <select style="width: 67%" aria-required="true" name="FavouriteProperties[visit_time]" id="propertyvisits-visit_time" class="form-control visit_time_class timepicker">
            <option value=""> At </option>
            <option value="10:00"> 10:00 - 10:30</option>
            <option value="10:30"> 10:30 - 11:00</option>
            <option value="11:00"> 11:00 - 11:30</option>
            <option value="11:30"> 11:30 - 12:00</option>
            <option value="12:00"> 12:00 - 12:30</option>
            <option value="12:30"> 12:30 - 13:00</option>
            <option value="13:00"> 13:00 - 13:30</option>
            <option value="13:30"> 13:30 - 14:00</option>
            <option value="14:00"> 14:00 - 14:30</option>
            <option value="14:30"> 14:30 - 15:00</option>
            <option value="15:00"> 15:00 - 15:30</option>
            <option value="15:30"> 15:30 - 16:00</option>
            <option value="16:00"> 16:00 - 16:30</option>
            <option value="16:30"> 16:30 - 17:00</option>
            <option value="17:00"> 17:00 - 17:30</option>
            <option value="17:30"> 17:30 - 18:00</option>
            <option value="18:00"> 18:00 - 18:30</option>
            <option value="18:30"> 18:30 - 19:00</option>
            <option value="19:00"> 19:00 - 19:30</option>
            <option value="19:30"> 19:30 - 20:00</option>
            <option value="20:00"> 20:00 - 20:30</option>
            <option value="20:30"> 20:30 - 21:00</option>
            <option value="21:00"> 21:00 - 21:30</option>
        </select>
        <div class="help-block visit-time-help-block" style="display: none;">Visit time cannot be blank.</div>
        <ul class='time_suggestion' style='display: none'>

        </ul>
    </div>

    <div class="modal-footer">
        <div class="col-md-6">
            <?= Html::submitButton('Save', ['class' => 'btn savechanges_submit_contractaction ']) ?>
        </div>
        <div class= "col-md-6">
            <button data-dismiss="modal" class="btn savechanges_submit cancel_confirm" type="button">Cancel</button>
        </div>

    </div>


    <?php ActiveForm::end(); ?>
</div>

</div>


<script type="text/javascript">
// $(document).on('click','.close_time_picker',function(){
// 			$('.time_suggestion').html();
// 	    	$('.time_suggestion').hide();
// 	    	$('.field-propertyvisits-visit_time .help-block').show();
// 	    	$('.close_time_picker').remove();
//   	});
//   	$(document).on('click','.apply_time',function(){
//   			$('.visit_time_class').val($(this).text());
// 			$('.time_suggestion').html();
// 	    	$('.time_suggestion').hide();
// 	    	$('.field-propertyvisits-visit_time .help-block').show();
// 	    	$('.close_time_picker').remove();

// 	});

    $(document).ready(function () {
        $(function () {
            $('#datetimepicker4').datetimepicker({
                format: 'HH:mm'
            });
        });

        $('#propertyvisits-visit_time').change(function () {
            if ($(this).val() == '') {
                $('.visit-time-help-block').css('display', 'block');
            } else {
                $('.visit-time-help-block').css('display', 'none');
            }
        });

        $('#favouriteproperties-status').change(function () {
            if ($(this).val() == 1) {
                $('#favouriteproperties-visit_date').attr('disabled', 'disabled');
                $('#propertyvisits-visit_time').attr('disabled', 'disabled');
            } else {
                $('#favouriteproperties-visit_date').removeAttr('disabled');
                $('#propertyvisits-visit_time').removeAttr('disabled');
            }
        });

        $('.savechanges_submit_contractaction').click(function (e) {
            if ($('#favouriteproperties-status').val() == 3) {
                if ($('#propertyvisits-visit_time').val() == '') {
                    e.preventDefault();
                    $('.visit-time-help-block').css('display', 'block');
                } else {
                    $('.visit-time-help-block').css('display', 'none');
                }

                var full = $('#favouriteproperties-visit_date').val() + ' ' + $('#propertyvisits-visit_time').val() + ':00';
                var d = new Date(full);
                var nowa = new Date();
                nowa = new Date(nowa.setHours(nowa.getHours() + 4));
                if (d <= nowa) {
                    e.preventDefault();
                    alert('The schedule date/time should be 4 hours later than current date/time');
                }
            }
        });
    });

    // $(document).click(function(e){
    // 	console.log(e.target);
    //        if(!$('#propertyvisits-visit_time').is(e.target) || !$('#propertyvisits-visit_time').has(e.target)){ // check if the click is inside a div or outside
    //            // if it is outside then hide all of them
    //            $('.time_suggestion').hide();
    //        }
    //    });

</script> 

<style>
    .bootstrap-timepicker-widget.dropdown-menu.open {
        display: inline-block;
        z-index: 99999 !important;
    }
    ul.time_suggestion {
        height: 119px;
        //position: absolute;
        background-color: white;
        overflow-y: scroll;
        width: 50%;
        // margin-top: -14px;
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
        /* padding: 5px; */
        border-radius: 16px;
        width: 20px;
        height: 20px;
        color: white;
        cursor: pointer;
        margin-top: -31px;
        text-align: center;
    }
    .field-propertyvisits-visit_time{
        position: relative;
    }

</style>