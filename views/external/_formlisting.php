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
</style>
<script type="text/javascript">
    $(function () {
        $(".datepicker").datepicker({
            minDate: new Date(),
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: 'dd-M-yy',

        });
    });
</script>



<div class="modal-header">
    Edit Property Listing
    <button type="button" class="close" data-dismiss="modal">&times;</button>		
</div>
<div class="modal-body">		
    <?php
    $form = ActiveForm::begin(['id' => 'applicantform', 'options' => [
                    'class' => 'forminputpopup'
                ], 'enableAjaxValidation' => true]);
    ?>

    <?= $form->field($model, 'id')->hiddenInput(['maxlength' => true, 'placeholder' => 'Rent*', 'class' => 'form-control isNumber'])->label(false); ?>        
    <?= $form->field($model, 'rent')->textInput(['maxlength' => true, 'placeholder' => 'Rent*', 'class' => 'form-control isNumber']); ?>
    <?= $form->field($model, 'deposit')->textInput(['maxlength' => true, 'placeholder' => 'Deposit*', 'class' => 'form-control isNumber'])->label('Deposit'); ?>
    <?= $form->field($model, 'token_amount')->textInput(['maxlength' => true, 'placeholder' => 'Token Amount*', 'class' => 'form-control isNumber']); ?>
    <?= $form->field($model, 'maintenance')->textInput(['maxlength' => true, 'placeholder' => 'Maintenance*', 'class' => 'form-control isNumber']); ?>
    <?= $form->field($model, 'availability_from')->textInput(['maxlength' => true, 'placeholder' => 'Availability From*', 'class' => 'form-control datepicker']); ?>



    <?php
    if ($type == 3) {
        echo $form->field($model, 'status')->dropDownList([1 => 'Listed', 0 => 'Unlisted'], ['prompt' => 'Select Status']);
    } else {
        echo $form->field($modelListing, 'status')->dropDownList([1 => 'Listed', 0 => 'Unlisted'], ['prompt' => 'Select Status']);
    }
    ?>


    <div class="row advisorbox" style= "margin: 30px auto auto;">
        <div class="col-md-6">
            <?= Html::submitButton('Save', ['class' => 'btn savechanges_submit_contractaction']) ?>
        </div>
        <div class="col-md-6">
            <a style="color:#FFF" class="btn savechanges_submit cancel_confirm_1" href="<?= Url::home(true); ?>external/propertieslisting" type="button">Cancel</a>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<script>
    $(document).on("click", ".cancel_confirm_1", function (e) {

        var element = $(this);
        var confirm_var = confirm('Are you sure you want to cancel ?');
        if (confirm_var == true) {
            //window.location.href = (element.attr('href'));
            $("#myModal2").modal("hide");
        } else {
            e.preventDefault();
        }
    });
</script>