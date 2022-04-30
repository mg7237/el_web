<?php
$this->title = 'PMS- Visit';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- <link href="<?php //echo Url::home(true);       ?>css/jquery.datetimepicker.css" rel="stylesheet">
<script src="<?php //echo Url::home(true);       ?>js/jquery.datetimepicker.js"></script> -->
<?php if (Yii::$app->session->hasFlash('success')) { ?>
    <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
        <?= Yii::$app->session->getFlash('success'); ?>
    </div>   
<?php } ?>
<div id="msform">
    <!-- progressbar -->
    <ul id="progressbar">
        <li class="active">Bank Details</li>
        <li class="active">Make Payment</li>
        <li class="active">Schedule</li>
        <!--
                        <li>Confirmation</li>
                        <li>On Boarding</li>
        -->
    </ul>
    <fieldset>
        <div class="signupbody">
            <div class="personalinfomation">


                When would you like to visit this property?</div>

            <div class="parmentaddress">



                <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
                <?php $form->errorSummary($modelPropertyVisits); ?>
                <?= $form->field($modelPropertyVisits, 'applicant_id')->hiddenInput(['maxlength' => true, 'value' => Yii::$app->userdata->id])->label(false) ?>
                <div class="col-md-6 col-sm-12 col-lg-6">
                    <?= $form->field($modelPropertyVisits, 'visit_date')->textInput(['maxlength' => true, 'id' => 'visit_date', 'placeholder' => 'Date', 'value' => $visitdate])->label(false) ?>
                </div>
                <div class="col-md-6 col-sm-12 col-lg-6">
                    <?= $form->field($modelPropertyVisits, 'visit_time')->textInput(['maxlength' => true, 'placeholder' => 'Time', 'class' => 'form-control datepicker visit_time_class', 'value' => $visittime])->label(false) ?>
                </div>
                <ul class='time_suggestion' style='display: none'>

                </ul>

                <?= Html::Button('Submit', ['class' => 'btn savechanges_submit', 'id' => 'visitsubmit']) ?>

                <?php ActiveForm::end(); ?>






            </div>





    </fieldset> 

</div>

<script type="text/javascript" src="../js/moment.js"></script>	
<script type="text/javascript" src="../js/timepicker.js"></script>
<link rel="stylesheet" href="../css/timepicker.css" />

<script type="text/javascript">
    $(document).on('click', '#visitsubmit', function (e) {

        var full = $('#visit_date').val() + ' ' + $('#propertyvisits-visit_time').val() + ':00';
        var d = new Date(full);
        var nowa = new Date();
        if (d <= nowa) {
            alert('The schedule time will be after the current time');
        } else {
            $('#w0').submit();
        }
    });
    $(document).on('change', '#visit_date', function () {
        var time = $('#propertyvisits-visit_time').val();
        // alert($(this).val()+' '+time);
    });
    $(document).on('focusout', '#propertyvisits-visit_time', function () {
        var date = $('#visit_date').val();
        // alert(date+' '+$(this).val());
    });

    $(".datepicker").datetimepicker({
        useCurrent: true,
        format: 'HH:mm',
        sideBySide: true,
    });

    $(document).on('click', '.close_time_picker', function () {
        $('.time_suggestion').html();
        $('.time_suggestion').hide();
        $('.field-propertyvisits-visit_time .help-block').show();
        $('.close_time_picker').remove();
    });
    $(document).on('click', '.apply_time', function () {
        $('.visit_time_class').val($(this).text());
        $('.time_suggestion').html();
        $('.time_suggestion').hide();
        $('.field-propertyvisits-visit_time .help-block').show();
        $('.close_time_picker').remove();

    });

    $(document).ready(function () {
        $('.visit_time_class').on('focus', function () {
            var date = $('#visit_date').val();
            var date1 = new Date(date);
            //alert(date1);
            var today = new Date();
            //alert(today);
            if (date1 > today)
            {
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
            } else {
                //alert(today);
                //var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
                var hour = today.getHours();
                var min = today.getMinutes();
                var list = '';
                var hh = '';
                var mm = '';
                for (var i = hour; i <= 21; i++) {
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

                            if (hh > hour || mm > min)
                            {
                                list = list + '<li class="apply_time">' + hh + ':' + mm + '</li>';
                            }
                        }
                    }
                }



                // for(var j=0;j<=60){
                // 	console.log(j);
                // 	j=j+5;

                // }
            }
            $('.time_suggestion').html(list);
            $('.close_time_picker').remove();
            $('.time_suggestion').show();
            $('.field-propertyvisits-visit_time .help-block').hide();
            $('.field-propertyvisits-visit_time').append('<span class="close_time_picker">X</span>')
        });
    });

    $(document).click(function (e) {
        console.log(e.target);
        if (!$('#propertyvisits-visit_time').is(e.target) || !$('#propertyvisits-visit_time').has(e.target)) { // check if the click is inside a div or outside
            // if it is outside then hide all of them
            $('.time_suggestion').hide();
        }
    });
    $("#propertyvisits-visit_time").keydown(false);
</script> 


<style>
    .bootstrap-timepicker-widget.dropdown-menu.open {
        display: inline-block;
        z-index: 99999 !important;
    }
    ul.time_suggestion {
        height: 90px;
        //position: absolute;
        background-color: white;
        overflow-y: scroll;
        width: 50%;
        // margin-top: -14px;
        /* display: inline-flex; */
        padding-left: 5px;
        z-index: 999999;
        position:relative;
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
    .form-group.field-visit_date.required.has-success input {
        width: 98%;
    }
    input#propertyvisits-visit_time {
        width: 100%;
    }
    .bootstrap-timepicker-widget{
        z-index:0;
        height:50%;
    }

</style>