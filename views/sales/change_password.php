<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Change Password';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="modal-header">
    Change Password  	
    <button type="button" class="close" data-dismiss="modal">&times;</button>

</div>

<?php if (Yii::$app->session->hasFlash('success')) { ?>
    <div class="alert alert-success">
        <?= Yii::$app->session->getFlash('success'); ?>
    </div>   
<?php }
?>

<div class="changepassword">
    <p>Please fill out the following fields to change password :</p>

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

    <?=
    $form->field($model, 'oldpass', ['inputOptions' => [
            'placeholder' => 'Old Password', 'class' => 'form-control'
]])->passwordInput();
    ?>

    <?=
    $form->field($model, 'newpass', ['inputOptions' => [
            'placeholder' => 'New Password', 'class' => 'form-control'
]])->passwordInput();
    ?>

    <?=
    $form->field($model, 'repeatnewpass', ['inputOptions' => [
            'placeholder' => 'Repeat New Password', 'class' => 'form-control'
]])->passwordInput();
    ?>
    <div class="clearfix"> </div>           
    <div class="form-group">

        <?=
        Html::submitButton('Save', [
            'class' => 'btn btn-success btn btn-primary btn-md'
        ])
        ?>

    </div>
    <?php ActiveForm::end(); ?>

    <div class="modal-footer">

    </div>
