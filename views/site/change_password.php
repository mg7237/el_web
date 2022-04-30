<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Change Password';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="modal-header">

    <button type="button" class="close closemodal" data-dismiss="modal">&times;</button>

</div>
<?php
$form = ActiveForm::begin([
            'id' => 'changepassword-form',
            'enableAjaxValidation' => true,
            'options' => ['class' => 'form-horizontal'],
            'fieldConfig' => [
            /*    'template'=>"{label}\n<div class=\"col-lg-3\">
              {input}</div>\n<div class=\"col-lg-5\">
              {error}</div>",
              'labelOptions'=>['class'=>'col-lg-2 control-label'], */
            ],
        ]);
?>
<div class="modal-body">
    <div class="changepassword">
        <h1>Change password</h1> 



        <?=
        $form->field($model, 'oldpass', ['inputOptions' => [
                'placeholder' => 'Old Password*', 'class' => 'form-control'
    ]])->passwordInput();
        ?>

        <?=
        $form->field($model, 'newpass', ['inputOptions' => [
                'placeholder' => 'New Password*', 'class' => 'form-control'
    ]])->passwordInput();
        ?>

        <?=
        $form->field($model, 'repeatnewpass', ['inputOptions' => [
                'placeholder' => 'Repeat New Password*', 'class' => 'form-control'
    ]])->passwordInput();
        ?>
        <div class="clearfix"> </div>   
    </div>        
</div>  
<div class="modal-footer">
    <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
        <?=
        Html::submitButton('Save', [
            'class' => 'btn btn-success btn btn-primary btn-md savechanges_submit_contractaction'
        ])
        ?>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
        <button class="btn btn-default savechanges_submit cancel_confirm_1 " data-dismiss="modal" type="button">Close</button>
    </div>



</div>
<?php ActiveForm::end(); ?>
