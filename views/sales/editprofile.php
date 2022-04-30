<?php
$this->title = 'PMS- My profile';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<!-- Modal signup -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-5 col-xs-offset-3">
            <div class="changepassword">
                <h1>My contact details</h1>

                <?php if (Yii::$app->session->hasFlash('success')) { ?>
                    <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                        <?= Yii::$app->session->getFlash('success'); ?>
                    </div>   
                <?php } ?>



                <?php if (Yii::$app->session->hasFlash('error')) { ?>
                    <div class="alert alert-danger fade in alert-dismissable" style="margin-top:18px;">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                        <strong>Error!</strong> <?= Yii::$app->session->getFlash('error'); ?>
                    </div>   
                <?php } ?>




                <?php $form = ActiveForm::begin(); ?>
                <?= $form->errorSummary($model); ?>


                <?= $form->field($model, 'full_name')->textInput(['maxlength' => true, 'placeholder' => 'Fullname*'])->label(false) ?>

                <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'placeholder' => 'Phone Number*'])->label(false) ?>

                <?= $form->field($model, 'address')->textInput(['maxlength' => true, 'placeholder' => 'Permanent address*'])->label(false) ?>


                <?= $form->field($model, 'emer_contact')->textInput(['maxlength' => true, 'placeholder' => 'Emergency contact number*'])->label(false) ?>

                <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => 'Email id*'])->label(false) ?>

                <?= Html::submitButton('Save', ['class' => 'btn savechanges_submit']) ?>

                <?php ActiveForm::end(); ?>


            </div>

            <div class="lineheightbg"></div>
            <div class="supportperson">
                <h6>Support person detail</h6>
                <ul>
                    <li>Name<span>Kartik Singh</span></li>
                    <li>Phone number<span>9876543210</span></li>
                    <li>Email<span>Loreumipsum@gmail.com</span></li>
                </ul>
            </div>

            <div class="col-xs-12 col-md-12">
                <a href="#" class="thumbnail">
                    <img src="<?php echo Url::home(true); ?>images/photo.jpg" alt="...">
                </a>
            </div>
            <div class="clearfix"></div>

        </div>

        <!-- /.col-lg-12 -->
    </div>
</div>

