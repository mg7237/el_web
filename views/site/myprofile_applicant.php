<?php
$this->title = 'PMS- My profile';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$proofsAll = Yii::$app->userdata->getAllProofs($model->applicant_id);
?>


<!-- Modal signup -->
<div id="page-wrapper" class= "autoheight">
    <div class="row">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

        <div class="row wallettop">
            <div class="col-lg-9 col-sm-9 col-xs-offset-2">
                <div class="col-md-12 col-sm-12">

                    <?php if (Yii::$app->session->hasFlash('success')) { ?>
                        <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                            <?= Yii::$app->session->getFlash('success'); ?>
                        </div>   
                    <?php } ?>
                    <?php
                    /* 	echo  "<pre>";
                      print_r($model);
                      echo  "</pre>"; */
                    ?> 

                    <div class="col-md-4 col-sm-6">
                        <div class="parmentaddress page_heading">
                            <h1>Personal Details</h1>
                        </div>
                    </div>
                    <div class="col-md-8 col-sm-6">
                        <div class="col-lg-12 text-right">
                            <?php echo Html::a('Edit', Url::to('editprofile'), ['class' => 'btn savechanges_submit editbutright']); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">

            <div class="col-lg-5 col-sm-6 col-xs-offset-2">

                <div class="myprofile supportperson mytop">
                    <span>Name </span>
                    <p><?= Yii::$app->userdata->getFullNameById($model->applicant_id); ?></p>

                </div>
                <div class="lineheightbg"></div>
                <div class="parmentaddress">

                    <h1>Contact detail </h1>
                    <p class="myprofiletext">Email <span><?= $model->email_id; ?></span></p>
                    <p class="myprofiletext">Phone Number<span><?= $model->phone; ?></span></p>
                </div>


                <div class="lineheightbg"></div>
                <div class="parmentaddress">
                    <h1>Permanent address</h1>
                    <p class="myprofiletext">Address<span><?= $model->address_line_1; ?> <?= $model->address_line_2; ?></span></p>
                    <p class="myprofiletext">State<span><?= Yii::$app->userdata->getStateName($model->state); ?></span></p>
                    <p class="myprofiletext">City<span><?= Yii::$app->userdata->getCityName($model->city); ?></span></p>
                    <p class="myprofiletext">Pincode<span><?= $model->pincode; ?></span></p>

                </div>
                <div class="lineheightbg"></div>
                <div class="uploadbox">
                    <div class="changepassword">
                        <h1 class="advisortext">Identity and Address Proof Documents</h1>
                        <div class="row">

                            <?php
                            if (count($proofsAll['address']) != 0) {
                                foreach ($proofsAll['address'] as $key => $value) {
                                    ?>
                                    <div class="col-md-6 col-sm-6">
                                        <img class= "preview" src="<?= "../" . $value->proof_document_url; ?>" alt="">
                                        <p class= "doc_name"><?php echo Yii::$app->userdata->getProofType($value->proof_type); ?></p><a class="removeIdentity" data-id="<?= $value->id ?>">Remove</a>
                                    </div>
                                    <?php
                                }
                            } else {
                                ?>
                                <div class="col-md-6 col-sm-6">
                                    <!--<img class="preview" src="<?= Url::home(true) . 'images/doumentupload.jpg' ?>">-->
                                    <p>No documents available</p>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <!--<img class="preview" src="<?= Url::home(true) . 'images/doumentupload.jpg' ?>">-->
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <p></p>
                </div>
                <div class="lineheightbg"></div>
                <div class="parmentaddress">

                    <h1>My emergency contact detail </h1>
                    <p class="myprofiletext">Contact Name<span><?= $model->emergency_contact_name; ?></span></p>
                    <p class="myprofiletext">Contact Email<span><?= $model->emergency_contact_email; ?></span></p>
                    <p class="myprofiletext">Contact Number<span><?= $model->emergency_contact_number; ?></span></p>

                    <div class="uploadbox">
                        <div class="row emergency_image_preview" >
                            <?php
                            foreach ($emergency_proofs as $value) {
                                ?>
                                <div class="col-md-6">
                                    <img class= "preview" alt=""  src="../<?php echo $value->proof_document_url ?>"><p><?php echo Yii::$app->userdata->getProofType($value->proof_type); ?></p><a class="removeEmergency" data-id="<?= $value->id ?>">Remove</a>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>

                </div>

                <div class="lineheightbg"></div>
                <div class="changepassword">
                    <h1>My Bank details</h1>

                    <p class="myprofiletext">Account holder name<span><?= $model->account_holder_name; ?> </span></p>
                    <p class="myprofiletext">Bank name<span><?= $model->bank_name; ?></span></p>
                    <p class="myprofiletext">Branch name<span><?= $model->bank_branchname; ?></span></p>
                    <p class="myprofiletext">IFSC code<span><?= $model->bank_ifcs; ?></span></p>
                    <p class="myprofiletext">Account number<span><?= $model->bank_account_number; ?></span></p>
                    <p class="myprofiletext">PAN number<span><?= $model->pan_number; ?></span></p>
                </div>
                <div class="uploadbox">
                    <div class="changepassword">
                        <p><img class= "preview" src="../<?= $model->cancelled_check; ?>" alt="" ></p>
                        <p>Cancelled cheque </p>
                    </div>
                </div>
                <div class="lineheightbg"></div>
                <div class="parmentaddress col-lg-12 col-sm-12">
                    <h1>Wallet Summary </h1>
                    <p class="myprofiletext">Current balance amount  <span>Rs <?= Yii::$app->userdata->getApplicantWalletAmount($model->applicant_id) ?></span></p>
                </div>
                <div class="lineheightbg"></div>
                <div class="parmentaddress">

                    <h1>My employment data</h1>

                    <div class="chancelall">
                        <p class="myprofiletext">Employer Name<span><?= $model->employer_name; ?></span></p>
                    </div>
                    <p class="myprofiletext">Employee Id<span><?= $model->employee_id; ?></span></p>
                    <p class="myprofiletext">Employee Email<span><?= $model->employment_email ?></span></p>
                    <p class="myprofiletext">Employment Start Date<span><?= $model->employment_start_date; ?></span></p>


                </div>
                <div class="uploadbox">
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <img class= "preview" src="../<?= $model->employmnet_proof_url; ?>" alt=""><p class = "doc_name">Employment proof</p></div>

                    </div>
                    <p></p>
                </div>
                <a style="margin-top:8px;" href="<?= Url::home(true) ?>site/changepassword" data-target="#myModal" data-toggle="modal" class="btn btn-info">Reset password</a>

            </div>

            <div class="col-md-2 col-sm-2 common_profile_pic"style="margin-left: 10px;"> 
                <div class="myprofile supportperson mytop">
                    <a href="#" class="thumbnail">
                        <img class= "preview" src="<?= !empty($model->profile_image) ? "../" . $model->profile_image : Url::home(true) . 'images/photo.jpg' ?>" alt="...">
                    </a>
                </div>
            </div>
        </div>

        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">

                </div>
            </div>
        </div>
    </div>

</div>



