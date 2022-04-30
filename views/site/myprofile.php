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
        <div class="col-lg-8 col-sm-8 col-xs-8 col-xs-offset-1"> 

            <h1>My contact details</h1>

            <?php if (Yii::$app->session->hasFlash('success')) { ?>
                <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                    <?= Yii::$app->session->getFlash('success'); ?>
                </div>   
            <?php } ?>

            <div class="supportperson">
                <fieldset> 
                    <h6>Profile Information</h6>
                    <ul>
                        <li>Name<span> <?= $model->full_name; ?></span></li>
                        <li>Permanent address<span><?= $model->address; ?> ,<?= $model->city; ?> ,<?= $model->state; ?> </span></li>
                    </ul>
                    <div class="clearfix"></div>

                    <h6>My contact details</h6>
                    <ul>
                        <li>Email<span> <?= $model->email; ?></span></li>
                        <li>Phone<span><?= $model->phone; ?>  </span></li>
                    </ul>
                    <div class="clearfix"></div>

                    <h6>My emergency contact detail </h6>
                    <ul>
                        <li>Details<span> <?= $model->emer_contact; ?></span></li>
                    </ul>

                    <div class="clearfix"></div>
                    <?php echo Html::a('Edit Profile', Url::to('editprofile'), ['class' => 'pull-right']); ?>
                    <br /><br />
                </fieldset>	
                <?php if (Yii::$app->userdata->usertype == 2 || Yii::$app->userdata->usertype == 3) { ?>				  
                    <div class="mybankdetail">
                        <h2>My Bank details</h2>
                        <ul>
                            <?php if ($bankDetails) { ?>
                                <li>Account holder name<span><?= $bankDetails->account_holder_name; ?></span></li>
                                <li>Bank name<span><?= $bankDetails->bank_name; ?></span></li>
                                <li>Account number<span><?= $bankDetails->account_number; ?></span></li>
                                <li>IFSC code<span><?= $bankDetails->ifsc_code; ?></span></li>

                                <div class="clearfix"></div>
                                <?php echo Html::a('Edit Bank Details', Url::to('bankdetails'), ['class' => 'pull-right']); ?>
                                <br /><br />
                            <?php } else { ?>

                                <li> No Bank Details given </li>
                                <li> <?php echo Html::a('Add Details', Url::to('bankdetails'), ['class' => ' savechanges_submit']); ?>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                <?php } ?>

                <div class="clearfix"></div>
                <?php if (Yii::$app->userdata->usertype == 4 || Yii::$app->userdata->usertype == 3) { ?>				  
                    <div class="mybankdetail">
                        <h2>Support Person</h2>

                    </div>
                <?php } ?>

                <div class="clearfix"></div>


            </div>




            <div class="clearfix"></div>

        </div>

        <!-- /.col-lg-12 -->
    </div>
</div>

