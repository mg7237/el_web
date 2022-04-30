<?php
$this->title = 'Details ';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/* echo "<pre>";
  print_r($model);
  die; */
?>
<style>
    .uploadbox img {
        width: 96%;
        height: 200px;
    }

    ul.time_suggestion {
        height: 119px;
        position: absolute;
        background-color: white;
        overflow-y: scroll;
        width: 90%;
        margin-top: 0px;
        /* display: inline-flex; */
        padding-left: 5px;
        z-index: 9999;
    }
    li.apply_time {
        display: inline-flex;
        width: 100%;
        padding-left: 42%;
        cursor: pointer;
    }

    li.apply_time:hover {
        background-color: grey;
    }

    span.close_time_picker {
        position: absolute;
        z-index: 10000;
        position: absolute;
        right: 22px;
        background: red;
        /* padding: 5px; */
        border-radius: 16px;
        width: 20px;
        height: 20px;
        color: white;
        cursor: pointer;
        margin-top: 9px;
        padding-left: 5px;
        padding-top: 1px;
    }

    .field-propertyvisits-visit_time{
        position: relative;
    }
    ul.nav.nav-tabs li a {
        background: none;
    }
    button#add_comment {
        margin-top: -19px;
        background: #347c17;
        border: 1px solid #347c17;
        padding: 4px 8px;
        color: #fff;
    }
    .modal-header {
        color: #34a506;
        font-size: 17px;
        padding: 0px 15px;
    }
    .propertyboxinner a:hover {
        color: #333;
    }

    /*    img#blah {
            margin-top: 48px;
        }
         
        .questionpopup_edit_p {
            z-index: 9999999;
            margin-left: 113px;
            margin-top: -55px;
            font-size: 16px;
        }*/
</style>
<script>
    window.selectedVal = 0;
</script>

