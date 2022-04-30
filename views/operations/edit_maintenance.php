
<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
?>
<div id="page-wrapper">
    <div class="row">
        <?php
        $form = ActiveForm::begin(['id' => 'adhocform', 'options' => [
                        'class' => 'forminputpopup',
                        'enctype' => 'multipart/form-data',
                    ], 'enableAjaxValidation' => true]);
        ?>
        <div class="col-lg-8" style="margin: 0 auto;">

            <div class="parmentaddressnew">
                <div class="form-inline">
                    <div class="form-group">
                        <label for="">Owner ID</label>
                        <?= Yii::$app->userdata->getOwnerIdByProperty($model->property_id) ?>
                    </div>
                    <div class="form-group">
                        <label for="">Owner Name </label>
                        <?= Yii::$app->userdata->getUserNameByProperty($model->property_id) ?>
                    </div>
                    <div class="form-group">
                        <label for="">Property details</label>
                        <?= Yii::$app->userdata->getPropertyNameById($model->property_id) ?>        
                    </div>
                    <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'placeholder' => 'Title*', 'class' => 'form-control'])->label('Request Title'); ?>
                </div>
            </div>
            <div class="form-group" style="margin: 5px 0;">
                <div class="row">
                    <div class="col-md-1 note_c"><p>Notes</p></div>
                    <div class="col-md-11 comment_c">
                        <!-- <a href="#" data-toggle="modal" data-target="#myModal_comment">Add Comments</a> -->
                        <div class="form-group">
                            <div class="col-md-10 col-sm-8">
                                <textarea class="form-control" rows="1" id="comment"></textarea>
                            </div>
                            <div class="col-md-2 col-sm-2" style="padding: 0px 10px;">
                                <button type="button" class="btn btn-default replybut" style="margin: 0px 0px;">Add Comment</button></div>
                        </div>
                    </div>
                </div>
                <div class="notexbox">

                    <div class="lorembox">
                        <?php
                        if (count($Comments) != 0) {

                            foreach ($Comments as $key => $value) {
                                ?>
                                <p><?= $value->message; ?><span>-<?= Yii::$app->userdata->getFullNameById($value->user_id) ?> <?= Date('d/m/Y', strtotime($value->created_datetime)) ?> at <?= Date('h:i A', strtotime($value->created_datetime)) ?></span></p>

                                <?php
                            }
                        }
                        ?>
                    </div>               
                </div>
            </div>







            <div style="clear: both;"></div>


            <div class="">
                <div class="col-md-12 col-sm-12">
                    <p></p>
                    <div class="uploadedimages" style="display: none">
                    </div>
                    <button type="button" class="btn savechanges_submit1 upload_images">Choose file</button>
                </div>
                <div class="uploadbox">
                    <p class="advisortext documenttext">Documents Uploaded</p>
                    <div class="row uploadedimgs">
                        <?php
                        if (count($attachments) != 0) {
                            foreach ($attachments as $key => $value) {
                                ?>
                                <div class="col-md-4 col-sm-6"><img src="<?= $value->attachment ?>" alt="" style="width: 100%; height: 300px"></div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>  
            </div>




            <!-------------------->
            <div class="parmentaddressnew">
                <?= $form->field($model, 'charges')->textInput(['maxlength' => true, 'placeholder' => 'Title*', 'class' => 'form-control isNumber'])->label('Actual Charges'); ?>
                <?= $form->field($model, 'charges_to_owner')->textInput(['maxlength' => true, 'placeholder' => 'Title*', 'class' => 'form-control isNumber'])->label('Charges To Owner'); ?>
                <?= $form->field($model, 'payment_due_date')->textInput(['maxlength' => true, 'placeholder' => 'Title*', 'class' => 'form-control datepicker'])->label('Due Date'); ?>


                <div class="form-group">
                    <label for="">Creation Date</label>
                    <?= Date('d-m-Y', strtotime($model->created_date)) ?>
                </div>

                <div class="form-group">
                    <label for="">Request Status</label>

                    <?php
//$states = \app\models\PropertyTypes::find()->all();
                    $stateData = ArrayHelper::map($request, 'id', 'name');
                    ?>
                    <?= $form->field($model, 'status')->dropDownList($stateData, ['prompt' => 'Select Status'])->label(false); ?>


                </div>
                <h4><strong>Payment Details</strong></h4>


                <?= $form->field($payment, 'amount')->textInput(['maxlength' => true, 'placeholder' => 'Amount*', 'class' => 'form-control isNumber'])->label('Amount'); ?>
                <?= $form->field($payment, 'transaction_id')->textInput(['maxlength' => true, 'placeholder' => 'Transaction Id', 'class' => 'form-control'])->label('Transaction Id'); ?>
                <?= $form->field($payment, 'date_of_payment')->textInput(['maxlength' => true, 'placeholder' => 'Date Of Payment*', 'class' => 'form-control datepicker']); ?>

                <?= $form->field($payment, 'type_of_payment')->dropDownList(['0' => 'Offline', '1' => 'Online'], ['prompt' => 'Select Type Of Payment'])->label('Type Of Payment'); ?>

            </div>
        </div>


        <div class="twobutall">
            <div class="col-lg-5 col-sm-4">
                <input type="submit" class="btn savechanges_submit_contractaction" value="Save">

            </div>
            <div class="col-lg-5 col-sm-4">
                <button type="button" class="btn savechanges_submit cancel_confirm">Cancel</button>
            </div>
            <div class="lineheightbg"></div>
        </div>
        <p>&nbsp;</p>     
        <?php ActiveForm::end(); ?>
    </div>


</div>
</div>

<script type="text/javascript">
    $(document).on('click', '.upload_images', function () {
        var count = $('.uploadedimages input').length;
        $('.uploadedimages').append('<input type="file" name="attachment[]" class="selected_attachment" style="display:none" data-id="' + count + '">');
        $('input[data-id="' + count + '"]').click();
    });

    $(document).on('change', '.selected_attachment', function () {
        readUrl(this);
    });

    function readUrl(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('.uploadedimgs').append('<div class="col-md-4 col-sm-6"><img src="' + e.target.result + '" alt="" style="width: 100%; height: 300px"></div>');
            }

            reader.readAsDataURL(input.files[0]);

        }
    }
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
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    $(document).on('click', '.replybut', function () {
        $.ajax({
            url: 'addmaintenancecomment',
            type: 'POST',
            dataType: 'JSON',
            data: {'val': $('#comment').val(), 'service': <?php echo base64_decode($_GET['id']) ?>, _csrf: csrfToken},
            success: function (data) {
                if (data.success) {
                    var string = '';
                    $(data.comments).each(function (key, value) {
                        string += '<p>' + value.message + '<span>-' + value.user + ' ' + value.date + ' at ' + value.time + '</span></p>';
                    });
                    $('.notexbox .lorembox').html(string);
                    $('#comment').val('');
                } else {
                    alert('Some Error Occured Please Try Again');
                }

            },
            error: function (data) {
                //    console.log(data.responseText);
                alert(data.responseText);
            }
        });
    })
</script>