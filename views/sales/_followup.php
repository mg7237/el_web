 
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


<div class="modal-header">
    Add 	Follow Up
    <button type="button" class="close" data-dismiss="modal">&times;</button>

</div>
<div class="modal-body">		
    <?php $form = ActiveForm::begin(['id' => 'loan',]); ?>

    <?= $form->field($model, 'scheduled_date_time')->textInput(['maxlength' => true, 'class' => 'datetimepicker1']) ?>
    <div id="error" style="color:red"></div>
    <?= $form->field($model, 'leads_id')->hiddenInput(['value' => Yii::$app->getRequest()->getQueryParam('id'), 'template' => '{input}'])->label(false) ?>
    <?= $form->field($model, 'url')->hiddenInput(['value' => \Yii::$app->getUrlManager()->createUrl('sales/savefollow'), 'template' => '{input}'])->label(false) ?>

    <div class="form-group">
        <?= Html::Button('Create', ['class' => 'btn btn-success', 'id' => 'leadsreferencesbtn']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<div class="modal-footer">

</div>
