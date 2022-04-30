<?php
$this->title = 'PMS- Visit';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<style>
    .navbar {
        margin-top: 0px !important;
    }
    h4#myModalLabel {
        margin: 16px 0px;
    }
    form#w0 {
        width: 50%;
        margin: 0 auto;
    }
</style>
<div class="signupbody">
    <div class="personalinfomation">

        <div class="parmentaddress">



            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

            <?= Html::submitButton('Pay', ['class' => 'btn savechanges_submit', 'name' => 'payment_mode']) ?>

            <?php ActiveForm::end(); ?>






        </div>





        </fieldset> 

    </div>