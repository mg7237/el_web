<?php
//echo "hello";die;
$this->title = 'PMS- My profile';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$proofsAll = Yii::$app->userdata->getAllProofs($model->tenant_id);
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>


<!-- Modal signup -->
<div id="page-wrapper" class= "autoheight">
    <div class="row">
        <div class="col-md-8 col-sm-8 col-xs-8 col-xs-offset-2 wallettop">

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

            <div class="col-md-4 col-sm-6 col-xs-6">
                <div class="parmentaddress page_heading">
                    <h1>Personal Details</h1></div>
            </div>
            <div class="col-md-8 col-sm-6 col-xs-8 text-right">
                <?php echo Html::a('Edit', Url::to('editprofiletenant'), ['class' => 'btn savechanges_submit editbutright']); ?>
            </div>
        </div>
    </div>
    <div class="row">

        <div class="col-lg-5 col-sm-5 col-md-5 col-xs-offset-2">

            <div class="myprofile supportperson mytop">
                <span>Name </span>
                <p><?= Yii::$app->userdata->getFullNameById($model->tenant_id); ?></p>

            </div>
            <div class="lineheightbg"></div>
            <div class="parmentaddress">

                <h1>Contact detail </h1>
                <p class="myprofiletext">Email <span><?php echo $UsersModel->login_id; ?></span></p>
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
                    <?php
                    if (count($proofsAll['address']) != 0) {
                        foreach ($proofsAll['address'] as $key => $value) {
                            ?>

                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <img class= "preview" src="../<?= $value->proof_document_url; ?>" alt="">
                                    <p class="doc_title"><?php echo Yii::$app->userdata->getProofType($value->proof_type); ?></p><a class="removeIdentity" data-id="<?= $value->id ?>">Remove</a>
                                </div>
                            </div>

                            <?php
                        }
                    } else {
                        ?>
                        <p class="myprofiletext">No documents available</p>
                    <?php } ?>
                </div>
                <br>

                <div class="lineheightbg"></div>
                <div class="parmentaddress">
                    <p style=""></p>
                    <h1>My emergency contact detail </h1>
                    <p class="myprofiletext">Emergency Contact Name<span><?php echo $model->emer_contact; ?></span></p> 
                       <!-- <p class="myprofiletext">Email<span><?php //echo $model->emergency_contact_email ;       ?></span></p>
                       <p class="myprofiletext">Emergency Number<span><?php //echo $model->emergency_contact_number ;        ?></span></p>-->
                    <div class="row " >
                        <?php
                        if (count($proofsAll['emergency']) != 0) {
                            foreach ($proofsAll['emergency'] as $key => $value) {
                                ?>

                                <div class="uploadbox">
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <img class= "preview" src="../<?= $value->proof_document_url; ?>" alt="">
                                        <p class="doc_title"><?php echo Yii::$app->userdata->getProofType($value->proof_type); ?></p><a class="removeEmergency" data-id="<?= $value->id ?>">Remove</a>
                                    </div>
                                </div>

                                <?php
                            }
                        }
                        ?>
                    </div>


                </div>

                <div class="lineheightbg"></div>
                <div class="changepassword">
                    <h1>My Bank details</h1>

                    <p class="myprofiletext">Account holder name<span><?= $model->account_holder_name; ?> </span></p>
                    <p class="myprofiletext">Bank name<span><?= $model->bank_name; ?></span></p>
                    <p class="myprofiletext">Branch name<span><?php echo $model->bank_branchname; ?></span></p>
                    <p class="myprofiletext">IFSC code<span><?php echo $model->bank_ifcs; ?></span></p>
                    <p class="myprofiletext">Account number<span><?php echo $model->account_number; ?></span></p>
                    <p class="myprofiletext">PAN number<span><?php echo $model->pan_number; ?></span></p> 
                </div>
                <div class="uploadbox">
                    <div class="changepassword">
                        <p><img src="../<?php echo $model->cancelled_check; ?>" alt="" ></p>
                        <p class="doc_title">Cancelled cheque </p>
                    </div>
                </div>
                <!--div class="parmentaddress col-lg-12 col-xs-12 col-md-12 col-sm-12">
                    <h1>Wallet Summary </h1>
                    <p class="myprofiletext">Current balance amount  <span>Rs6000</span></p>
                </div-->
                <div class="lineheightbg"></div>
                <div class="parmentaddress">

                    <h1>My employment data</h1>

                    <div class="chancelall">
                        <p class="myprofiletext">Employer Name<span><?php echo $model->employer_name; ?></span></p>
                    </div>
                    <p class="myprofiletext">Employee Id<span><?php echo $model->employee_id; ?></span></p>
                    <p class="myprofiletext">Employee Email<span><?php echo $model->employment_email; ?></span></p>
                    <p class="myprofiletext">Employment Start Date<span><?php echo $model->employment_start_date; ?></span></p>


                </div>
                <div class="uploadbox">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12"><img src="<?php echo $model->employment_proof_url; ?>" alt=""><p>Employment proof</p></div>

                    </div>
                    <p></p>
                </div>
                <div class="lineheightbg"></div>
                <div class="parmentaddress">
                    <h1>Support Person Details</h1>
                    <p class="myprofiletext">Support Person Name<span><?= $OperationModel->full_name; ?> </span></p>
                    <p class="myprofiletext">Support Person Email<span><?= $OperationModel->login_id; ?></span></p>
                    <p class="myprofiletext">Support Person Number<span><?php echo $OperationsProfile->phone; ?></span></p>
                </div>

                <a style="margin-top:15px;" href="<?= Url::home(true) ?>site/changepassword" data-target="#myModal" data-toggle="modal" class="btn btn-info">Reset password</a>


            </div>
        </div>

        <div class="col-md-2 col-sm-2 col-xs-12 common_profile_pic"style="margin-left: 10px;">
            <div class="myprofile supportperson mytop">
                <a href="#" class="thumbnail">
                    <img src="<?= !empty($model->profile_image) ? "../" . $model->profile_image : Url::home(true) . 'images/photo.jpg' ?>" alt="...">
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


