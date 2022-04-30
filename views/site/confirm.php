<?php
$this->title = 'PMS- Confirmation';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<?php if (Yii::$app->session->hasFlash('success')) { ?>
    <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
        <?= Yii::$app->session->getFlash('success'); ?>
    </div>   
<?php } ?>
<div id="msform">
    <!-- progressbar -->
    <ul id="progressbar">
        <li class="active">Bank Details</li>
        <li class="active">Make Payment</li>
        <li class="active">Schedule </li>
        <li class="active">Confirmation </li>
        <li>On Boarding </li>

    </ul>
    <fieldset>
        <div class="signupbody">
            <div class="personalinfomation">Confirmation<img src="<?php echo Url::home(true); ?>images/right.png" alt=""></div>

            <!--
                  <a href="<?php echo Url::home(true); ?>site/onboarding?id=<?= $agreement_id ?>" class="btn savechanges_submit next" type="button">Confirm</a>
            -->

            <a href="<?php echo Url::home(true); ?>site/myfavlist" class="btn savechanges_submit next" type="button">Confirm</a>





    </fieldset> 
</div>

