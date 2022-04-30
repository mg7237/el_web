<?php
$this->title = 'PMS- My Bank Details';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<!-- Modal signup -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 col-xs-offset-3">
            <div class="changepassword">
                <h1>My Bank details</h1>

                <?php if (Yii::$app->session->hasFlash('success')) { ?>
                    <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                        <?= Yii::$app->session->getFlash('success'); ?>
                    </div>   
                <?php } ?>



                <?php $form = ActiveForm::begin(); ?>
                <?php //$form->errorSummary($model); ?>


                <?= $form->field($model, 'account_holder_name')->textInput(['maxlength' => true, 'placeholder' => 'Account Holder Name*'])->label(false) ?>

                <?= $form->field($model, 'bank_name')->textInput(['maxlength' => true, 'placeholder' => 'Bank Name*'])->label(false) ?>

                <?= $form->field($model, 'account_number')->textInput(['maxlength' => true, 'placeholder' => 'Account Number*'])->label(false) ?>


                <?= $form->field($model, 'ifsc_code')->textInput(['maxlength' => true, 'placeholder' => 'IFSC Code*'])->label(false) ?>

                <?= Html::submitButton('Save', ['class' => 'btn savechanges_submit']) ?>

                <?php ActiveForm::end(); ?>


            </div>


        </div>

        <!-- /.col-lg-12 -->
    </div>
</div>

