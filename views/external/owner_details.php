<?php
$this->title = 'Details ';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

// echo "<pre>";
// print_r($model);
// die;
?>
<style>
    .uploadbox img {
        width: 96%;
        height: 200px;
    }

    .removeLocal {
        position: absolute;
        padding-top: 14%;
        bottom: 57px;
        vertical-align: middle;
        height: 84%;
        width: 95.5%;
        left: 1px;
        right: 0px;
        text-align: center;
        background-color: rgba(0,0,0,0.6);
        top: 1px;
        display: none;
        cursor: pointer;
        text-decoration: none !important;
    }

    .row.identity_image_preview div:hover > a {
        display: block;
    }

    .removeLocal::before {
        content: "\f014";
        font-family: FontAwesome;
        font-size: 40px;
        color: white;
    }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<div id="page-wrapper">
    <div class="row">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#home">Owner information </a></li>
            <li><a data-toggle="tab" href="#menu1">Lead information</a></li>
            <li><a data-toggle="tab" href="#lead-assignment">Lead assignment</a></li>
        </ul>

        <div class="tab-content">
            <div id="home" class="tab-pane fade in active">
                <?php if (Yii::$app->session->hasFlash('success')) { ?>
                    <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                        <?= Yii::$app->session->getFlash('success'); ?>
                    </div>
                <?php } ?>
                <?php if (Yii::$app->session->hasFlash('error')) { ?>
                    <div class="alert alert-danger fade in alert-dismissable" style="margin-top:18px;">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                        <?= Yii::$app->session->getFlash('error'); ?>
                    </div>
                <?php } ?>
                <?php if (Yii::$app->session->hasFlash('leadssuccess')) { ?>
                    <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                        <?= Yii::$app->session->getFlash('leadssuccess'); ?>
                    </div>
                <?php } ?>
                <div class="col-lg-7 col-sm-7 col-xs-offset-1">
                    <h3>Owner information </h3>
                </div>
                <?php $form = ActiveForm::begin(['id' => 'users_agreement', 'options' => ['enctype' => 'multipart/form-data']]); ?>
                <?php //echo $form->errorSummary($model); ?>
                <?php //echo $form->errorSummary($modelUser); ?>

                <div class="row">

                    <div class="col-lg-7 col-sm-7 col-xs-offset-1">
                        <div class="myprofile">
                            <span>Name</span>   
                            <?php $fullname = Yii::$app->userdata->getFullNameById($model->owner_id); ?>
                            <div class="parmentaddress resetbox">
                                <?= $form->field($modelLeads, 'full_name')->textInput(['maxlength' => true, 'placeholder' => 'Full Name*', 'value' => $fullname]) ?>
                            </div>
                        </div>
                        <div class="lineheightbg"></div>
                        <div class="parmentaddress">
                            <h1>Permanent address</h1>
                            <div class="parmentaddress resetbox">
                                <?= $form->field($model, 'address_line_1')->textInput(['maxlength' => true, 'placeholder' => 'Address Line 1*']) ?>
                            </div>

                            <div class="parmentaddress resetbox">
                                <?= $form->field($model, 'address_line_2')->textInput(['maxlength' => true, 'placeholder' => 'Address Line 2*']) ?>
                            </div>

                            <div class="parmentaddress resetbox">
                                <?= $form->field($model, 'city_name')->textInput(['maxlength' => true, 'placeholder' => 'City Name*', 'class' => 'form-control']); ?>
                            </div>

                            <div class="parmentaddress resetbox">

                                <?php
                                $states = \app\models\States::find()->all();
                                $stateData = ArrayHelper::map($states, 'code', 'name');
                                ?>
                                <?= $form->field($model, 'state')->dropDownList($stateData, ['prompt' => 'Select State', 'url' => \Yii::$app->getUrlManager()->createUrl('users/getcity')]); ?>
                            </div>

                            <div class="parmentaddress resetbox">

                                <?= $form->field($model, 'pincode')->textInput(['maxlength' => 15, 'placeholder' => 'Pincode*', 'class' => 'form-control ']) ?>

                            </div>
                            <div class="parmentaddress resetbox">
                                <?= $form->field($modelUser, 'login_id')->textInput(['maxlength' => true, 'placeholder' => 'Email*']) ?>
                            </div>
                            <div class="parmentaddress resetbox">

                                <?= $form->field($model, 'phone')->textInput(['maxlength' => 15, 'placeholder' => 'Phone Number*', 'class' => 'form-control']) ?>

                            </div>

                        </div>
                        <div class="lineheightbg"> </div>
                        <p style="height:20px;"></p>
                        <div class="changepassword">
                            <h1 class="advisortext">Identity and Address Proof Documents</h1>

                            <div class="row">
                                <div class="uploadbox">
                                    <div class="col-md-6 col-sm-6">
                                        <select class="selectpicker IDproof">
                                            <option value="">Select ID Proof</option>
                                            <?php foreach ($proofType as $type) {
                                                ?>
                                                <option value="<?= $type->id ?>"><?= $type->name ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-sm-6" id="IDproof">
                                    </div>
                                    <div class="clearfix"></div>


                                    <div class="row identity_image_preview">
                                        <?php
                                        foreach ($address_proofs as $key => $value) {
                                            ?>
                                            <div class="col-md-6 col-sm-6">
                                                <img class= "preview" alt="" src="../<?= $value->proof_document_url ?>"><p class="identityp"><?php echo Yii::$app->userdata->getProofType($value->proof_type); ?></p><a class="removeIdentityowner" data-id="<?= $value->id ?>">Remove</a>
                                            </div>
                                            <?php
                                        }
                                        ?>

                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="lineheightbg"></div>

                        <div class="parmentaddress">
                            <h1>Emergency Contact Detail</h1>

                            <div class="parmentaddress resetbox">
                                <?= $form->field($model, 'emer_contact')->textInput(['maxlength' => true, 'placeholder' => 'Name', 'class' => 'form-control isCharacter'])->label('Contact Name') ?>

                            </div>
                            <div class="parmentaddress resetbox">
                                <?= $form->field($model, 'emergency_contact_email')->textInput(['maxlength' => true, 'placeholder' => 'Email']) ?>

                            </div>
                            <div class="parmentaddress resetbox">
                                <?= $form->field($model, 'emergency_contact_number')->textInput(['maxlength' => 15, 'placeholder' => 'Number', 'class' => 'form-control']) ?>
                            </div>
                        </div>
                        <div class="lineheightbg"></div>

                        <div class="changepassword">
                            <h1>Bank details</h1>
                            <?= $form->field($model, 'account_holder_name')->textInput(['maxlength' => true, 'placeholder' => 'Account Holder Name*', 'class' => 'form-control isCharacter']) ?>

                            <?= $form->field($model, 'bank_name')->textInput(['maxlength' => true, 'placeholder' => 'Bank Name*', 'class' => 'form-control isCharacter']) ?>

                            <?= $form->field($model, 'bank_branchname')->textInput(['maxlength' => true, 'placeholder' => 'Branch Name*']) ?>
                            <?= $form->field($model, 'bank_ifcs')->textInput(['maxlength' => true, 'placeholder' => 'IFSC Code*'])->label('IFSC Code') ?>
                            <?= $form->field($model, 'bank_account_number')->textInput(['maxlength' => true, 'placeholder' => 'Account Number*', 'class' => 'form-control']) ?>
                            <?= $form->field($model, 'pan_number')->textInput(['maxlength' => true, 'placeholder' => 'Pan Number*', 'class' => 'form-control']) ?>


                        </div>
                        <div class="row">
                            <div class="chancelall">

                                <?= $form->field($model, 'cancelled_check')->fileInput(['accept' => 'image/*']) ?><div class="questionpopup_edit_p cancelled_check_new">
                                    <a href="javascript:;" data-toggle="popover" title="" data-content='<p class="circlebox1">The account holder name on cheque should match property owner name.' data-html="true"  data-placement="left">?</a>
                                </div>

                            </div>
                            <div class="uploadbox <?= !empty($model->cancelled_check) ? 'canceledCheck' : '' ?>">

                                <div class="col-md-6 col-sm-6">

                                    <img  id="cancelled" src="<?= !empty($model->cancelled_check) ? "../" . $model->cancelled_check : Url::home(true) . 'images/doumentupload.jpg' ?>" alt="..." >
                                    <?= !empty($model->cancelled_check) ? '<a class="removeCancelledCheck" attr-id="' . $model->owner_id . '">Remove</a>' : '' ?>
                                    <p class="identityp">Cancelled Cheque</p>
                                </div>
                            </div>
                            <p></p>
                        </div>
                        <div class="lineheightbg"></div>
                    </div>
                    <div class="col-xs-2 col-md-2 rightpfrolietop">
                        <a href="" class="thumbnail" onclick="apply_image('ownerprofile-profile_image'); return false;">
                            <img id="blah" src="<?= !empty($model->profile_image) ? "../" . $model->profile_image : Url::home(true) . 'images/photo.jpg' ?>" alt="...">
                        </a>
                        <?= $form->field($model, 'profile_image')->fileInput(['accept' => 'image/*', 'style' => 'display:none'])->label(false) ?>
                    </div>
                </div>

                <input type="hidden" id="_csrf1" class="form-control name1" value="<?= Yii::$app->request->getCsrfToken() ?>" name="_csrf">
                <div class="row col-lg-11 col-sm-11 pull-right ownerbutton" style="padding-top:20px;">

                    <?= Html::submitButton('Save', ['class' => 'btn savechanges_submit_contractaction', 'onclick' => 'saveform();']) ?>

                    <a style="color:#FFF" class="btn savechanges_submit ancbutton cancel_confirm_2" href="<?= Url::home(true); ?>external/owners" type="button">Cancel</a>


                    <?php
                    if ($modelUser->status == 0 || $modelUser->status == 2) {
                        ?>
                        <button class="btn savechanges_submit_contractaction activate_owner_link resposiveleft" data-id="<?= $model->owner_id ?>">Activate property owner</button>
                        <?php
                    } else {
                        ?>
                        <button type="button" class="btn savechanges_submit_contractaction deactivate_owner_link" data-id="<?= $model->owner_id ?>">De-activate property owner</button>
                        <?php
                    }
                    ?>


                </div>
                <?php ActiveForm::end(); ?>



            </div>


            <div id="menu1" class="tab-pane fade">
                <div class="col-lg-9 col-sm-9 col-xs-offset-1">
                    <h3>Lead information</h3>
                </div>
                <div class="row">
                    <div class="col-lg-9 col-sm-9 col-xs-offset-1">
                        <div class="advisorbox">


                            <?php $formLeads = ActiveForm::begin(['id' => 'leads_agreement', 'options' => ['enctype' => 'multipart/form-data']]); ?>

                            <?php
                            $regions = \app\models\RefStatus::find()->all();
                            $cityData = ArrayHelper::map($regions, 'id', 'name');
                            //echo "<pre>";print_r($modelLeads);echo "</pre>";die;
                            if ($modelLeads->ref_status != 0) {
                                $stat = $modelLeads->ref_status;
                            } else {
                                $stat = 6;
                            }
                            ?>
                            <?= $formLeads->field($modelLeads, 'ref_status')->dropDownList($cityData, array('options' => array($stat => array('selected' => true))))->label('Lead Status');
                            ?>
                            <?= $formLeads->field($modelLeads, 'follow_up_date_time')->textInput(['maxlength' => true, 'placeholder' => 'DD-MMM-YYYY HH:MM', 'class' => 'datetimepickerss form-control']) ?>


                            <div class="row">
                                <br />
                                <div class="col-lg-6 col-sm-6">
                                    <?= Html::submitButton('Save', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn savechanges_submit_contractaction', 'id' => 'ownerCreateBtn', 'style' => 'display:none']) ?>

                                </div>
                                <!--  <div class="col-lg-6">
                                      <a style="color:#FFF" class="btn savechanges_submit" href="<?//= Url::home(true); ?>sales/applicants" type="button">Cancel</a>

                                 </div>  -->
                            </div>

                            <?php ActiveForm::end(); ?>
                            <div class="notexbox">

                                <?php
                                $formLeads = ActiveForm::begin([
                                            'id' => 'comment_form',
                                            'options' => ['enctype' => 'multipart/form-data'],
                                            'enableAjaxValidation' => true,
                                            'action' => 'addcomment1',
                                ]);
                                ?>

                                <div class="row">

                                    <div class="col-md-1 note_c">
                                        <p>Notes</p>
                                    </div>
                                    <div class="col-md-11 col-sm-11 comment_c">
                                        <div class="col-md-10 col-sm-10 comment_c">
                                            <?= $form->field($modelComments, 'description')->textarea(['rows' => '1'])->label(false) ?>
                                            <?= $form->field($modelComments, 'user_id')->hiddenInput(['value' => $model->owner_id])->label(false) ?>

                                        </div>

                                        <div class="col-md-2 col-sm-2 comment_c">
                                            <?= Html::Button('Add Comment', ['id' => 'add_comment', 'class' => 'btn btn-info replybut']) ?>

                                        </div>
                                    </div>
                                </div>

                                <?php ActiveForm::end(); ?>

                                <div class="lorembox" id="dataComments">
                                    <?php
                                    if ($comments) {
                                        foreach ($comments as $comment) {
                                            ?>
                                            <p><?= $comment->description; ?>,
                                                <span>  <?= Yii::$app->userdata->getFullNameById($comment->created_by); ?> on  <?= date('d-M-Y', strtotime($comment->created_date)); ?> At  <?= date('h:i A', strtotime($comment->created_date)); ?></span>
                                            </p>
                                            <?php
                                        }
                                    } else {
                                        echo "<p> No comments</p>";
                                    }
                                    ?>


                                </div>

                            </div>
                            <div class="pricinginner">
                                <p>Property List</p>
                                <?php foreach ($properties as $key => $property) { ?>
                                    <div class="col-lg-6 imgrightbox">
                                        <div class="imgsliderbox">
                                            <div class="imgleftbox">
                                                <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                                                    <!-- Wrapper for slides -->
                                                    <div class="carousel-inner" role="listbox">
                                                        <div class="item active">
                                                            <?php $propertiesImages = \app\models\PropertyImages::find()->where(['property_id' => $property->id])->one();
                                                            ?>
                                                            <img  src="<?php echo ($propertiesImages) ? "../" . $propertiesImages->image_url : Url::home(true) . 'images/gallery_imgone.jpg'; ?>" class="mCS_img_loaded" alt="image"> </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="textrightbox">
                                                <h5> <?= $property->property_name; ?></h5>
                                                <p><?= Yii::$app->userdata->getPropertyAddrres($property->id) ?></p>
                                                <div class="col-lg-12 rentboxnew">




                                                </div>
                                                <p class="text-right" style="padding-right:9px; margin-bottom:0px;">
                                                    <?php
                                                    if (!Yii::$app->userdata->getPropertyBookedStatus($property->id)) {
                                                        ?>
                                                                                     <!-- <a href="<?//= Url::home(true) ?>external/editproperty?id=<?//= $property->id ; ?>" onclick="$('body').on('hidden.bs.modal', '.modal', function () { $(this).removeData('bs.modal'); });"  data-toggle="modal" data-target="#myModal_edit">Edit </a> -->
                                                        <a href="<?= Url::home(true) ?>external/editproperty?id=<?= $property->id; ?>">Edit </a>
                                                    <?php } else {
                                                        ?>
                                                        <a href="<?= Url::home(true) ?>external/editproperty?id=<?= $property->id; ?>">Edit </a>
                                                    <?php }
                                                    ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="clearfix"></div>
                            <!----------add property---------------------->
                            <a href="<?= Url::home(true) ?>external/addproperty?id=<?= base64_encode($model->owner_id); ?>" >Add Property</a>
                            <p></p>


                            <div class="row">
                                <br />
                                <div class="col-lg-6 col-sm-6">
                                    <a style="color:#FFF" class="btn savechanges_submit_contractaction submit_form_anchor" data-value="leads_agreement" href="#" >Save</a>

                                </div>
                                <div class="col-lg-6 col-sm-6">
                                    <a style="color:#FFF" class="btn savechanges_submit cancel_confirm_2" href="<?= Url::home(true); ?>sales/applicants" type="button">Cancel</a>

                                </div>
                            </div>
                        </div>



                        <!--------------------------------------->
                        <div class="modal fade" id="myModal_edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog modalbg modalbgpopup" role="document">
                                <div class="modal-content">
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>



                </div>

                <!----------------------------------------->


            </div>

            <div id="lead-assignment" class="tab-pane fade">
                <div class="col-lg-9 col-sm-9 col-xs-offset-1">
                    <h3>Lead assignment</h3>
                </div>
                <div class="row">
                    <div class="col-lg-9 col-sm-9 col-xs-offset-1">
                        <div class="advisorbox">

                            <?php $formLeads = ActiveForm::begin(['id' => 'owner_lead_assignment', 'action' => 'leadassignmentowner', 'method' => 'post', 'options' => ['enctype' => 'multipart/form-data']]); ?>

                            <input type="hidden" value="1" name="lead_assignment" />
                            <?php
                            //print_r($modelLeads);
                            //$userModel = app\models\User::findOne(['login_id' => $modelLeads->email_id]);
                            $ownerProfileModel = app\models\OwnerProfile::findOne(['owner_id' => $modelUser->id]);
                            $currentRoleCode = NULL;
                            $currentRoleValue = NULL;
                            //$currentBranchCode = $ownerProfileModel->branch_code;

                            if (Yii::$app->user->identity->user_type == 6) {
                                $currentSaleProfile = app\models\SalesProfile::findOne(['sale_id' => Yii::$app->user->id]);
                                $currentRoleCode = @$currentSaleProfile->role_code;
                                $currentRoleValue = @$currentSaleProfile->role_value;
                            } else if (Yii::$app->user->identity->user_type == 7) {
                                $currentOperationProfile = app\models\OperationsProfile::findOne(['operations_id' => Yii::$app->user->id]);
                                $currentRoleCode = @$currentOperationProfile->role_code;
                                $currentRoleValue = @$currentOperationProfile->role_value;
                            }

                            $ownerId = $ownerProfileModel->owner_id;
                            $ownerOperation = $ownerProfileModel->operation_id;
                            $ownerSale = $ownerProfileModel->sales_id;

                            $operationRoleCode = null;
                            $operationRoleValue = null;

                            $saleRoleCode = null;
                            $saleRoleValue = null;

                            if (!empty($ownerOperation)) {
                                $operationProfileModel = app\models\OperationsProfile::findOne(['operations_id' => $ownerOperation]);
                                $operationRoleCode = $operationProfileModel->role_code;
                                $operationRoleValue = $operationProfileModel->role_value;
                            }

                            if (!empty($ownerSale)) {
                                $saleProfileModel = app\models\SalesProfile::findOne(['sale_id' => $ownerSale]);
                                $saleRoleCode = $saleProfileModel->role_code;
                                $saleRoleValue = $saleProfileModel->role_value;
                            }
                            ?>
                            <?php
                            $regions = \app\models\RefStatus::find()->all();
                            $cityData = ArrayHelper::map($regions, 'id', 'name');
                            //echo "<pre>";print_r($modelLeads);echo "</pre>";die;
                            if ($modelLeads->ref_status != 0) {
                                $stat = $modelLeads->ref_status;
                            } else {
                                $stat = 6;
                            }
                            ?>

                            <input type="hidden" name="operation_role_code" value="<?= $operationRoleCode ?>" />
                            <input type="hidden" name="operation_role_value" value="<?= $operationRoleValue ?>" />
                            <input type="hidden" name="sale_role_code" value="<?= $saleRoleCode ?>" />
                            <input type="hidden" name="sale_role_value" value="<?= $saleRoleValue ?>" />
                            <input type="hidden" name="redirect" value="<?= $_GET['id'] ?>" />

                            <input type="hidden" name="owner_id" value="<?= $ownerId ?>" />

                            <?php //print_r($modelLeads); //echo $formLeads->field($modelLeads, 'ref_status')->dropDownList($cityData, array('options' => array($stat => array('selected' => true))))->label('Lead Status');  ?>

                            <div class="form-group field-leadsowner-ref_status has-success" style="display: none;">
                                <label class="control-label" for="leadsowner-ref_status">Branch Assigned To</label>
                                <input type="text" readonly="readonly" name="assigned_to" value="<?= (!empty($modelLeads->branch_code)) ? $modelLeads->branch_code : ""; ?>" placeholder="<?= (!empty($modelLeads->branch_code)) ? $modelLeads->branch_code : "N/A"; ?>" class="form-control" />
                                <div class="help-block"></div>
                            </div><br />

                            <label class="control-label" for="assigned_level_1">
                                <?php
                                $assignLevelCheck = ($operationRoleCode == 'OPCTMG' || $saleRoleCode == 'SLCTMG') ? true : false;
                                $assignLevelCheck2 = ($operationRoleCode == 'OPBRMG' && $saleRoleCode == 'SLBRMG') ? true : false;
                                $assignLevelCheck3 = ($operationRoleCode == 'OPBRMG' && $saleRoleCode == 'SLEXE') ? true : false;
                                $assignLevelCheck4 = ($operationRoleCode == 'OPEXE' && $saleRoleCode == 'SLBRMG') ? true : false;
                                //$assignLevelCheck6 = (($currentRoleCode == 'OPBRMG' || $currentRoleCode == 'SLBRMG') && ($operationRoleCode != 'OPCTMG' || $saleRoleCode != 'SLCTMG')) ? true : false ;

                                $assignLevelCheck5 = ($operationRoleValue == 'ELHQ') ? true : false;
                                ?>

                                <?php if ($currentRoleCode != 'OPEXE' && $currentRoleCode != 'SLEXE') { ?>
                                    <input <?= ($assignLevelCheck) ? 'checked' : '' ?> type="radio" name="assigned_level" value="1" class="assigned_level" id="assigned_level_1" />
                                    City Level &nbsp;&nbsp;</label>
                            <?php } ?>

                            <label class="control-label" for="assigned_level_2">
                                <input <?= ($assignLevelCheck2 || $assignLevelCheck3 || $assignLevelCheck4/* || $assignLevelCheck6 */) ? 'checked' : '' ?> type="radio" name="assigned_level" value="2" class="assigned_level" id="assigned_level_2" />
                                Branch Level &nbsp;&nbsp;</label>

                            <?php if (($currentRoleCode == 'OPCTMG' || $currentRoleCode == 'SLCTMG' || $currentRoleCode == 'OPSTMG' || $currentRoleCode == 'SLSTMG') || $currentRoleValue == 'ELHQ') { ?>
                                <label class="control-label" for="assigned_level_3">
                                    <input <?= ($assignLevelCheck5) ? 'checked' : ''; ?> type="radio" name="assigned_level" value="3" class="assigned_level" id="assigned_level_3" />
                                    ELHQ Level &nbsp;&nbsp;</label>
                            <?php } ?>

                            <br /><br />

                            <div class="form-group field-leadsowner-ref_status has-success">
                                <label class="control-label" for="leadsowner-ref_status">City</label>
                                <select class="form-control" name="assigned_city" id="assigned_city">
                                    <option value="">Select City</option>
                                    <?php
                                    $userType = Yii::$app->user->identity->user_type;
                                    $userId = Yii::$app->user->identity->id;
                                    if ($userType == 7) {
                                        $opsModel = \app\models\OperationsProfile::findOne(['operations_id' => $userId]);
                                        if ($opsModel->role_code == 'OPEXE' && $opsModel->role_value == 'ELHQ') {
                                            $cities = \app\models\Cities::find()->where(['status' => 1])->all();
                                        } else if ($opsModel->role_code == 'OPSTMG') {
                                            $cities = \app\models\Cities::find()->where(['status' => 1, 'state_code' => $opsModel->role_value])->all();
                                        } else if ($opsModel->role_code == 'OPCTMG') {
                                            $cities = \app\models\Cities::find()->where(['status' => 1, 'code' => $opsModel->role_value])->all();
                                        } else if ($opsModel->role_code == 'OPBRMG') {
                                            $branchModel = \app\models\Branches::findOne(['branch_code' => $opsModel->role_value]);
                                            $cities = \app\models\Cities::find()->where(['status' => 1, 'code' => $branchModel->city_code])->all();
                                        } else if ($opsModel->role_code == 'OPEXE') {
                                            $branchModel = \app\models\Branches::findOne(['branch_code' => $opsModel->role_value]);
                                            $cities = \app\models\Cities::find()->where(['status' => 1, 'code' => $branchModel->city_code])->all();
                                        }
                                    } else if ($userType == 6) {
                                        $saleModel = \app\models\SalesProfile::findOne(['sale_id' => $userId]);
                                        if ($saleModel->role_code == 'SLEXE' && $saleModel->role_value == 'ELHQ') {
                                            $cities = \app\models\Cities::find()->where(['status' => 1])->all();
                                        } else if ($saleModel->role_code == 'SLSTMG') {
                                            $cities = \app\models\Cities::find()->where(['status' => 1, 'state_code' => $saleModel->role_value])->all();
                                        } else if ($saleModel->role_code == 'SLCTMG') {
                                            $cities = \app\models\Cities::find()->where(['status' => 1, 'code' => $saleModel->role_value])->all();
                                        } else if ($saleModel->role_code == 'SLBRMG') {
                                            $branchModel = \app\models\Branches::findOne(['branch_code' => $saleModel->role_value]);
                                            $cities = \app\models\Cities::find()->where(['status' => 1, 'code' => $branchModel->city_code])->all();
                                        } else if ($saleModel->role_code == 'SLEXE') {
                                            $branchModel = \app\models\Branches::findOne(['branch_code' => $saleModel->role_value]);
                                            $cities = \app\models\Cities::find()->where(['status' => 1, 'code' => $branchModel->city_code])->all();
                                        }
                                    }

                                    foreach ($cities as $city) {
                                        //$citiesModel = \app\models\Cities::find(['code' => $modelLeads->lead_city])->one();
                                        echo $modelLeads->lead_city;
                                        ?>

                                        <option <?= ($modelLeads->lead_city == $city->code) ? 'selected' : '' ?> value="<?= $city->code; ?>"><?= ($city->city_name); ?></option>

                                    <?php }
                                    ?>

                                </select>
                                <div class="help-block"></div>
                            </div>

                            <div class="form-group field-leadsowner-ref_status has-success">
                                <label class="control-label" for="leadsowner-ref_status">Branch</label>
                                <select class="form-control" name="assigned_branch" id="assigned_branch">
                                    <option value="">Select Branch</option>
                                    <?php
                                    if ($userType == 7) {
                                        $opsModel = \app\models\OperationsProfile::findOne(['operations_id' => $userId]);
                                        if ($opsModel->role_code == 'OPEXE' && $opsModel->role_value == 'ELHQ') {
                                            $branches = \app\models\Branches::find()->where('branch_code != :branch_code', ['branch_code' => 'NA'])->all();
                                        } else if ($opsModel->role_code == 'OPSTMG') {
                                            $branches = \app\models\Branches::find()->where('branch_code != :branch_code AND state_code = :state_code', ['branch_code' => 'NA', 'state_code' => $opsModel->role_value])->all();
                                        } else if ($opsModel->role_code == 'OPCTMG') {
                                            $branches = \app\models\Branches::find()->where('branch_code != :branch_code AND city_code = :city_code', ['branch_code' => 'NA', 'city_code' => $opsModel->role_value])->all();
                                        } else if ($opsModel->role_code == 'OPBRMG') {
                                            $branches = \app\models\Branches::find()->where('branch_code = :branch_code', ['branch_code' => $opsModel->role_value])->all();
                                        } else if ($opsModel->role_code == 'OPEXE') {
                                            $branches = \app\models\Branches::find()->where('branch_code = :branch_code', ['branch_code' => $opsModel->role_value])->all();
                                        }
                                    } else if ($userType == 6) {
                                        $saleModel = \app\models\SalesProfile::findOne(['sale_id' => $userId]);
                                        if ($saleModel->role_code == 'SLEXE' && $saleModel->role_value == 'ELHQ') {
                                            $branches = \app\models\Branches::find()->where('branch_code != :branch_code', ['branch_code' => 'NA'])->all();
                                        } else if ($saleModel->role_code == 'SLSTMG') {
                                            $branches = \app\models\Branches::find()->where('branch_code != :branch_code AND state_code = :state_code', ['branch_code' => 'NA', 'state_code' => $saleModel->role_value])->all();
                                        } else if ($saleModel->role_code == 'SLCTMG') {
                                            $branches = \app\models\Branches::find()->where('branch_code != :branch_code AND city_code = :city_code', ['branch_code' => 'NA', 'city_code' => $saleModel->role_value])->all();
                                        } else if ($saleModel->role_code == 'SLBRMG') {
                                            $branches = \app\models\Branches::find()->where('branch_code = :branch_code', ['branch_code' => $saleModel->role_value])->all();
                                        } else if ($saleModel->role_code == 'SLEXE') {
                                            $branches = \app\models\Branches::find()->where('branch_code = :branch_code', ['branch_code' => $saleModel->role_value])->all();
                                        }
                                    }

                                    foreach ($branches as $branch) {
                                        ?>

                                        <option <?= ($modelLeads->branch_code == $branch->branch_code) ? 'selected' : '' ?> value="<?= $branch->branch_code; ?>"><?= $branch->name . " [" . $branch->branch_code . "]"; ?></option>

                                    <?php }
                                    ?>
                                </select>
                                <div class="help-block"></div>
                            </div>

                            <div class="form-group field-leadsowner-ref_status has-success">
                                <label class="control-label" for="leadsowner-ref_status">Operation's Member</label>
                                <select class="form-control" name="assigned_operation" id="assigned_operation">
                                    <option value="">Select Operation</option>
                                    <?php if (!empty($ownerProfileModel->operation_id)) { ?>
                                        <option selected="selected" value="<?= $ownerProfileModel->operation_id; ?>"><?= (Yii::$app->userdata->getEmailById($ownerProfileModel->operation_id)) . " [" . Yii::$app->userdata->getRoleCodeByUserIdOps($ownerProfileModel->operation_id) . "] [" . Yii::$app->userdata->getRoleValueByUserIdOps($ownerProfileModel->operation_id) . "]"; ?></option>
                                    <?php } ?>
                                    <?php
                                    if (!empty($modelLeads->branch_code)) {
                                        $opUsers = app\models\OperationsProfile::find()->where(' role_value = "' . $modelLeads->branch_code . '" ')->all();
                                        foreach ($opUsers as $op) {
                                            ?>
                                            <option value="<?= $op->operations_id; ?>"><?= ($op->email) . " [" . $op->role_code . "] [" . $op->role_value . "]"; ?></option>
                                        <?php
                                        }
                                    }
                                    ?>
                                </select>
                                <div class="help-block"></div>
                            </div>

                            <div class="form-group field-leadsowner-ref_status has-success">
                                <label class="control-label" for="leadsowner-ref_status">Sale's Member</label>
                                <select class="form-control" name="assigned_sale" id="assigned_sale">
                                    <option value="">Select Sale</option>
                                    <?php if (!empty($ownerProfileModel->sales_id)) { ?>
                                        <option selected="selected" value="<?= $ownerProfileModel->sales_id; ?>"><?= (Yii::$app->userdata->getEmailById($ownerProfileModel->sales_id)) . " [" . Yii::$app->userdata->getRoleCodeByUserIdSales($ownerProfileModel->sales_id) . "] [" . Yii::$app->userdata->getRoleValueByUserIdSales($ownerProfileModel->sales_id) . "]"; ?></option>
                                    <?php } ?>
                                    <?php
                                    if (!empty($modelLeads->branch_code)) {
                                        $slUsers = app\models\SalesProfile::find()->where(' role_value = "' . $modelLeads->branch_code . '" ')->all();
                                        foreach ($slUsers as $sl) {
                                            ?>
                                            <option value="<?= $sl->sale_id; ?>"><?= ($sl->email) . " [" . $sl->role_code . "] [" . $sl->role_value . "]"; ?></option>
                                        <?php
                                        }
                                    }
                                    ?>
                                </select>
                                <div class="help-block"></div>
                            </div>

                            <br />

                            <div class="row">
                                <div class="col-lg-6 col-sm-6">
<?php echo Html::submitButton('Save', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn savechanges_submit_contractaction', 'id' => 'owner-lead-assign-btn', 'onclick' => 'return assignLead()']) ?>
                                </div>
                                <div class="col-lg-6 col-sm-6">
                                    <a style="color:#FFF" class="btn savechanges_submit cancel_confirm_2" href="<?= Url::home(true); ?>sales/applicants" type="button">Cancel</a>
                                </div>
                            </div>

<?php ActiveForm::end(); ?>

                        </div>



                        <!--------------------------------------->
                        <div class="modal fade" id="myModal_edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog modalbg modalbgpopup" role="document">
                                <div class="modal-content">
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
<!-- /.col-lg-12 -->
</div>
<div class="modal fade bs-modal-sm1" id="myModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-body">
                <div id="myTabContent" class="tab-content">
                    <div class="tab-pane fade active in" id="signin">
                        <fieldset>
                            <!-- Sign In Form -->
                            <!-- Text input-->
                            <div class="">
                                <div class="controls">
                                    <label> Search sales person </label>
                                    <input  id="tags" name="userid" type="text" class="form-control" placeholder="Search For Sales Person" class="input-medium" >

                                    <style>
                                        ul.ui-autocomplete {
                                            z-index: 1100;
                                        }
                                        .circlebox1 {
                                            text-align: left !important;
                                        }
                                    </style>
                                    <script>
                                        function assignLead() {
                                            var assignedLevel = $('input[name=assigned_level]:checked').val();
                                            var assignedOperation = $('#assigned_operation').val();
                                            var assignedSale = $('#assigned_sale').val();
                                            var assignedBranch = $('#assigned_branch').val();
                                            var assignedCity = $('#assigned_city').val();

                                            if (assignedLevel == '' || assignedLevel == null || assignedLevel == undefined) {
                                                alert('Please select "Level"');
                                                return false;
                                            }

                                            if (assignedLevel == 1) {
                                                if (assignedCity == '' || assignedCity == null || assignedCity == undefined) {
                                                    alert('Please select "City"');
                                                    return false;
                                                }

                                                if (assignedOperation == '' || assignedOperation == null || assignedOperation == undefined) {
                                                    alert('Please select "Operation member"');
                                                    return false;
                                                }

                                                if (assignedSale == '' || assignedSale == null || assignedSale == undefined) {
                                                    alert('Please select "Sale member"');
                                                    return false;
                                                }
                                            } else if (assignedLevel == 2) {
                                                if (assignedCity == '' || assignedCity == null || assignedCity == undefined) {
                                                    alert('Please select "City"');
                                                    return false;
                                                }

                                                if (assignedBranch == '' || assignedBranch == null || assignedBranch == undefined) {
                                                    alert('Please select "Branch"');
                                                    return false;
                                                }

                                                if (assignedOperation == '' || assignedOperation == null || assignedOperation == undefined) {
                                                    alert('Please select "Operation member"');
                                                    return false;
                                                }

                                                if (assignedSale == '' || assignedSale == null || assignedSale == undefined) {
                                                    alert('Please select "Sale member"');
                                                    return false;
                                                }
                                            } else if (assignedLevel == 3) {
                                                return true;
                                            }

                                            return true;
                                        }

                                        $(function () {
                                            $('#assigned_city').change(function () {
                                                var assignedCity = $(this).val();
                                                var assignedLevel = $('input[name=assigned_level]:checked').val();
                                                var url = null;
                                                if (assignedLevel == 1) {
                                                    $.ajax({
                                                        url: 'getopsbycitycode',
                                                        type: 'POST',
                                                        data: {city_code: assignedCity},
                                                        success: function (data) {
                                                            $('#assigned_operation').html(data);
                                                        },
                                                        error: function (data) {
                                                            alert('Something went wrong.');
                                                        }
                                                    });

                                                    $.ajax({
                                                        url: 'getsalebycitycode',
                                                        type: 'POST',
                                                        data: {city_code: assignedCity},
                                                        success: function (data) {
                                                            $('#assigned_sale').html(data);
                                                        },
                                                        error: function (data) {
                                                            alert('Something went wrong.');
                                                        }
                                                    });
                                                } else if (assignedLevel == 2) {
                                                    $.ajax({
                                                        url: 'getbranchbycitycode',
                                                        type: 'POST',
                                                        data: {city_code: assignedCity},
                                                        success: function (data) {
                                                            $('#assigned_branch').html(data);
                                                        },
                                                        error: function (data) {
                                                            alert('Something went wrong.');
                                                        }
                                                    });
                                                } else {
                                                    return;
                                                }
                                            });

                                            $('#assigned_branch').change(function () {
                                                var assignedBranch = $(this).val();
                                                var assignedLevel = $('input[name=assigned_level]:checked').val();
                                                var assignedCity = $('#assigned_city').val();
                                                $.ajax({
                                                    url: 'getopsbybranchcode',
                                                    type: 'POST',
                                                    data: {city_code: assignedCity, branch: assignedBranch},
                                                    success: function (data) {
                                                        $('#assigned_operation').html(data);
                                                    },
                                                    error: function (data) {
                                                        alert('Something went wrong.');
                                                    }
                                                });

                                                $.ajax({
                                                    url: 'getsalesbybranchcode',
                                                    type: 'POST',
                                                    data: {city_code: assignedCity, branch: assignedBranch},
                                                    success: function (data) {
                                                        $('#assigned_sale').html(data);
                                                    },
                                                    error: function (data) {
                                                        alert('Something went wrong.');
                                                    }
                                                });
                                            });

                                            $('.assigned_level').click(function () {
                                                var assignedLevel = $('input[name=assigned_level]:checked').val();

                                                $('#assigned_city').removeAttr('disabled');
                                                $('#assigned_branch').removeAttr('disabled');
                                                $('#assigned_operation').removeAttr('disabled');
                                                $('#assigned_sale').removeAttr('disabled');

                                                if (assignedLevel == 1) {
                                                    $('#assigned_city').removeProp('disabled');
                                                    $('#assigned_branch').prop('disabled', 'disabled');
                                                    $('#assigned_operation').removeProp('disabled');
                                                    $('#assigned_sale').removeProp('disabled');

                                                    $('#assigned_city').val('');
                                                    $('#assigned_branch').html('');
                                                    $('#assigned_operation').html('');
                                                    $('#assigned_sale').html('');
                                                }

                                                if (assignedLevel == 2) {
                                                    $('#assigned_branch').removeProp('disabled');
                                                    $('#assigned_city').removeProp('disabled');
                                                    $('#assigned_operation').removeProp('disabled');
                                                    $('#assigned_sale').removeProp('disabled');

                                                    $('#assigned_city').val('');
                                                    $('#assigned_branch').html('');
                                                    $('#assigned_operation').html('');
                                                    $('#assigned_sale').html('');
                                                }

                                                if (assignedLevel == 3) {
                                                    $('#assigned_branch').prop('disabled', 'disabled');
                                                    $('#assigned_city').prop('disabled', 'disabled');
                                                    $('#assigned_operation').prop('disabled', 'disabled');
                                                    $('#assigned_sale').prop('disabled', 'disabled');

                                                    $('#assigned_city').html('');
                                                    $('#assigned_branch').html('');
                                                    $('#assigned_operation').html('');
                                                    $('#assigned_sale').html('');
                                                }

//                                                var assignedCity = $(this).val();
//                                                var assignedLevel = $('input[name=assigned_level]:checked').val();
//                                                var url = null;
//                                                if (assignedLevel == 1) {
//                                                    $.ajax({
//                                                        url: 'getcitybycurrentrolecode',
//                                                        type: 'POST',
//                                                        data: {city_code: assignedCity},
//                                                        success: function (data) {
//                                                            $('#assigned_operation').html(data);
//                                                        },
//                                                        error: function (data) {
//                                                            alert('Something went wrong.');
//                                                        }
//                                                    });
//                                                }
                                            });

                                            $("#tags").autocomplete({
                                                source: "getsalesname",
                                                select: function (event, ui) {
                                                    event.preventDefault();
                                                    //	console.log(ui.item);
                                                    $("#sales_name").val(ui.item.label);
                                                    $("#ownerprofile-sales_id").val(ui.item.value);
                                                    $("#tags").val(ui.item.label);
                                                },
                                            });
                                        });

                                    </script>
                                </div>
                            </div>


                            <div class="">
                                <label class="control-label" for="signin"></label>
                                <center>
                                    <div class="controls">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </center>
                            </div>
                        </fieldset>
                    </div>


                </div>

            </div>
        </div>
    </div></div>

<script>
    var idArrayt = [];
    $(".removeIdentityowner").click(function () {

        var element = $(this);
        var id = $(this).attr('data-id');
        //alert(id);
        idArrayt.push({
            id
        });
        sessionStorage.setItem("set_id_t", JSON.stringify(idArrayt));
        element.closest('div').remove();
    });

    function saveform()
    {
        var sessiondata = sessionStorage.getItem("set_id_t");
        if (sessiondata) {
            deletedatat();
        }
    }

    function deletedatat() {
        var sessiondata = sessionStorage.getItem("set_id_t");
        //alert("fdsfdsf");
        var sessiondata_id = JSON.parse(sessiondata);
        //alert(sessiondata_id);
        // conole.log(sessiondata_id);
        if (sessiondata != null) {
            for (var i = 0; i < sessiondata_id.length; i++) {

                console.log(sessiondata_id);
                console.log(sessiondata_id[i].id);
                var sessiondata_data_id = sessiondata_id[i].id;
                var csrfToken1 = $("#_csrf1").val();
                //alert("hello");
                $.ajax({
                    url: "../external/removeidentityowner",
                    type: 'POST',
                    data: {'id': sessiondata_data_id, _csrf: csrfToken1},
                    success: function (data) {
                        if (data) {
                            element.closest('div').remove();
                        }
                    },
                    error: function (data) {
                        //    console.log(data.responseText);
                        alert(data.responseText);
                    }
                });
            }
        }
    }



    $(document).on('change', '.IDproof', function () {
        var count = $('#IDproof .box').length;
        $('.identity_box').hide();
        $('#IDproof').append('<div class=" identity_file_' + count + ' identity_box box col-md-12 col-sm-12"><input type="file" attr-class="identity_file_' + count + '" name="address_proof[' + $(this).val() + '][]" attr-type="' + $('.IDproof option:selected').text() + '" class="identity_files" accept="image/*">');
    });

    $(document).on('change', '.identity_files', function (e) {
        readurlIdentity(this);
    });

    $(document).on('click', '.removeLocal', function () {
        $('.' + $(this).attr('id')).remove();
        $(this).closest('div').remove();
    })

    function readurlIdentity(input) {
        if (input.files && input.files[0]) {
            var attrclass = $(input).attr('attr-class');
            var reader = new FileReader();

            reader.onload = function (e) {
                if (e.target.result == '') {
                    alert('Only images are allowed');
                    $('.IDproof').val('');
                    $('#IDproof div.' + attrclass).remove();
                    $('a#' + attrclass).closest('.col-md-6').remove();
                } else {
                    var image_check = check_image(e.target.result);
                    if (image_check == 'image') {
                        $('.identity_image_preview').append('<div class="col-md-6 col-sm-6"><img class= "preview" alt="" src="' + e.target.result + '"><a class="removeLocal" id="' + attrclass + '"></a><p class="identityp">' + $('.IDproof option:selected').text() + '</p></div>');
                        $('.IDproof').prop('selectedIndex', 0);
                        $('.identity_box').hide();
                    } else {
                        alert('Only images are allowed');
                        $('.IDproof').val('');
                        $('#IDproof div.' + attrclass).remove();
                        $('a#' + attrclass).closest('.col-md-6').remove();
                    }
                }
            }

            reader.readAsDataURL(input.files[0]);
        }
    }


    $(document).ready(function () {

        var assignedLevel = $('input[name=assigned_level]:checked').val();

        $('#assigned_city').removeAttr('disabled');
        $('#assigned_branch').removeAttr('disabled');
        $('#assigned_operation').removeAttr('disabled');
        $('#assigned_sale').removeAttr('disabled');

        if (assignedLevel == 1) {
            $('#assigned_city').removeProp('disabled');
            $('#assigned_branch').prop('disabled', 'disabled');
            $('#assigned_operation').removeProp('disabled');
            $('#assigned_sale').removeProp('disabled');
        }

        if (assignedLevel == 2) {
            $('#assigned_branch').removeProp('disabled');
            $('#assigned_city').removeProp('disabled');
            $('#assigned_operation').removeProp('disabled');
            $('#assigned_sale').removeProp('disabled');
        }

        if (assignedLevel == 3) {
            $('#assigned_branch').prop('disabled', 'disabled');
            $('#assigned_city').prop('disabled', 'disabled');
            $('#assigned_operation').prop('disabled', 'disabled');
            $('#assigned_sale').prop('disabled', 'disabled');
        }

        var form = $("#comment_form");
        $("#add_comment").click(function () {
            if ($('#comments-description').val() == '') {
                alert('Please enter comment.');
                $('#comments-description').focus();
                return false;

            }

            startLoader();
            $.ajax({
                type: "POST",
                url: form.attr("action"),
                data: $("#comment_form").serialize(), //only input
                success: function (response) {

                    if (response == 'done') {
                        $("#comment_form")[0].reset();
                        $('#dataComments').load(' #dataComments');
                        hideLoader();
                    } else {
                        hideLoader();
                        alert('Please try again');
                    }
                },
                error: function (data) {
                    hideLoader();
                    // alert(data.responseText);
                    alert('Failure')

                }
            });
        });
    });
    function apply_image(str) {
        $('#' + str).click();
        return false;
    }
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                var image_check = check_image(e.target.result);
                console.log(image_check);
                if (image_check == 'image') {
                    $('#blah').attr('src', e.target.result);
                } else {
                    alert("only images are allowed");
                }
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#ownerprofile-profile_image").change(function () {
        readURL(this);
    });
    function readURL1(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#passport').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#ownerprofile-address_proof").change(function () {
        readURL1(this);
    });
    function readURL2(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#dl').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#ownerprofile-identity_proof").change(function () {
        readURL2(this);
    });
    function readURL3(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#dle').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#ownerprofile-emer_contact_indentity").change(function () {
        readURL3(this);
    });
    function readURL4(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#passporte').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
    $('.deleteProperty').click(function () {
        startLoader();
        var data_id = $(this).attr('data-id');
        $.ajax({
            type: "POST",
            url: 'deleteProperty',
            data: 'id=' + data_id, //only input
            success: function (response) {
                hideLoader();
            },
            error: function (data) {
                hideLoader();
                // alert(data.responseText);
                alert('Failure')

            }
        });
    });
    // })
    $("#ownerprofile-emer_contact_address").change(function () {
        readURL4(this);
    });
    function readURL4(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#cancelled').attr('src', e.target.result);
                $('#cancelled').after('<a class="removeCancelledCheck" attr-id="">Remove</a>');
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#ownerprofile-cancelled_check").change(function () {
        readURL4(this);
    });

    $(document).on("click", ".cancel_confirm_2", function (e) {
        e.preventDefault();
        var element = $(this);
        var confirm_var = confirm('Are you sure you want to cancel ?');
        if (confirm_var == true) {
            window.history.back();

        }
    });
