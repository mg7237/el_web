<?php
$this->title = 'PMS- Personal Information';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="//maps.googleapis.com/maps/api/js?key=AIzaSyDCFkcmtCqHONWxjbZn3bftmAqQ0A2YSKw&amp;libraries=places" type="text/javascript"></script>

<script type="text/javascript">
    function initialize() {
        //	alert('hello');
        var input = document.getElementById('searchTextField');
        var autocomplete = new google.maps.places.Autocomplete(input);
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var place = autocomplete.getPlace();
            /*  document.getElementById('stepinfo-lat').value = place.geometry.location.lat();
             document.getElementById('stepinfo-lon').value = place.geometry.location.lng();*/
        });
    }
    google.maps.event.addDomListener(window, 'load', initialize);


</script>


<style>
    h4#myModalLabel {
        margin-top: 18px;
        font-size: 19px;
    }
    .navbar {
        margin-top: 0px !important;
    }
    #progressbar {
        margin-bottom: 15px;
        overflow: hidden;
        counter-reset: step;
        margin-top: 15px;
    }
</style>
<div id="msform">
    <!-- progressbar -->
    <ul id="progressbar">
        <li class="active">Schedule Visit</li>
        <li>Make Payment</li>
        <li>Schedule </li>
        <!--<li>Confirmation </li>-->
        <!--<li>On Boarding </li>-->
    </ul>

    <?php if (Yii::$app->session->hasFlash('success')) { ?>
        <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
            <?= Yii::$app->session->getFlash('success'); ?>
        </div>   
    <?php } ?>

    <!-- Form starts here -->
    <?php //$form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <?php //echo $form->errorSummary($model); ?>

    <?php $form = ActiveForm::begin(['id' => 'visits', 'action' => '']); ?>

    <div class="personalinfomation"><b>Schedule Visit as per your convenience</b> <span style="font-size:11px;text-transform: none;"></span></div>
    <div class="parmentaddress">
        <input type="hidden" name="property_id" value="<?= (!empty($_GET['property_id'])) ? $_GET['property_id'] : $_GET['parent_property_id']; ?>" />

        <div class="col-md-6 col-sm-12">
            <?= $form->field($modelvisit, 'visit_date')->textInput(['maxlength' => true, 'placeholder' => 'Visit Date', 'id' => 'visit_date'])->label(false); ?>
        </div>
        <div class="col-md-6 col-sm-12">
            <?= $form->field($modelvisit, 'visit_time')->textInput(['maxlength' => true, 'class' => 'form-control visit_time_class', 'placeholder' => 'Visit Time'])->label(false); ?>

            <ul class='time_suggestion' style='display: none'>

            </ul>
        </div>

        <div class="col-md-12 col-sm-12">
            <p>Would you like to reserve or book this property on hold if you like it.</p>
            <label class="radio-inline"><input onclick="setUnsetBookProp(this);" type="radio" name="sch_book" value="1" checked="checked" /> Yes</label>
            <label class="radio-inline"><input onclick="setUnsetBookProp(this);" type="radio" name="sch_book" value="0" /> No</label>
            <br />
            <br />
        </div>

        <div class="col-sm-12 col-md-12">
            <?= Html::submitButton('Schedule and Book', ['id' => 'visitsubmit', 'class' => 'btn savechanges_submit']) ?>
        </div>



        <?php //echo $form->field($model, 'account_holder_name')->textInput(['maxlength' => true, 'placeholder' => 'Account Holder Name*', 'class' => 'form-control '])->label('Account Holder Name*') ?>

        <?php //echo $form->field($model, 'bank_name')->textInput(['maxlength' => true, 'placeholder' => 'Bank Name*', 'class' => 'form-control '])->label('Bank Name*') ?>

        <?php //echo $form->field($model, 'bank_branchname')->textInput(['maxlength' => true, 'placeholder' => 'Branch name*'])->label('Branch Name*') ?>
        <?php //echo $form->field($model, 'bank_ifcs')->textInput(['maxlength' => true, 'placeholder' => 'IFSC Code*', 'id' => 'booking_ifsc'])->label('IFSC Code*') ?>
        <div class="help-block" id="ifsc_error" style="color: #a94442;"></div>
        <?php if (Yii::$app->userdata->getusertype() == 3) { ?>
            <?php //echo $form->field($model, 'account_number')->textInput(['maxlength' => true, 'placeholder' => 'Account Number*'])->label('Account Number*') ?>
        <?php } else { ?>
            <?php //echo $form->field($model, 'bank_account_number')->textInput(['maxlength' => true, 'placeholder' => 'Account Number*'])->label('Account Number*') ?>
        <?php } ?>
        <?php //echo Html::submitButton('Next', ['class' => 'btn savechanges_submit', 'id' => 'booking_next']) ?>
    </div>

    <?php //ActiveForm::end(); ?>
    <?php ActiveForm::end(); ?>
    <!-- Form ends here -->


    <!-- Scheduled Form starts here -->


    <!-- Scheduled Form Ends here -->
</div>

<script type="text/javascript">
    $(function () {
        $('#visit_date').datepicker({
            format: 'HH:mm'
        });
        var d1 = new Date();
        var d2 = new Date(d1);
        d2.setHours(d1.getHours() + 4);
        $('#propertyvisits-visit_time').timepicker({
            minTime: d2,
            maxTime: '09:30 PM',
            format: 'HH:mm'
        });
    });

    function setUnsetBookProp(ele) {
        var val = $(ele).val();
        console.log(val);
        if (val == 0) {
            $('#visitsubmit').text('Schedule');
        } else {
            $('#visitsubmit').text('Schedule and Book');
        }
    }

    $("#booking_next").click(function (e) {
        e.preventDefault();
        var ifsc = document.getElementById('booking_ifsc').value;
        var url = "checkifsc";

        $.ajax({
            type: "POST",
            url: url,
            data: {ifsc: ifsc},
            //dataType: "json",
            //processData: false,
            success: function (data) {

                if (data == 'no')
                {
                    document.getElementById('w0').submit();
                } else {
                    document.getElementById("booking_ifsc").focus();
                }
            },
        });

    });

    $(document).ready(function () {

        removeCookie('redirect');
        $('input[data-type="Number"]').keypress(function (e) {

            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
            ;
        })
    })
    $('.alphaonly').bind('keypress keyup blur', function () {
        var node = $(this);
        node.val(node.val().replace(/[^a-z]/g, ''));
    }
    );


</script>