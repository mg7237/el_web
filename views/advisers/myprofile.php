<style>
    img.preview {
        width: 96%;
        height: 200px;
    }
</style>

<?php
$this->title = 'PMS- My profile';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<div id="page-wrapper">
    <div class="row">
        <?php if (Yii::$app->session->hasFlash('success')) { ?>
            <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                <?= Yii::$app->session->getFlash('success'); ?>
            </div>
        <?php } ?>
        <div class="col-lg-12 col-sm-11 col-xs-12 col-md-12 text-right" style="margin-bottom: 20px">

            <?php echo Html::a('Edit', Url::to('editprofile'), ['class' => 'btn savechanges_submit editbutright']); ?>
        </div>

        <div class="col-lg-5 col-sm-7 col-md-5 col-lg-5 col-xs-offset-2">
            <div class="supportperson mytop">
                <h6>My contact details</h6>
                <ul>
                    <li>Name<span><?= Yii::$app->userdata->getFullNameById($model->advisor_id); ?></span></li>
                    <li>Phone number<span><?= $model->phone; ?></span></li>
                    <li>Email Id<span><?= Yii::$app->userdata->getUserEmailById($model->advisor_id); ?></span></li>
                    <li>Permanent address<span>#<?= $model->address_line_1 . ' ' . $model->address_line_2; ?></span></li>
                    <!--<li>Emergency contact number<span><?= $model->emer_contact; ?></span></li>-->
                    <li>City<span><?= Yii::$app->userdata->getCityName($model->city); ?></span></li>
                    <li>State<span><?= Yii::$app->userdata->getStateName($model->state); ?></span></li>
                    <li>Pincode<span><?= $model->pincode; ?></span></li>
                </ul>
            </div>
            <div class="lineheightbg"></div>
            <div class="col-lg-12 col-sm-11 col-xs-12 col-md-12 changepassword">
                <h1>Agreement contract details </h1>
                <div class="form-group">
                    <div class="col-lg-12">
                        <div class="supportperson supportpersonmt">
                            <?php if ($advisorAgreements) { ?>
                                <ul>
                                    <li>Start Date<span><?= date('d-M-Y', strtotime($advisorAgreements->start_date)); ?></span></li>
                                    <li>End Date<span><?= date('d-M-Y', strtotime($advisorAgreements->end_date)); ?></span></li>
                                </ul>
                            <?php } ?>
                        </div>
                        <!-- <a href="../owner/agreement.html">View Agreement</a>
                        -->
                        <br/>
                    </div>
                    <div class="previewDoc " id="agreement_doc">
                        <?php if (isset($advisorAgreements->agreement_doc) && $advisorAgreements->agreement_doc != '') {
                            ?>
                            <iframe  src="http://docs.google.com/gview?url=<?= Url::home(true) . $advisorAgreements->agreement_doc ?>&embedded=true" class="abc" width="100%" frameborder="0" allowfullscreen></iframe>						
                            <?php
                        }
                        ?>
                    </div>
                </div>

            </div>

            <div class="col-lg-12 col-sm-12">
                <div class="lineheightbg"></div>
                <div class="supportperson">
                    <h6>Identity and Address Proof Documents</h6>
                    <div class="uploadbox">
                        <div class="row identity_image_preview">
                            <?php
                            if ($address_proofs) {
                                foreach ($address_proofs as $key => $value) {
                                    ?>
                                    <div class="col-md-6">
                                        <img class= "preview" alt="" src="../<?= $value->proof_document_url ?>"><p class="identityp"><?php echo Yii::$app->userdata->getProofType($value->proof_type); ?></p><a class="removeIdentity" data-id="<?= $value->id ?>">Remove</a>
                                    </div>
                                <?php
                                }
                            } else {
                                ?>
                                <p class="myprofiletext">No documents available</p>
<?php } ?>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 col-sm-12">
                <div class="lineheightbg"></div>
                <div class="supportperson">
                    <h6>Financial Data</h6>
                    <ul>
                        <li>bank name<span><?= $model->bank_name; ?></span></li>
                        <li>Branch name<span><?php echo $model->bank_branchname; ?></span></li>
                        <li>Ifsc<span><?php echo $model->bank_ifcs; ?></span></li>
                        <li>Account number <span><?php echo $model->account_number; ?></span></li>
                        <li>Pan Number<span><?php echo $model->pan_number; ?></span></li>
                        <li>Service Tax Number<span><?php echo $model->service_tax_number; ?></span></li>
                        <!--
                                               <li>Service tax number<span>5487</span></li>
                        -->
                    </ul>
                </div>
            </div>

            <?php if ($model->sales_id != '' && $model->sales_id != '0') {
                ?>
                <div class="col-lg-12 col-sm-12">
                    <div class="lineheightbg"></div>
                    <div class="supportperson">
                        <h6>Support person detail</h6>
                        <ul>
                            <li>Name<span><?= Yii::$app->userdata->getFullNameById($model->operation_id) ?></span></li>
                            <li>Phone number<span><?= Yii::$app->userdata->getPhoneNumberById($model->operation_id, '7') ?></span></li>
                            <li>Email<span><?= Yii::$app->userdata->getLoginIdById($model->operation_id) ?></span></li>
                        </ul>
                    </div>
                </div>
            <?php }
            ?>

            <!----------------------------------reset password start------------------------------------->
            <div class="row">
                <div class="col-lg-12 col-sm-12">
                    <a style="margin-top:15px;" href="<?= Url::home(true) ?>site/changepassword" data-target="#myModal" data-toggle="modal" class="btn btn-info">Reset password</a>
                </div>
            </div>
            <!---------------------reset password-------------------------->

        </div>
        <div class="col-md-2 col-sm-2">
            <a href="#" class="thumbnail">
                <img src="<?= !empty($model->profile_image) ? "../" . $model->profile_image : Url::home(true) . 'images/photo.jpg' ?>" alt="...">
            </a>
        </div>
    </div>

    <!-- /.col-lg-12 -->
</div>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modalbg">

        <!-- Modal content-->
        <div class="modal-content">

        </div>
    </div>

</div>
