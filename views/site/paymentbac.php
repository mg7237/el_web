<?php
$this->title = 'PMS- Payment';

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
        <li>Schedule </li>
        <!--
                        <li>Confirmation </li>
                        <li>On Boarding </li>
        -->

    </ul>
    <fieldset>
        <div class="signupbody">
            <div class="personalinfomation">Make Payment<img src="<?php echo Url::home(true); ?>images/right.png" alt=""></div>


            <div class="bookingbox" style="overflow: hidden;">

                <div class="col-lg-6">
                    <h1> Rent </h1>
                    <h2>₹ <?php echo $model->propertyListing->rent; ?></h2>
                    <h3>Maintenance charges: <span> <?php echo $model->propertyListing->maintenance_included == 0 ? 'Not Included' : 'Included'; ?></span></h3>
                    <h4>Maintenance Fees: <span>₹  <?php echo $model->propertyListing->maintenance_included == 0 ? $model->propertyListing->maintenance : '0'; ?></span></h4>
                    <h5>Deposit: ₹ <?php echo Yii::$app->request->get('deposit'); ?></h5> 
                    <h5>Token Amount: ₹<?php echo Yii::$app->request->get('token_amount'); ?></h5>
                </div>


                <div class="col-lg-6 rentbox">

                    <h5>Wallet money: ₹<?php echo $amountWallet; ?></h5>
                    <br />
                </div>

                <?php
                $amountLeft = $amountWallet - Yii::$app->request->get('token_amount');
                ?>
                <?php if ($amountLeft >= 0) { ?>
                    <div style="text-align:center" class="col-lg-12">
                        <p> Amount to pay  : <?php echo Yii::$app->request->get('token_amount'); ?> </p>
                        <p> Amount Left in wallet : <?= $amountLeft; ?> </p>
                    </div>
                    <div style="text-align:center"  class="col-lg-12">

                        <?php $form = ActiveForm::begin(['id' => 'payment', 'options' => ['enctype' => 'multipart/form-data']]); ?>
                        <?php $form->errorSummary($tenantPayments); ?>

                        <?= $form->field($tenantPayments, 'rent')->hiddenInput(['maxlength' => true, 'value' => Yii::$app->request->get('rent')])->label(false) ?>
                        <?= $form->field($tenantPayments, 'total_amount')->hiddenInput(['maxlength' => true, 'value' => Yii::$app->request->get('token_amount')])->label(false) ?>
                        <?= $form->field($tenantPayments, 'payment_mode')->hiddenInput(['maxlength' => true, 'value' => 2])->label(false) ?>
                        <?= $form->field($tenantPayments, 'payment_status')->hiddenInput(['maxlength' => true, 'value' => 1])->label(false) ?>

                        <?= $form->field($model, 'availability_from')->hiddenInput(['maxlength' => true, 'value' => $model->propertyListing->availability_from])->label(false) ?>
                        <?= $form->field($agreements, 'min_contract_peroid')->hiddenInput(['maxlength' => true])->label(false) ?>
                        <?= $form->field($agreements, 'notice_peroid')->hiddenInput(['maxlength' => true])->label(false) ?>


                        <?php echo $form->field($myagreements, 'lease_start_date')->textInput(['maxlength' => true, 'id' => 'from', 'placeholder' => 'Move In Date'])->label(false) ?>
                        <?php echo $form->field($myagreements, 'lease_end_date')->textInput(['maxlength' => true, 'id' => 'to', 'placeholder' => 'Exit Date'])->label(false) ?>



                        <?= Html::Button('Pay now', ['class' => 'btn bookbut', 'onclick' => 'return datasubmit();']) ?>

                        <?php ActiveForm::end(); ?>

                    </div>

                <?php } else { ?> 
                    <div style="text-align:center"  class="col-lg-12">
                        <p> Please add some amount in wallet to continue </p>
                        <a href="<?php echo Url::home(true); ?>site/myprofile"> Go to Profile</a>
                    </div> 

                <?php } ?>	         
            </div>	






    </fieldset> 
</div>