</script>
<style>
    .field-applicantprofile-emer_contact_address label, .field-applicantprofile-emer_contact_indentity label{
        display:none;
    }
</style>
<script type="text/javascript">
    // function readURL(input) {
    // if (input.files && input.files[0]) {
    // var reader = new FileReader();

    // reader.onload = function (e) {
    // $('#blah').attr('src', e.target.result);
    // }

    // reader.readAsDataURL(input.files[0]);
    // }
    // }
    function activatepropertyOwner(thiselement) {
        startLoader();
        $.ajax({
            type: "POST",
            url: 'activateowner',
            data: 'id=' + thiselement.attr('data-id'), //only input
            dataType: 'json',
            timeout: 15000,
            success: function (response) {
                hideLoader();
                if (response.success == 0) {
                    alert(response.msg);
                } else {
                    alert('Property Owner Activated Successfully');
                    location.reload();
                }
                hideLoader();
            },
            error: function (x, textStatus, m) {
                if (textStatus == "timeout") {
                    activatepropertyOwner(thiselement);
                    hideLoader();
                }
            }
        });
    }
    $(document).on('click', '.activate_owner_link', function () {
        var formdata = new FormData($('#users_agreement')[0]);
        var id = "<?= $id1 ?>";
        //alert(id);
        var thiselement = $(this);
        startLoader();
        $.ajax({
            type: "POST",
            url: 'ownersdetailsajax?id=' + id,
            data: formdata,
            processData: false,
            contentType: false,
            success: function (response) {
                var formdata = new FormData($('#leads_agreement')[0]);
                var formdata1 = new FormData($('#users_agreement')[0]);
                //alert(formdata1);
                $.ajax({
                    type: "POST",
                    url: 'ownersdetailsajax?id=' + id,
                    data: formdata, formdata1,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        activatepropertyOwner(thiselement);

                    },
                    error: function (data) {
                        hideLoader();
                        alert(data.responseText);
                    }
                })
            },
            error: function (data) {
                // hideLoader();
                alert('Some error occured');
            }
        })
        /*
         
         */
    });

    $(document).on('click', '.deactivate_owner_link', function () {
        startLoader();
        var thiselement = $(this);
        $.ajax({
            type: "POST",
            url: 'deactivateowner',
            data: 'id=' + $(this).attr('data-id'), //only input
            success: function (response) {
                if (response) {
                    thiselement.text('Activate Property Owner');
                    thiselement.addClass('activate_owner_link');
                    thiselement.removeClass('deactivate_owner_link');
                    hideLoader();
                } else {
                    hideLoader();
                    alert('Can\'t Deactivate Owner.Some Property Is Activated');
                }
            }
        });
    });
    $(document).on('click', '.submit_form_anchor', function (e) {
        e.preventDefault();
        $('#' + $(this).attr('data-value')).submit();
    })

</script>

