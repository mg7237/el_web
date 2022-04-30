<?php
$this->title = 'PMS- Payment';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12 wallettop">

            <?php if (Yii::$app->session->hasFlash('success')) { ?>
                <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                    <?= Yii::$app->session->getFlash('success'); ?>
                </div>   
            <?php } ?>

            <div class="signupbody">

                <div class="parmentaddress">

                    <div class="bookingbox" style="overflow: hidden;">

                        <div class="col-lg-6">
                            <h4> Rent : ₹ <?php echo Yii::$app->request->get('rent'); ?></h4>
                            <h4> Penalty amount : ₹ <?php echo Yii::$app->request->get('charges'); ?> </h4>
                            <h4> Total amount : ₹ <?php echo Yii::$app->request->get('totalamount'); ?> </h4>
                        </div>


                        <div class="col-lg-6 rentbox">

                            <h5>Wallet money: ₹<?php echo $amountWallet; ?></h5>
                            <br />
                        </div>

                        <?php
                        $amountLeft = $amountWallet - Yii::$app->request->get('totalamount');
                        ?>
                        <?php if ($amountLeft >= 0) { ?>
                            <div style="text-align:center" class="col-lg-12">
                                <p> Amount to pay  : <?php echo Yii::$app->request->get('totalamount'); ?> </p>
                                <p> Amount Left in wallet : <?= $amountLeft; ?> </p>
                            </div>
                            <div  class="col-lg-6">

                                <?php
                                $form = ActiveForm::begin(['enableAjaxValidation' => false,
                                            'enableClientValidation' => false, 'options' => ['enctype' => 'multipart/form-data']]);
                                ?>
                                <?php //echo   $form->errorSummary($model);  ?>

                                <?php echo $form->field($model, 'original_amount')->textInput(['value' => Yii::$app->request->get('rent'), 'maxlength' => true, 'id' => 'from', 'placeholder' => 'Original amount', 'readonly' => true]) ?>
                                <?php echo $form->field($model, 'penalty_amount')->textInput(['value' => Yii::$app->request->get('charges'), 'maxlength' => true, 'id' => 'from', 'placeholder' => 'Penalty amount', 'readonly' => true]) ?>
                                <?php echo $form->field($model, 'total_amount')->textInput(['value' => Yii::$app->request->get('totalamount'), 'maxlength' => true, 'id' => 'from', 'placeholder' => 'Total amount', 'readonly' => true]) ?>
                                <?php echo $form->field($model, 'remarks')->textInput(['maxlength' => true, 'id' => 'from', 'placeholder' => 'Remarks']) ?>


                                <div  class="col-lg-6">

                                    <?= Html::submitButton('Pay now', ['class' => 'btn bookbut']) ?>
                                </div>
                                <?php ActiveForm::end(); ?>

                            </div>

                        <?php } else { ?> 
                            <div style="text-align:center"  class="col-lg-12">
                                <p> Please add some amount in wallet to continue </p>
                                <a href="<?php echo Url::home(true); ?>site/wallet"> Go to Profile</a>
                            </div> 

                        <?php } ?>	         
                    </div>	





                </div>




            </div>


        </div>
    </div>
</div>



