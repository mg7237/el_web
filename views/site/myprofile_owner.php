<?php
$this->title = 'PMS- My profile';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$proofsAll = Yii::$app->userdata->getAllProofs($model->owner_id);
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<style>
    .editbutright {
        width: 30%;
        padding: 2px 13px;
        margin-top: 15px;
    }
</style>
<!-- Modal signup -->
<div id="page-wrapper">

    <div class="row">
        <div class="col-md-9 col-sm-9 col-xs-offset-2">

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
                <div class="parmentaddress main_title">

                    <h1>Personal Details</h1></div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-6 text-right">
                <div>
                    <?php echo Html::a('Edit', Url::to('editowner'), ['class' => 'btn savechanges_submit editbutright']); ?>
                </div>
            </div>
        </div>
    </div>


    <div class="row">

        <div class="col-lg-5 col-sm-6 col-xs-12  col-xs-offset-2">

            <div class="myprofile supportperson mytop">
                <span>Name </span>
                <p><?= Yii::$app->userdata->getFullNameById($model->owner_id); ?></p>

            </div>

            <div class="lineheightbg"></div>
            <div class="parmentaddress">
                <h1>Contact Details</h1>
                <p class="myprofiletext">Email <span><?= Yii::$app->userdata->getUserEmailById($model->owner_id); ?></span></p>
                <p class="myprofiletext">Phone Number<span><?= $model->phone; ?></span></p>

            </div>
            <div class="lineheightbg"></div>
            <div class="parmentaddress">
                <h1>Permanent address</h1>
                <p class="myprofiletext">Address<span><?= $model->address_line_1; ?> , <?= $model->address_line_2; ?></span></p>
                <p class="myprofiletext">State<span><?= Yii::$app->userdata->getStateName($model->state); ?></span></p>
                <p class="myprofiletext">city<span><?= Yii::$app->userdata->getCityName($model->city); ?></span></p>
                <p class="myprofiletext">pincode<span><?= $model->pincode; ?></span></p>


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
                                    <img class= "preview" src="../<?= $value->proof_document_url; ?>" alt="">
                                    <p class="doc_title"><?php echo Yii::$app->userdata->getProofType($value->proof_type); ?></p><a class="removeIdentity" data-id="<?= $value->id ?>">Remove</a>
                                </div>
                                <?php
                            }
                        } else {
                            ?>
                            <div class="col-md-6 col-sm-6">
                                <img class="preview" src="<?= Url::home(true) . 'images/doumentupload.jpg' ?>">
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <img class="preview" src="<?= Url::home(true) . 'images/doumentupload.jpg' ?>">
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="lineheightbg"></div>
            <div class="parmentaddress">
                <h1>My emergency contact detail </h1>
                <p class="myprofiletext">Name<span><?= $model->emer_contact; ?></span></p>
                <p class="myprofiletext">Email<span><?= $model->emergency_contact_email; ?></span></p>
                <p class="myprofiletext">Contact Number<span><?= $model->emergency_contact_number; ?></span></p>

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
                    <p> 
                        <img src="<?= !empty($model->cancelled_check) ? "../" . $model->cancelled_check : Url::home(true) . 'images/doumentupload.jpg' ?>" alt="...">

                    </p>
                    <p>Cancelled cheque </p>
                </div>
            </div>
            <div class="parmentaddress">

                <?php if ($model->sales_id != '' && $model->sales_id != '0') {
                    ?>
                    <div class="col-lg-12 col-sm-12">
                        <div class="lineheightbg"></div>
                        <div class="">
                            <h1>Support person detail</h1>
                            <p class="myprofiletext">Name<span><?= Yii::$app->userdata->getFullNameById($model->operation_id) ?></span></p>
                            <p class="myprofiletext">Phone number<span><?= Yii::$app->userdata->getPhoneNumberById($model->operation_id, '7') ?></span></p>
                            <p class="myprofiletext">Email<span><?= Yii::$app->userdata->getLoginIdById($model->operation_id) ?></span></p>
                        </div>
                    </div>
                <?php }
                ?>
            </div>
            <div class="parmentaddress"></div>


            <a style="margin-top:15px;" href="<?= Url::home(true) ?>site/changepassword" data-target="#myModal" data-toggle="modal" class="btn btn-info">Reset password</a>


        </div>

        <div class="col-md-2 col-sm-2 common_profile_pic"style="margin-left: 10px;">
            <div class="myprofile supportperson mytop">
                <a href="#" class="thumbnail">
                    <img src="<?= !empty($model->profile_image) ? "../" . $model->profile_image : Url::home(true) . 'images/photo.jpg' ?>" alt="...">
                </a>
            </div>
        </div>
    </div>



</div>

<div class="modal fade modal1" id="myModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">

        </div>
    </div>



</div>
<script>
    $(document).on("click", ".cancel_confirm_1", function (e) {
        e.preventDefault();
        var element = $(this);
        var confirm_var = confirm('Are you sure you want to cancel ?');
        if (confirm_var == true) {
            $(".closemodal").click();
        } else {

        }
    });
</script>


