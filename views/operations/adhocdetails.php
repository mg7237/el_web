 
<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'PMS- Adhoc Details';
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

<div id="page-wrapper">	
    <div class="row">
        <h1>Adhoc Detail</h1>
        <?php
        $form = ActiveForm::begin(['id' => 'adhocform', 'options' => [
                        'class' => 'forminputpopup', 'enctype' => 'multipart/form-data'
                    ], 'enableAjaxValidation' => true]);
        ?>
        <div class="form-group">
            <label class="control-label" for="adhocrequests-title">Tenant Name</label> 
            <input type="text" id="tenant_name" value="<?= Yii::$app->userdata->getFullNameById($model->tenant_id) ?>" class="form-control isCharacter" placeholder="Tenant Name*" >
            <ul class="tennat_list" style="display: none;">

            </ul>
            <?= $form->field($model, 'tenant_id')->hiddenInput()->label(false); ?>

        </div>
        <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'placeholder' => 'Adhoc Title*', 'class' => 'form-control'])->label('Title'); ?>

        <div class="col-lg-12 col-md-12">
            <div class="col-lg-9 col-md-9">
                <div class="form-group field-propertyservicecomment-message required has-success">
                    <label class="control-label" for="propertyservicecomment-message">Add Comment</label>
                    <textarea id="property-message" class="form-control" name="PropertyServiceComment[message]" rows="2" style="max-width:100%;" aria-required="true" aria-invalid="false"></textarea>

                    <div class="help-block"></div>
                </div>    
            </div>
            <div class="col-lg-3 col-md-3" style="text-align: right;">
                <a id="add_comment" class="btn btn-default" style="margin-top: 29px">Add Comment</a>
            </div>
            <div class="lorembox" id="dataComments">
                <?php
                if ($comments) {
                    foreach ($comments as $comment) {
                        ?>
                        <p><?= $comment->message; ?>, 
                            <span>  -<?= Yii::$app->userdata->getFullNameById($comment->user_id); ?> on  <?= date('m/d/Y', strtotime($comment->created_date)); ?> At  <?= date('h:i a', strtotime($comment->created_date)); ?></span>
                        </p>
                        <?php
                    }
                } else {
                    echo "<p> No comments</p>";
                }
                ?>            
            </div>
        </div>
        <div class="form-group">
            <label style="margin-bottom: 12px;">Attachment</label>
            <a href="javascript:" id="attachment_add">Add Attachment</a>
            <div class="image_input_list" style="display:none"></div>
            <div class="images_list">
                <?php
                if (count($modelAttachments) != 0) {
                    foreach ($modelAttachments as $key => $value) {
                        ?>
                        <div class="col-md-3">
                            <img class= "preview" alt="" src="../<?= $value->url ?>">
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>


        <?= $form->field($model, 'actual_charge')->textInput(['maxlength' => true, 'placeholder' => 'Actual Charge*', 'class' => 'form-control isNumber'])->label('Actual Charges'); ?>

        <?= $form->field($model, 'charge_to_tenant')->textInput(['maxlength' => true, 'placeholder' => 'Charge To Tenant*', 'class' => 'form-control isNumber'])->label('Charge To Tenant'); ?>
        <?= $form->field($model, 'payment_due_date')->textInput(['maxlength' => true, 'placeholder' => 'Payment Due Date*', 'class' => 'form-control datepicker'])->label('Payment Due Date'); ?>
        <?= $form->field($model, 'payment_status')->dropDownList([0 => 'Unpaid', 1 => 'Paid'], ['prompt' => 'Select Request Type'])->label('Payment Status'); ?>

        <h3>Payment Detail Section</h3>
        <?= $form->field($modelPayment, 'amount')->textInput(['placeholder' => 'Amount*', 'class' => 'form-control isNumber']); ?>
        <?= $form->field($modelPayment, 'transaction_id')->textInput(['maxlength' => true, 'placeholder' => 'Transaction Id*', 'class' => 'form-control']); ?>
        <?= $form->field($modelPayment, 'created_date')->textInput(['maxlength' => true, 'placeholder' => 'Created Date*', 'class' => 'form-control datepicker'])->label('Date Of Payment'); ?>
        <?php
        $TypeData = \yii\helpers\ArrayHelper::map($payment_type, 'id', 'name');
        ?>
        <?= $form->field($modelPayment, 'type_of_payment')->dropDownList($TypeData, ['prompt' => 'Payment Type']); ?>

        <?= $form->field($model, 'created_date')->textInput(['maxlength' => true, 'placeholder' => 'Created Date*', 'class' => 'form-control datepicker']); ?>

        <?= $form->field($model, 'updated_date')->textInput(['maxlength' => true, 'placeholder' => 'Updated Date*', 'class' => 'form-control datepicker']); ?>
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
    #attachment_add:hover{
        background-color: #337ab7;
        color: white;

    }
    #attachment_add{
        color: #337ab7;
        background-color: white;
        padding: 10px 10px;
        border-radius: 5px;
        text-decoration: none;
        border: 1px solid #337ab7;
    }

    .images_list img.preview {
        width: 97%;
        margin-top: 20px;
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

    $(document).on('click', '#add_comment', function (e) {
        e.preventDefault();
        var message = $('#property-message').val();
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        $.ajax({
            url: "addadhoccomment",
            type: "POST",
            dataType: 'json',
            data: 'message=' + message + '&service_id="<?= $_GET['id']; ?>"&_csrf=' + csrfToken,
            success: function (data) {
                var html = '';
                $(data).each(function (key, value) {
                    html = html + "<p>" + value.message + ",<span>  -" + value.username + " on  " + value.created_date + " At  " + value.created_time + "</span></p>";

                });
                $('#property-message').val('');
                $('#dataComments').html(html);
            }

        });
    })
    $(document).on('click', '#attachment_add', function () {
        var length = $('.image_input_list input[type="file"]').length;
        if (length == 0) {
            var data_number = 1;
        } else {
            var data_number = parseInt($('image_input_list input[type="file"]:last-child').attr('data-number')) + 1;
        }
        $('.image_input_list').append('<input type="file" name="images[]" data-number="' + data_number + '"/>');
        $('.image_input_list input[type="file"][data-number="' + data_number + '"]').click();
    });

    $(document).on('change', '.image_input_list input[type="file"]', function () {
        var element = readurlIdentity(this);
    })

    function readurlIdentity(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {

                $('.images_list').append('<div class="col-md-3"><img class= "preview" alt=""src="' + e.target.result + '"></div>');
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>