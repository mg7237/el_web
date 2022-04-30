<?php
$this->title = 'PMS- Payment';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<?php
$urlext = '';
foreach ($_GET as $key => $value) {
    $urlext .= $key . "=" . $value . "&";
}
$urlext = substr($urlext, 0, strlen($urlext) - 1);
?>
<?php if (Yii::$app->session->hasFlash('success')) { ?>
    <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
        <?= Yii::$app->session->getFlash('success'); ?>
    </div>   
<?php } ?>
<style>
    h4#myModalLabel {
        margin-top: 18px;
        font-size: 19px;
    }
    .navbar {
        margin-top: 0px !important;
    }
    #progressbar {
        margin-bottom: 15px;
        overflow: hidden;
        counter-reset: step;
        margin-top: 15px;
    }
    #msform .action-button {
        width: 100%;
        background: #347c17;
        color: white;
        border: 0 none;
        border-radius: 0px;
        cursor: pointer;
        padding: 2px 13px;
        margin: 0px 0px;
        font-size: 18px;
    }
    .savechanges_submit {
        background: #642493 none repeat scroll 0 0;
        border: 1px solid #642493;
        border-radius: 0;
        color: #ffffff;
        font-family: "open_sanssemibold";
        font-size: 18px;
        padding: 2px 13px;
        width: 100%;
        text-transform: capitalize;
        height: 32px;
    }
    .parmentaddress h1 {
        margin-top: 10px;
    }


</style>
<div id="msform">
    <!-- progressbar -->
    <ul id="progressbar">
        <li class="active">Bank Details</li>
        <li class="active">Make Payment</li>
        <li>Schedule </li>
        <!--
                        <li>Confirmation </li>
                        <li>On Boarding </li>
        -->

    </ul>
    <fieldset>
        <div class="signupbody">
            <div class="personalinfomation" style="margin-bottom: 15px;">Make Payment<img src="<?php echo Url::home(true); ?>images/right.png" alt=""></div>


            <div class="parmentaddress"> 

                <h1>Property Details</h1>
                <div class="bookingbox">
                    <?php
                    if ($model->property_type == '3') {
                        $maintenance = $model->propertyListing->maintenance;
                    } else {
                        $maintenance = Yii::$app->userdata->getChildPropertyMaintenance(Yii::$app->request->get('property_id'));
                    }
                    ?>
                    <h1> Booking Amount </h1>
                    <h2>₹ <?php echo Yii::$app->request->get('token_amount'); ?></h2>
                    <h3>Rent: ₹ <span> <?php echo Yii::$app->request->get('rent'); ?></span></h3>
                    <h4>Maintenance Fees: <span>  <?php echo ($maintenance == 0 ) ? 'included' : '₹' . $maintenance; ?></span></h4>
                    <h5>Deposit: ₹ <?php echo Yii::$app->request->get('deposit'); ?></h5> 


                </div>




                <?php
//$amountLeft  = $amountWallet - Yii::$app->request->get('token_amount') ;
                ?>
                <?php //if( $amountLeft >= 0 ){ ?>
                <!-- <div style="text-align:center" class="col-lg-12">
                                                <p> Amount to pay  : <?php //echo Yii::$app->request->get('token_amount') ;      ?> </p>
                                                <p> Amount Left in wallet : <?//= $amountLeft  ; ?> </p>
                                        </div>
                <div style="text-align:center"  class="col-lg-12"> -->

                <?php $form = ActiveForm::begin(['id' => 'payment', 'options' => ['enctype' => 'multipart/form-data']]); ?>
                <?php $form->errorSummary($PropertyBooking); ?>

                <?= $form->field($PropertyBooking, 'rent')->hiddenInput(['maxlength' => true, 'value' => Yii::$app->request->get('rent')])->label(false) ?>
                <?= $form->field($PropertyBooking, 'total_amount')->hiddenInput(['maxlength' => true, 'value' => Yii::$app->request->get('token_amount')])->label(false) ?>
                <?= $form->field($PropertyBooking, 'payment_mode')->hiddenInput(['maxlength' => true, 'value' => 2])->label(false) ?>
                <?= $form->field($PropertyBooking, 'payment_status')->hiddenInput(['maxlength' => true, 'value' => 1])->label(false) ?>

                <div class= "row">
                    <div class= "col-md-6" style= "padding: 0px 2px">
                        <?= Html::Button('Pay now', ['class' => 'btn savechanges_submit', 'onclick' => 'return datasubmit();', 'name' => 'submitForm']) ?>
                    </div>
                    <div class= "col-md-6" style= "padding: 0px 2px">
                        <input type="button" value="Previous" name="previous" class="previous action-button" style="height: 32px" onclick="location.href = 'bookinginfo?<?= $urlext ?>'">
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>

            </div>

            <?php //} else// {  ?> 
            <!-- <div style="text-align:center"  class="col-lg-12">
                    <p> Please add some amount in wallet to continue </p>
                     <a href="<?php //echo Url::home(true);      ?>site/myprofile"> Go to Profile</a>
            </div>  -->

            <?php //}  ?>	         
        </div>






    </fieldset> 
</div>