<div id="page-wrapper">
    <div class="row">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#home">Applicant information </a></li>
            <li><a data-toggle="tab" href="#menu1">Lead information</a></li>
            <li><a data-toggle="tab" href="#lead-assignment">Lead assignment</a></li>
        </ul>

        <div class="tab-content">
            <div id="home" class="tab-pane fade in active">
                <div class="col-lg-7 col-sm-7">
                    <h4>Applicant information</h4>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <?php if (Yii::$app->session->hasFlash('success')) { ?>
                            <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                                <?= Yii::$app->session->getFlash('success'); ?>
                            </div>
                        <?php } ?>

                        <?php if (Yii::$app->session->hasFlash('leadssuccess')) { ?>
                            <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                                <?= Yii::$app->session->getFlash('leadssuccess'); ?>
                            </div>
                        <?php } ?>

                        <div class="row">  				
                            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
                            <div class="col-lg-7 col-sm-7">

                                <div class="myprofile supportperson mytop">
                                    <span>Name </span>
                                    <?php $fullname = Yii::$app->userdata->getFullNameById($model->applicant_id); ?>
                                    <?= $form->field($modelLeads, 'full_name')->textInput(['maxlength' => true, 'placeholder' => 'Full Name*', 'value' => $fullname]) ?>
                                </div>


                                <div class="parmentaddress">
                                    <div class="lineheightbg"></div>
                                    <h1>Contact detail </h1>

                                    <?= $form->field($model, 'email_id')->textInput(['maxlength' => true, 'placeholder' => 'Email*']) ?>

                                    <?= $form->field($model, 'phone')->textInput(['maxlength' => 15, 'placeholder' => 'Phone Number*', 'class' => 'form-control ']) ?>


                                </div>

                                <div class="lineheightbg"></div>
                                <div class="parmentaddress">
                                    <h1>Permanent address</h1>
                                    <?= $form->field($model, 'address_line_1')->textInput(['maxlength' => true, 'placeholder' => 'Address Line 1*']) ?>

                                    <?= $form->field($model, 'address_line_2')->textInput(['maxlength' => true, 'placeholder' => 'Adddress Line 2*']) ?>

                                    <?php
                                    $states = \app\models\States::find()->all();
                                    $stateData = ArrayHelper::map($states, 'code', 'name');
                                    ?>
                                    <?= $form->field($model, 'state')->dropDownList($stateData, ['prompt' => 'Select state*', 'url' => \Yii::$app->getUrlManager()->createUrl('users/getcity')]); ?>

                                    <?= $form->field($model, 'city_name')->textInput(['maxlength' => true, 'placeholder' => 'Applicant City *']) ?>


                                    <?= $form->field($model, 'pincode')->textInput(['maxlength' => 15, 'placeholder' => 'Pincode*', 'class' => 'form-control ']) ?>


                                    <div class="lineheightbg"></div>

                                    <div class="uploadbox">
                                        <div class="changepassword">
                                            <h1 class="advisortext">Identity and Address Proof Documents</h1></div></div>
                                    <div class="row">
                                        <div class="uploadbox">
                                            <div class="col-md-6">
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
                                            <div class="col-md-6" id="IDproof">
                                            </div>
                                            <div class="clearfix"></div>


                                            <div class="row identity_image_preview">
                                                <?php
                                                foreach ($address_proofs as $key => $value) {
                                                    ?>
                                                    <div class="col-md-6">
                                                        <img class= "preview" alt="" src="../<?= $value->proof_document_url ?>"><p class="identityp"><?php echo Yii::$app->userdata->getProofType($value->proof_type); ?></p><a class="removeIdentity" data-id="<?= $value->id ?>">Remove</a>
                                                    </div>
                                                    <?php
                                                }
                                                ?>

                                            </div>
                                        </div>
                                    </div>


                                </div>
                                <div class="parmentaddress">
                                    <div class="lineheightbg"></div>
                                    <p style=""></p>
                                    <h1>Emergency contact detail </h1>
                                    <?= $form->field($model, 'emergency_contact_name')->textInput(['maxlength' => true, 'placeholder' => 'Name'])->label('Contact Name') ?>

                                    <?= $form->field($model, 'emergency_contact_email')->textInput(['maxlength' => true, 'placeholder' => 'Email'])->label('Contact Email') ?>

                                    <?= $form->field($model, 'emergency_contact_number')->textInput(['maxlength' => 15, 'placeholder' => 'Number', 'class' => 'form-control '])->label('Contact Number') ?>
                                    <div class="uploadbox">
                                        <div class="col-md-6">
                                            <select class="selectpicker EMERProof">
                                                <option value="">Select ID Proof</option>
                                                <?php foreach ($proofType as $type) {
                                                    ?>
                                                    <option value="<?= $type->id ?>"><?= $type->name ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6" id="EMERProof">

                                        </div>
                                        <div class="clearfix"></div>
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
                                    <h1>Bank details</h1>
                                    <?= $form->field($model, 'account_holder_name')->textInput(['maxlength' => true, 'placeholder' => 'Account Holder Name*', 'class' => 'form-control isCharacter']) ?>

                                    <?= $form->field($model, 'bank_name')->textInput(['maxlength' => true, 'placeholder' => 'Bank Name*', 'class' => 'form-control isCharacter']) ?>

                                    <?= $form->field($model, 'bank_branchname')->textInput(['maxlength' => true, 'placeholder' => 'Bank Branch Name*', 'class' => 'form-control isCharacter']) ?>
                                    <?= $form->field($model, 'bank_ifcs')->textInput(['maxlength' => true, 'placeholder' => 'IFSC Code*'])->label('IFSC Code') ?>
                                    <div id="ifsc-error-box" style="color: #a94442; display: none;" class="help-block">Invalid IFSC code.</div>
                                    <?= $form->field($model, 'bank_account_number')->textInput(['maxlength' => true, 'placeholder' => 'Bank Account Number*', 'class' => 'form-control isNumber']) ?>
                                    <?= $form->field($model, 'pan_number')->textInput(['maxlength' => true, 'placeholder' => 'Pan Number*']) ?>

                                </div>

                                <div class="chancelall">
                                    <?= $form->field($model, 'cancelled_check')->fileInput(['accept' => 'image/*', 'onchange' => "readURL13(this);"])->label('Cancelled Cheque') ?>
                                    <div class="questionpopup_edit_p">
                                        <a class="questionpopup12" href="javascript:;" data-toggle="popover" title="" data-content='<p class="circlebox1">The account holder name on cheque should match property owner name.' data-html="true"  data-placement="left">?</a>
                                    </div>
                                    <div class="uploadbox <?= !empty($model->cancelled_check) ? 'canceledCheck' : '' ?>">

                                        <div class="col-md-6 col-sm-6">
                                            <img  id="blah" src="<?= !empty($model->cancelled_check) ? "../" . $model->cancelled_check : Url::home(true) . 'images/doumentupload.jpg' ?>" alt="..." >
                                            <?= !empty($model->cancelled_check) ? '<a class="removeCancelledCheck" attr-id="' . $model->applicant_id . '"></a>' : '' ?>
                                            <p class="identityp">Cancelled Cheque</p>

                                        </div>	
                                    </div>
                                </div>







                                <style>
                                    .questionpopup {
                                        position: relative;
                                    }
                                    .questionpopup11 {
                                        position: relative;
                                        right: 0px;
                                    }
                                    .circlebox1 {
                                        text-align: left !important;
                                    }
                                </style>


                                <div class="lineheightbg" style="clear:both;"></div>
                                <div class="parmentaddress" style="clear: both;">
                                    <h1>Employment Data</h1>
                                    <div class="questionpopup11"><a href="javascript:;" data-toggle="popover" title="" data-content='<p class="circlebox1">if you are not employee then put self-employment' data-html="true"  data-placement="left">?</a></div>

                                    <?= $form->field($model, 'employer_name')->textInput(['maxlength' => true, 'placeholder' => 'Employer Name']) ?>

                                    <?= $form->field($model, 'employee_id')->textInput(['maxlength' => true, 'placeholder' => 'Employee Id']) ?>
                                    <?= $form->field($model, 'employment_email')->textInput(['maxlength' => true, 'placeholder' => 'Employment Email'])->label('Employee email') ?>

                                    <?= $form->field($model, 'employment_start_date')->textInput(['maxlength' => true, 'placeholder' => 'DD-MMM-YYYY', 'class' => 'datetimepicker form-control']) ?>


                                    <?= $form->field($model, 'employmnet_proof_url')->fileInput(['accept' => 'pdf/*', 'onchange' => "PreviewImage();", 'id' => 'uploadPDF'])->label('Employment Proof') ?>


                                    <!--div class="uploadbox <?= !empty($model->employmnet_proof_url) ? 'canceledCheck' : '' ?>">

                                        <div class="col-md-6 col-sm-6">
                                            <img  id="blah1" src="<?= !empty($model->employmnet_proof_url) ? "../" . $model->employmnet_proof_url : Url::home(true) . 'images/doumentupload.jpg' ?>" alt="..." >
                                    <?= !empty($model->employmnet_proof_url) ? '<a class="removeCancelledCheck" attr-id="' . $model->applicant_id . '"></a>' : '' ?>
                                            <p class="identityp">Cancelled Cheque</p>

                                        </div>	
                                    </div-->

                                    <div style="clear:both">
                                        <iframe id="viewer" frameborder="0" class="display_css_hide" scrolling="no" width="400" height="600"></iframe>
                                    </div>
                                    <?php
                                    $employmentProofUrl = (!empty($model->employmnet_proof_url)) ? Url::home(true) . $model->employmnet_proof_url : "";
                                    ?>
                                    <object data="<?php echo $employmentProofUrl; ?>" type="application/pdf" width="300" height="200" id="editpdf">
                                        <!--<a href="<?php echo $employmentProofUrl; ?>">test.pdf</a>-->
                                    </object>


                                </div>

                                <div class="lineheightbg"></div>

                                <br>
                                <div class="row">
                                    <div class="col-lg-6 col-sm-6">
                                        <?= Html::submitButton('Save', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn savechanges_submit_contractaction', 'onclick' => 'return checkForIfsc(event, this);']) ?>

                                    </div>
                                    <div class="col-lg-6 col-sm-6">
                                        <a style="color:#FFF" class="btn savechanges_submit cancel_confirm_2" href="<?= Url::home(true); ?>sales/applicants" type="button">Cancel</a>
                                    </div>
                                </div>




                            </div>

                            <div class="col-md-2 col-sm-2 common_profile_pic"style="margin-left: 10px;">
                                <div class="myprofile supportperson mytop">
                                    <a href="" class="thumbnail" onclick="apply_image('applicantprofile-profile_image');
                                            return false;
                                       ">
                                        <img id="blah14" src="<?= !empty($model->profile_image) ? "../" . $model->profile_image : Url::home(true) . 'images/photo.jpg' ?>" alt="...">
                                    </a>
                                </div>
                                <?= $form->field($model, 'profile_image')->fileInput(['accept' => 'image/*', 'style' => 'display:none', 'onchange' => "readURL14(this);"])->label(false) ?>
                            </div>
                            <?php ActiveForm::end(); ?>
                        </div>



                        <!-- /.col-lg-12 -->
                    </div>

                </div>

            </div>
            <div id="menu1" class="tab-pane fade">
                <div class="col-lg-12 col-sm-12">
                    <h4>Lead information</h4>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-sm-12">

                        <div class="advisorbox">
                            <?php $formLeads = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

                            <?php
                            $regions = \app\models\RefStatus::find()->all();
                            $cityData = ArrayHelper::map($regions, 'id', 'name');
                            if ($modelLeads->ref_status != 0) {
                                $stat = $modelLeads->ref_status;
                            } else {
                                $stat = 6;
                            }
                            //echo $modelLeads->ref_status;die;
                            ?>
                            <?= $formLeads->field($modelLeads, 'ref_status')->dropDownList($cityData, array('options' => array($stat => array('selected' => true))))->label('Lead Status');
                            ?>
                            <?php echo $formLeads->field($modelLeads, 'follow_up_date_time')->textInput(['maxlength' => true, 'placeholder' => 'DD-MMM-YYYY HH:MM', 'class' => 'datetimepicker form-control']) ?>


                            <!-- <div class="row">
                                    <br />
                       <div class="col-lg-6">
                                    <?//= Html::submitButton( 'Save', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn savechanges_submit_contractaction']) ?>
    
                                      </div>
                                       <div class="col-lg-6">
                                            <a style="color:#FFF" class="btn savechanges_submit" href="<?//= Url::home(true); ?>sales/applicants" type="button">Cancel</a>
    
                                       </div>
                                    </div> -->

                            <?php ActiveForm::end(); ?>

                        </div>

                        <div class="clearfix"></div>
                        <div class="propertyboxinner">
                            <div class="col-lg-12 col-sm-12 ptleadsleft">Properties</div>


                            <?php
                            if ($fav) {
                                $countfav = 0;
                                foreach ($fav as $keyform => $favData) {
                                    $countfav++;
                                    // print_r ($favData);
                                    ?>
                                    <div class="col-lg-6 col-sm-6">
                                        <div class="propertyboxlarge ">

                                            <div class="row">
                                                <div class="col-sm-4 col-md-4 col-lg-4">
                                                    <div class="propertyimgleft">
                                                        <?php $propertiesImages = \app\models\PropertyImages::find()->where(['property_id' => $favData->property_id])->one();
                                                        ?>
                                                        <img  src="<?php echo ($propertiesImages) ? "../" . $propertiesImages->image_url : Url::home(true) . 'images/gallery_imgone.jpg'; ?>" class="mCS_img_loaded" alt="image"> </a>

                                                    </div>
                                                </div>
                                                <div class="col-sm-8 col-md-8 col-lg-8">
                                                    <div class="propertynameright">
                                                        <p> <?= Yii::$app->userdata->getPropertyNameById($favData->property_id); ?> </p>
                                                        <div>
                                                            <label for="exampleInputName2">Status</label>
                                                            <p> <?php
                                                                if ($favData->status == 1) {
                                                                    echo "Favourite";
                                                                } else if ($favData->status == 2) {
                                                                    echo "Booked";
                                                                } else {
                                                                    echo "Scheduled";
                                                                }
                                                                ?> </p>
                                                        </div>
                                                        <div class="formscheduled">
                                                            <?php
                                                            ActiveForm::begin([
                                                                'id' => 'sch' . $keyform,
                                                                'options' => ['enctype' => 'multipart/form-data', 'class' => "form-inline"],
                                                                'enableAjaxValidation' => true,
                                                            ]);
                                                            ?>	<div class="col-md-12">
                                                                <div class="col-md-12"></div>
                                                                <label for="exampleInputEmail2">Scheduled On</label> <br />
                                                                <input type="hidden" id="fav_id" name="fav_id" value="<?= $favData->id; ?>" >

                                                                <input style="background: white; width: 35% !important;" type="text" value="<?= (date('d-m-Y', strtotime($favData->visit_date)) != '01-01-1970') ? date('d-M-Y', strtotime($favData->visit_date)) : ''; ?>" id="datesch<?= $countfav ?>" name="datesch"  class="form-control datepicker datesch_<?= $countfav ?>" placeholder="DD-MMM-YYYY">


                                                                                                    <!--<input type="text" id="timesch" value="<?= (date('d-m-Y', strtotime($favData->visit_date)) != '01-01-1970') ? date('H:i', strtotime($favData->visit_time)) : ''; ?>" name="timesch" class="form-control timepicker visit_time_class timesch_<?= $countfav ?>"  placeholder="HH:MM" onclick="getdate(this);">-->
                                                                <select style="height: 37px; width: 35%;" aria-required="true" name="timesch" id="timesch" class="form-control">
                                                                    <option value=""> At </option>
                                                                    <option <?php echo (date('H:i', strtotime($favData->visit_time)) == '10:00') ? 'selected="selected"' : ''; ?> value="10:00"> 10:00 - 10:30</option>
                                                                    <option <?php echo (date('H:i', strtotime($favData->visit_time)) == '10:30') ? 'selected="selected"' : ''; ?> value="10:30"> 10:30 - 11:00</option>
                                                                    <option <?php echo (date('H:i', strtotime($favData->visit_time)) == '11:00') ? 'selected="selected"' : ''; ?> value="11:00"> 11:00 - 11:30</option>
                                                                    <option <?php echo (date('H:i', strtotime($favData->visit_time)) == '11:30') ? 'selected="selected"' : ''; ?> value="11:30"> 11:30 - 12:00</option>
                                                                    <option <?php echo (date('H:i', strtotime($favData->visit_time)) == '12:00') ? 'selected="selected"' : ''; ?> value="12:00"> 12:00 - 12:30</option>
                                                                    <option <?php echo (date('H:i', strtotime($favData->visit_time)) == '12:30') ? 'selected="selected"' : ''; ?> value="12:30"> 12:30 - 13:00</option>
                                                                    <option <?php echo (date('H:i', strtotime($favData->visit_time)) == '13:00') ? 'selected="selected"' : ''; ?> value="13:00"> 13:00 - 13:30</option>
                                                                    <option <?php echo (date('H:i', strtotime($favData->visit_time)) == '13:30') ? 'selected="selected"' : ''; ?> value="13:30"> 13:30 - 14:00</option>
                                                                    <option <?php echo (date('H:i', strtotime($favData->visit_time)) == '14:00') ? 'selected="selected"' : ''; ?> value="14:00"> 14:00 - 14:30</option>
                                                                    <option <?php echo (date('H:i', strtotime($favData->visit_time)) == '14:30') ? 'selected="selected"' : ''; ?> value="14:30"> 14:30 - 15:00</option>
                                                                    <option <?php echo (date('H:i', strtotime($favData->visit_time)) == '15:00') ? 'selected="selected"' : ''; ?> value="15:00"> 15:00 - 15:30</option>
                                                                    <option <?php echo (date('H:i', strtotime($favData->visit_time)) == '15:30') ? 'selected="selected"' : ''; ?> value="15:30"> 15:30 - 16:00</option>
                                                                    <option <?php echo (date('H:i', strtotime($favData->visit_time)) == '16:00') ? 'selected="selected"' : ''; ?> value="16:00"> 16:00 - 16:30</option>
                                                                    <option <?php echo (date('H:i', strtotime($favData->visit_time)) == '16:30') ? 'selected="selected"' : ''; ?> value="16:30"> 16:30 - 17:00</option>
                                                                    <option <?php echo (date('H:i', strtotime($favData->visit_time)) == '17:00') ? 'selected="selected"' : ''; ?> value="17:00"> 17:00 - 17:30</option>
                                                                    <option <?php echo (date('H:i', strtotime($favData->visit_time)) == '17:30') ? 'selected="selected"' : ''; ?> value="17:30"> 17:30 - 18:00</option>
                                                                    <option <?php echo (date('H:i', strtotime($favData->visit_time)) == '18:00') ? 'selected="selected"' : ''; ?> value="18:00"> 18:00 - 18:30</option>
                                                                    <option <?php echo (date('H:i', strtotime($favData->visit_time)) == '18:30') ? 'selected="selected"' : ''; ?> value="18:30"> 18:30 - 19:00</option>
                                                                    <option <?php echo (date('H:i', strtotime($favData->visit_time)) == '19:00') ? 'selected="selected"' : ''; ?> value="19:00"> 19:00 - 19:30</option>
                                                                    <option <?php echo (date('H:i', strtotime($favData->visit_time)) == '19:30') ? 'selected="selected"' : ''; ?> value="19:30"> 19:30 - 20:00</option>
                                                                    <option <?php echo (date('H:i', strtotime($favData->visit_time)) == '20:00') ? 'selected="selected"' : ''; ?> value="20:00"> 20:00 - 20:30</option>
                                                                    <option <?php echo (date('H:i', strtotime($favData->visit_time)) == '20:30') ? 'selected="selected"' : ''; ?> value="20:30"> 20:30 - 21:00</option>
                                                                    <option <?php echo (date('H:i', strtotime($favData->visit_time)) == '21:00') ? 'selected="selected"' : ''; ?> value="21:00"> 21:00 - 21:30</option>

                                                                    <!--                                                                    <option value="10:00"> 10:00 - 10:30</option>
                                                                                                                                        <option value="10:30"> 10:30 - 11:00</option>
                                                                                                                                        <option value="11:00"> 11:00 - 11:30</option>
                                                                                                                                        <option value="11:30"> 11:30 - 12:00</option>
                                                                                                                                        <option value="12:00"> 12:00 - 12:30</option>
                                                                                                                                        <option value="12:30"> 12:30 - 13:00</option>
                                                                                                                                        <option value="13:00"> 13:00 - 13:30</option>
                                                                                                                                        <option value="13:30"> 13:30 - 14:00</option>
                                                                                                                                        <option value="14:00"> 14:00 - 14:30</option>
                                                                                                                                        <option value="14:30"> 14:30 - 15:00</option>
                                                                                                                                        <option value="15:00"> 15:00 - 15:30</option>
                                                                                                                                        <option value="15:30"> 15:30 - 16:00</option>
                                                                                                                                        <option value="16:00"> 16:00 - 16:30</option>
                                                                                                                                        <option value="16:30"> 16:30 - 17:00</option>
                                                                                                                                        <option value="17:00"> 17:00 - 17:30</option>
                                                                                                                                        <option value="17:30"> 17:30 - 18:00</option>
                                                                                                                                        <option value="18:00"> 18:00 - 18:30</option>
                                                                                                                                        <option value="18:30"> 18:30 - 19:00</option>
                                                                                                                                        <option value="19:00"> 19:00 - 19:30</option>
                                                                                                                                        <option value="19:30"> 19:30 - 20:00</option>
                                                                                                                                        <option value="20:00"> 20:00 - 20:30</option>
                                                                                                                                        <option value="20:30"> 20:30 - 21:00</option>
                                                                                                                                        <option value="21:00"> 21:00 - 21:30</option>-->
                                                                </select>
                                                                <script>
                                                                    window.selectedVal = '<?php echo (!empty($favData->visit_time)) ? $favData->visit_time : 0; ?>';
                                                                </script>
                                                                <div class="help-block visit-time-help-block" style="display: none;">Visit time cannot be blank.</div>
                                                                <?= Html::Button('Save', ['onclick' => 'return schsubmit(' . $keyform . ')', 'class' => 'btn btn-danger replybut', 'style' => 'height: 37.7px; width: 28%;']) ?>
                                                                <ul class='time_suggestion' style='display: none'>

                                                                </ul>
                                                            </div>
                                                            <div class="col-md-6" style="margin-top: 4px;">

                                                            </div>
                                                            <?php ActiveForm::end(); ?>


                                                        </div>
                                                        <p class="text-right anchorRight" style="width: 100%;">
                                                            <?php
                                                            if ($favData->child_properties == '') {
                                                                $child_property = $favData->property_id;
                                                            } else {
                                                                $child_property = $favData->child_properties;
                                                            }
                                                            ?>
                                                            <a class="btn btn-primary" href="<?= Url::home(true) ?>external/createlease?property_id=<?= $child_property; ?>&parent_id=<?= $favData->property_id; ?>&applicant_id=<?= $model->applicant_id; ?>" onclick="openLeaseSetUpModal(this)" style="width: 100%; height: 38px; font-size: 18px; line-height: 32px;"  data-toggle="modal" data-target="#myModal2">Set Up The Lease </a>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            } else {
                                ?>
                                <div class="col-lg-12 ptleadsleft"> <p> No Booked/scheduled property found without agreement</p></div>
                            <?php } ?>
                            <div class="clearfix"></div>
                            <div class="col-lg-6">
                                <a type="button" class="btn savechanges_submit_contractaction" href="<?= Url::home(true) ?>external/createfav?applicant_id=<?= $model->applicant_id; ?>" onclick="openAddPropertyModal(this)" data-toggle="modal" data-target="#myModal33">Add Property</a>
                            </div>


                        </div>

                        <div class="clearfix"></div>
                        <div class=" advisorbox">
                            <?php
                            $formLeads = ActiveForm::begin([
                                        'id' => 'comment_form',
                                        'options' => ['enctype' => 'multipart/form-data'],
                                        'enableAjaxValidation' => true,
                                        'action' => 'addcomment1',
                            ]);
                            ?>

                            <div class="row">

                                <?php
                                $propData = ArrayHelper::map($props, 'id', 'property_name');
                                ?>
                                <?= $form->field($modelComments, 'property_id')->dropDownList($propData, ['prompt' => 'Select property'])->label('Property'); ?>

                                <div class="col-md-1 note_c">
                                    <p>Notes</p>
                                </div>
                                <div class="col-md-11 col-sm-11 comment_c">
                                    <div class="col-md-10 col-sm-10 comment_c">
                                        <?= $form->field($modelComments, 'description')->textarea(['rows' => '1'])->label(false) ?>
                                        <?= $form->field($modelComments, 'user_id')->hiddenInput(['value' => $model->applicant_id])->label(false) ?>

                                    </div>

                                    <div class="col-md-2 col-sm-2 comment_c">
                                        <?= Html::Button('Add Comment', ['id' => 'add_comment', 'class' => 'btn btn-default replybut']) ?>

                                    </div>
                                </div>
                            </div>

                            <?php ActiveForm::end(); ?>
                            <script>
                                function checkForIfsc(e, ele) {
                                    e.preventDefault();
                                    var ifsc = $('#applicantprofile-bank_ifcs').val();
                                    $.ajax({
                                        url: 'checkifsc',
                                        type: 'post',
                                        data: {'ifsc': ifsc},
                                        success: function (res) {
                                            if (res == 'error') {
                                                $('#applicantprofile-bank_ifcs').css('border', '1px solid #a94442');
                                                $('#ifsc-error-box').css('display', 'block');
                                                $(document).scrollTop($('#applicantprofile-bank_ifcs').position().top + 35);
                                                return false;
                                            } else {
                                                $('#applicantprofile-bank_ifcs').css('border', '1px solid #c7c7c7');
                                                $('#ifsc-error-box').css('display', 'none');
                                                $('#w0').submit();
                                            }
                                        },
                                        error: function (a, b, c) {
                                            alert("Some error occured at server, please try again later.");
                                        }
                                    });
                                }

                                function schsubmit(formid) {

                                    if ($('#timesch').val() == '') {
                                        $('.visit-time-help-block').css('display', 'block');
                                        return false;
                                    } else {
                                        $('.visit-time-help-block').css('display', 'none');
                                    }

                                    if ($('#datesch').val() == '') {
                                        alert('Please enter date.');
                                        return false;

                                    } else if ($('#timesch').val() == '') {
                                        alert('Please enter time.');
                                        return false;

                                    }

                                    var full = $('#datesch1').val() + ' ' + $('#timesch').val() + ':00';
                                    var d = new Date(full);
                                    var nowa = new Date();
                                    nowa = new Date(nowa.setHours(nowa.getHours() + 4));
                                    //console.log(nowa);
                                    //return false;
                                    if (d <= nowa) {
                                        alert('The schedule time should be 4 hours ahead of current time');
                                        return false;
                                    }

                                    startLoader();
                                    $.ajax({
                                        type: "POST",
                                        url: 'saveschdule',
                                        data: $("#sch" + formid).serialize(), //only input
                                        success: function (response) {
                                            hideLoader();
                                            if (response == 'done') {

                                                alert(' Schedule updated');
                                            } else {
                                                alert('Please try again');
                                            }
                                        },
                                        error: function (data) {
                                            hideLoader();
                                            // alert(data.responseText);
                                            alert('Failure')

                                        }
                                    });
                                }
                                function getdate(ele)
                                {
                                    //alert(ele);
                                }
                                $(document).ready(function () {

                                    var form = $("#comment_form");
                                    $("#add_comment").click(function () {
                                        if ($('#comments-property_id').val() == '') {
                                            alert('Please select property.');
                                            $('#comments-property_id').focus();
                                            return false;

                                        } else if ($('#comments-description').val() == '') {
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
                                                hideLoader();
                                                if (response == 'done') {
                                                    $("#comment_form")[0].reset();
                                                    $('#dataComments').load(' #dataComments');
                                                } else {
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
                            </script>

                            <div class="lorembox" id="dataComments">
                                <?php
                                if ($comments) {
                                    foreach ($comments as $comment) {
                                        ?>
                                        <p><?= $comment->description; ?>,
                                            <span>-<?= Yii::$app->userdata->getPropertyNameById($comment->property_id); ?>, <?= Yii::$app->userdata->getFullNameById($comment->created_by); ?> on  <?= date('m/d/Y', strtotime($comment->created_date)); ?> At  <?= date('h:i A', strtotime($comment->created_date)); ?></span>
                                        </p>
                                        <?php
                                    }
                                } else {
                                    echo "<p> No comments</p>";
                                }
                                ?>


                            </div>
                            <div class="clearfix"></div>
                            <div class="row">
                                <br />
                                <br>
                                <div class="col-lg-6 col-sm-6">
                                    <a style="color:#FFF" class="btn savechanges_submit_contractaction submit_form_anchor" data-value="w1" href="#">Save</a>

                                </div>
                                <div class="col-lg-6 col-sm-6">
                                    <a style="color:#FFF" class="btn savechanges_submit cancel_confirm" href="<?= Url::home(true); ?>sales/applicants" type="button">Cancel</a>

                                </div>
                            </div>
                        </div>


                    </div>

                </div>
            </div>

            <!-- Lead Assignment starts from here -->

            <div id="lead-assignment" class="tab-pane fade">
                <div class="col-lg-9 col-sm-9 col-xs-offset-1">
                    <h3>Lead assignment</h3>
                </div>
                <div class="row">
                    <div class="col-lg-9 col-sm-9 col-xs-offset-1">
                        <div class="advisorbox">

                            <?php $formLeads = ActiveForm::begin(['id' => 'owner_lead_assignment', 'action' => 'leadassignmentapplicant', 'method' => 'post', 'options' => ['enctype' => 'multipart/form-data']]); ?>

                            <input type="hidden" value="1" name="lead_assignment" />
                            <?php
                            //print_r($modelLeads);
                            $userModel = app\models\User::findOne(['login_id' => $modelLeads->email_id]);
                            $applicantProfileModel = app\models\ApplicantProfile::findOne(['applicant_id' => $userModel->id]);
                            $currentRoleCode = NULL;
                            $currentRoleValue = NULL;
                            $currentBranchCode = $applicantProfileModel->branch_code;

                            if (Yii::$app->user->identity->user_type == 6) {
                                $currentSaleProfile = app\models\SalesProfile::findOne(['sale_id' => Yii::$app->user->id]);
                                $currentRoleCode = @$currentSaleProfile->role_code;
                                $currentRoleValue = @$currentSaleProfile->role_value;
                            } else if (Yii::$app->user->identity->user_type == 7) {
                                $currentOperationProfile = app\models\OperationsProfile::findOne(['operations_id' => Yii::$app->user->id]);
                                $currentRoleCode = @$currentOperationProfile->role_code;
                                $currentRoleValue = @$currentOperationProfile->role_value;
                            }

                            $applicantId = $applicantProfileModel->applicant_id;
                            $ownerOperation = $applicantProfileModel->operation_id;
                            $ownerSale = $applicantProfileModel->sales_id;

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

                            <input type="hidden" name="applicant_id" value="<?= $applicantId ?>" />

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
                                    <?php if (!empty($applicantProfileModel->operation_id)) { ?>
                                        <option selected="selected" value="<?= $applicantProfileModel->operation_id; ?>"><?= (Yii::$app->userdata->getEmailById($applicantProfileModel->operation_id)) . " [" . Yii::$app->userdata->getRoleCodeByUserIdOps($applicantProfileModel->operation_id) . "] [" . Yii::$app->userdata->getRoleValueByUserIdOps($applicantProfileModel->operation_id) . "]"; ?></option>
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
                                    <?php if (!empty($applicantProfileModel->sales_id)) { ?>
                                        <option selected="selected" value="<?= $applicantProfileModel->sales_id; ?>"><?= (Yii::$app->userdata->getEmailById($applicantProfileModel->sales_id)) . " [" . Yii::$app->userdata->getRoleCodeByUserIdSales($applicantProfileModel->sales_id) . "] [" . Yii::$app->userdata->getRoleValueByUserIdSales($applicantProfileModel->sales_id) . "]"; ?></option>
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
                                    <input  id="tags" name="userid" type="text" class="form-control" placeholder="Search Sales Person" class="input-medium" >
                                    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
                                    <script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
                                    <style>
                                        ul.ui-autocomplete {
                                            z-index: 1100;
                                        }
                                    </style>
                                    <script>

                                $(function () {
                                    $("#tags").autocomplete({
                                        source: "getsalesname",
                                        select: function (event, ui) {
                                            event.preventDefault();
                                            //	console.log(ui.item);
                                            $("#sales_name").val(ui.item.label);
                                            $("#applicantprofile-sales_id").val(ui.item.value);
                                            $("#tags").val(ui.item.label);
                                        },
                                    });
                                });

                                    </script>
                                </div>
                            </div>

                            <!-- Button
                            <div class="">
                              <label class="control-label" for="add"></label>
                              <div class="controls">
                                <button id="search" name="search" class="btn btn-success btn-lg btn-block">Search</button>
                              </div>
                            </div> -->

                            <!-- Button -->
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

<div data-refresh="true" class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modalbg modalbgpopup" role="document">
        <div class="modal-content myModal2-modal-content">

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="myModal33" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modalbg modalbgpopup" role="document">
        <div class="modal-content">

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-------------------------->

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

<script>

    $(document).on('change', '.IDproof', function () {
        var count = $('#IDproof .box').length;
        $('.identity_box').hide();
        $('#IDproof').append('<div class=" identity_file_' + count + ' identity_box box col-md-12 col-sm-12"><input type="file" attr-class="identity_file_' + count + '" name="address_proof[' + $(this).val() + '][]" attr-type="' + $('.IDproof option:selected').text() + '" class="identity_files" accept="image/*">');


    });

    $(document).on('change', '.identity_files', function (e) {
        readurlIdentity(this);

    });


    $(document).on('change', '.EMERProof', function () {
        var count = $('#EMERProof .box').length;
        $('.emergency_box').hide();
        $('#EMERProof').append('<div class=" emergency_file_' + count + ' emergency_box box col-md-12 col-sm-12"><input type="file" attr-class="emergency_file_' + count + '" name="emergency_proof[' + $(this).val() + '][]" attr-type="' + $('.EMERProof option:selected').text() + '" class="emergency_files" accept="image/*">');

    });

    $(document).on('change', '.emergency_files', function (e) {
        readurlIdentity1(this);

    });

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
                        $('.identity_image_preview').append('<div class="col-md-6"><img class= "preview" alt=""src="' + e.target.result + '"><a class="removeLocal" id="' + attrclass + '"></a><p class="identityp">' + $('.IDproof option:selected').text() + '</p></div>');
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

    function readurlIdentity1(input) {
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
                        $('.emergency_image_preview').append('<div class="col-md-6"><img class= "preview" alt=""src="' + e.target.result + '"><a class="removeLocal" id="' + attrclass + '"></a><p class="emergencyp">' + $('.EMERProof option:selected').text() + '</p></div>');
                        // $('.EMERProof').val('');
                        $('.EMERProof').prop('selectedIndex', 0);
                        $('.emergency_box').hide();

                    } else {
                        alert('Only images are allowed');
                        $('.EMERProof').val('');
                        $('#EMERProof div.' + attrclass).remove();
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
                // console.log(image_check);
                if (image_check == 'image') {
                    $('#image_upload_preview').attr('src', e.target.result);
                } else {
                    alert("only images are allowed");
                }
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#applicantprofile-profile_image").change(function () {
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

    $("#applicantprofile-address_proof").change(function () {
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

    $("#applicantprofile-identity_proof").change(function () {
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

    $("#applicantprofile-emer_contact_indentity").change(function () {
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

    $("#applicantprofile-emer_contact_address").change(function () {
        readURL4(this);
    });
    function readURL4(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#cancelled').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#applicantprofile-cancelled_check").change(function () {
        readURL5(this);
    });
</script>
<style>
    .field-applicantprofile-emer_contact_address label, .field-applicantprofile-emer_contact_indentity label{
        display:none;
    }
</style>
<script type="text/javascript">
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#cancelled').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $(document).ready(function () {
        $(".scheduledDatePicker").datepicker({
            minDate: new Date(),
            changeMonth: true,
            changeYear: true,
            yearRange: "-0:+1",
            numberOfMonths: 1,
            dateFormat: 'dd-M-yy',
            beforeShowDay: function (date) {

                if (date.getDay() == 3) {
                    return [false, ''];
                }
                return [true, ''];
            }
        });

        $(".hasDatepicker").datepicker({
            minDate: new Date(),
            changeMonth: true,
            changeYear: true,
            yearRange: "-0:+1",
            numberOfMonths: 1,
            dateFormat: 'dd-M-yy',
            beforeShowDay: function (date) {

                if (date.getDay() == 3) {
                    return [false, ''];
                }
                return [true, ''];
            }
        });

        $('#applicantprofile-employment_start_date').datetimepicker({
            format: 'DD-MMM-YYYY'
        })
    });

    $(document).on('click', '.submit_form_anchor', function (e) {
        e.preventDefault();
        $('#' + $(this).attr('data-value')).submit();
    })

// function readURL(input) {
    // if (input.files && input.files[0]) {
    // var reader = new FileReader();

    // reader.onload = function (e) {
    // $('#blah').attr('src', e.target.result);
    // }

    // reader.readAsDataURL(input.files[0]);
    // }
    // }

    // $("#imgInp").change(function(){
    // readURL(this);
    // });

    function readURL13(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah')
                        .attr('src', e.target.result)
                        .width(290)
                        .height(200);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
    function readURL12(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah1')
                        .attr('src', e.target.result)
                        .width(290)
                        .height(200);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
    function readURL14(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah14')
                        .attr('src', e.target.result)
                        .width(290)
                        .height(150);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    $(document).on('change', '#tenantagreements-agreement_url', function (e) {
        var inpAgreeUrl = $('#tenantagreements-agreement_url').val();
        if (inpAgreeUrl == '') {
            $('.crt_lease_sub_btn').attr('disabled');
        } else {
            $('.crt_lease_sub_btn').removeAttr('disabled');
        }
    });

    $(document).on('submit', '#ownerform', function (e) {
        e.preventDefault();
        var element = $(this);
        var form_data = new FormData($(this)[0]);
        startLoader();
        $.ajax({
            url: element.attr('action'),
            method: 'POST',
            data: form_data,
            dataType: 'JSON',
            contentType: false,
            processData: false,
            success: function (data) {
                if (data.success == '1') {
                    location.reload();
                } else if (data.error != '') {
                    alert(data.error);
                } else {
                    $('.help-block').text('');
                    $('.has-error').removeClass('has-error');
                    $.each(data, function (key, value) {
                        if (value.length > '0') {
                            $('#' + key).siblings('.help-block').text(value[0]);
                            $('.field-' + key).addClass('has-error');
                        }

                    })

                }
                hideLoader();
            }, error: function (fd, status, msg) {
                // alert(msg+' Some error occured');
                hideLoader();
            }
        });
    })

    $(document).on('submit', '#ownerform2', function (e) {
        e.preventDefault();
        var forRoomBed = $('input[name=for_bed_room]:checked').val();
        if (forRoomBed !== undefined && forRoomBed != '' && forRoomBed != null) {

            if (forRoomBed == 1 && $('#rooms_id').val() == '') {
                $('#rooms_id').css('border-color', '#a94442');
                return;
            } else {
                $('#rooms_id').css('border', '1px solid #ccc');
            }

            if ((forRoomBed == 2) && ($('#beds_id').val() == '' || $('#beds_id').val() === undefined)) {
                $('#beds_id').css('border-color', '#a94442');
                if ($('#rooms_id').val() == '') {
                    $('#rooms_id').css('border-color', '#a94442');
                } else {
                    $('#rooms_id').css('border', '1px solid #ccc');
                }
                return;
            } else {
                $('#beds_id').css('border', '1px solid #ccc');
            }

            var element = $(this);
            var form_data = new FormData($(this)[0]);
            startLoader();
            $.ajax({
                url: element.attr('action'),
                method: 'POST',
                data: form_data,
                dataType: 'JSON',
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data.success == '1') {
                        location.reload();
                    } else {
                        $('.help-block').text('');
                        $('.has-error').removeClass('has-error');
                        $.each(data, function (key, value) {
                            if (value.length > '0') {
                                $('#' + key).siblings('.help-block').text(value[0]);
                                $('.field-' + key).addClass('has-error');
                            }

                        })

                    }
                    hideLoader();
                }, error: function (fd, status, msg) {
                    // alert(msg+' Some error occured');
                    hideLoader();
                }
            });
        } else {
            alert('Please select Bed or Room');
        }
    })

    $(document).on("click", "#select-room-for", function (e) {
        $('#beds-collection').empty();
        $('#beds-collection').addClass('hidden');
    });

    $(document).on("click", ".cancel_confirm_2", function (e) {
        e.preventDefault();
        var element = $(this);
        var confirm_var = confirm('Are you sure you want to cancel ?');
        if (confirm_var == true) {
            window.history.back();

        }
    });

    function getAvailableBeds(mainId, roomId) {
        var csrfToken = $("input[name='_csrf']").val();
        $('#beds-collection').addClass('hidden');
        if ($('input[name=for_bed_room]:checked').val() == 2) {
            $.ajax({
                url: 'getavailablebeds',
                type: 'post',
                data: {'_csrf': csrfToken, 'mainId': mainId, 'roomId': roomId},
                success: function (res) {
                    if (res != 'error') {
                        $('#beds-collection').html(res);
                        $('#beds-collection').removeClass('hidden');
                    } else {

                    }
                },
                error: function (a, b, c) {
                    alert("Some error occured at server, please try again later.");
                }
            });
        }
    }

    function openLeaseSetUpModal(ele) {
        //$('body').on('hidden.bs.modal', '.modal', function () {
        //  $(ele).removeData('bs.modal');
        //});
    }

    function PreviewImage() {
        pdffile = document.getElementById("uploadPDF").files[0];
        pdffile_url = URL.createObjectURL(pdffile);
        $('#viewer').attr('src', pdffile_url);
        $("#viewer").removeClass("display_css_hide").addClass("display_css_show");
        $("#editpdf").removeClass("display_css_show").addClass("display_css_hide");
    }

    $('#myModal2').on('show.bs.modal', function (e) {
        var url = $(e.relatedTarget).attr('href');
        $('.myModal2-modal-content').load(url);
    });

</script>
<script>
    $(document).on("click", ".cancel_confirm_1", function (e) {
        e.preventDefault();
        var element = $(this);
        var confirm_var = confirm('Are you sure you want to cancel ?');
        if (confirm_var == true) {
            //window.location.href = (element.attr('href'));
            $("#myModal2").modal("hide");
        } else {
            e.preventDefault();
        }
    });
</script>
<style>
    .display_css_hide{
        display: none;
    }
    .display_css_show{
        display: block;
    }
</style>