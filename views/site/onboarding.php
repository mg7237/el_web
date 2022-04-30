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
        <li class="active">On Boarding </li>

    </ul>
    <fieldset>
        <div class="signupbody">
            <p>&nbsp;</p>
            <div class="personalinfomation">Onboarding Checklist </div>
            <div class="col-lg-6 rightcheckbox">
                <ul>
                    <li>
                        <label  style="float: none;" class="control control--checkbox">Photograph
                            <input type="checkbox" checked="checked">
                            <div class="control__indicator"></div>
                        </label>
                    </li>
                    <li>
                        <label  style="float: none;"  class="control control--checkbox">Pan card
                            <input checked="checked">
                            <div class="control__indicator"></div>
                        </label>
                    </li>
                    <li>
                        <label  style="float: none;"  class="control control--checkbox">Passport
                            <input checked="checked">
                            <div class="control__indicator"></div>
                        </label>
                    </li>
                    <li>
                        <label  style="float: none;"  class="control control--checkbox">Address proof
                            <input checked="checked">
                            <div class="control__indicator"></div>
                        </label>
                    </li>
                    <li>
                        <label  style="float: none;"  class="control control--checkbox">Signed rental agreement 
                            <input checked="checked">
                            <div class="control__indicator"></div>
                        </label>
                    </li>
                    <li>
                        <label  style="float: none;"  class="control control--checkbox">Deposit amount clearance   
                            <input checked="checked">
                            <div class="control__indicator"></div>
                        </label>
                    </li>
                </ul>
            </div>
            <div class="col-lg-6 rightuploadtext">Upload address proof</div>
        </div>
        <div class="signupbody">
            <p>&nbsp;</p>
            <div class="movebox">
                <h1>Move In</h1>
                <p>Move in message loreum ipsum is a dummy text</p>
                <span>  <?php
                    $date = Yii::$app->userdata->getAgreementByPk(Yii::$app->request->get('id'));
                    if ($date) {
                        echo $date->lease_start_date;
                    }
                    ?></span>
            </div>

        </div>
        <input type="button" value="Previous" class="previous action-button" name="previous">

        <button class="btn savechanges_submit " type="button">Submit</button>

    </fieldset> 
</div>

