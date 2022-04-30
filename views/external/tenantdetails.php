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
    #blah{
        width: 290px;
        height: 150px;
    }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<!-- Modal 1 for lease extension -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
                <div class="row">
                    <div class="col-lg-12 col-sm-12">
                        <div class="formtexttable">
                            <ul>
                                <li>
                                    Rent Amount
                                    <span>
                                        <div class="form-group required has-success">
                                            <input type="text" id="tenantagreements-rent-2" class="form-control" name="TenantAgreements[rent]" value="<?= $tenantAgreements->rent; ?>" placeholder="Rent*" aria-required="true" aria-invalid="false">
                                            <div class="help-block"></div>
                                        </div>
                                    </span>
                                </li>
                                <li>
                                    Maintenance Amount
                                    <span>
                                        <div class="form-group required has-success">
                                            <input type="text" id="tenantagreements-maintainance-2" class="form-control" name="TenantAgreements[maintainance]" value="<?= $tenantAgreements->maintainance; ?>" placeholder="Maintenance*" aria-required="true" aria-invalid="false">
                                            <div class="help-block"></div>
                                        </div>
                                    </span>
                                </li>
                                <li>
                                    Deposit Amount
                                    <span>
                                        <div class="form-group required has-success">
                                            <input type="text" id="tenantagreements-deposit-2" class="form-control" name="TenantAgreements[deposit]" value="<?= $tenantAgreements->deposit; ?>" placeholder="Deposit*" aria-required="true" aria-invalid="false">
                                            <div class="help-block"></div>
                                        </div>
                                    </span>
                                </li>
                                <li>
                                    Minimum Stay Period (In Months)
                                    <span>
                                        <div class="form-group required has-success">
                                            <input type="text" id="tenantagreements-min_stay-2" class="form-control" name="TenantAgreements[min_stay]" value="<?= $tenantAgreements->min_stay; ?>" maxlength="100" placeholder="Minimum Stay*" aria-required="true" aria-invalid="false">
                                            <div class="help-block"></div>
                                        </div>
                                    </span>
                                </li>
                                <li>
                                    Notice Period (In Months)
                                    <span>
                                        <div class="form-group required has-success">
                                            <input type="text" id="tenantagreements-notice_period-2" class="form-control" name="TenantAgreements[notice_period]" value="<?= $tenantAgreements->notice_period; ?>" maxlength="100" placeholder="Notice Period*" aria-required="true" aria-invalid="false">
                                            <div class="help-block"></div>
                                        </div>
                                    </span>
                                </li>
                                <li>
                                    Late Fee Penalty (%)
                                    <span>
                                        <div class="form-group">
                                            <input type="text" id="tenantagreements-late_penalty_percent-2" class="form-control" name="TenantAgreements[late_penalty_percent]" value="<?= $tenantAgreements->late_penalty_percent; ?>" placeholder="Late Penalty Percent*">
                                            <div class="help-block"></div>
                                        </div>
                                    </span>
                                </li>
                                <li>
                                    Late Fee Minimum Penalty
                                    <span>
                                        <div class="form-group">
                                            <input type="text" id="tenantagreements-min_penalty-2" class="form-control" name="TenantAgreements[min_penalty]" value="<?= $tenantAgreements->min_penalty; ?>" placeholder="Minimum Penalty*">
                                            <div class="help-block"></div>
                                        </div>
                                    </span>
                                </li>
                                <input type="hidden" name="TenantAgreements[lease_start_date]" value="<?= $tenantAgreements->lease_start_date; ?>" />
                                <input type="hidden" name="TenantAgreements[lease_end_date]" value="<?= $tenantAgreements->lease_end_date; ?>" />
                                <input type="hidden" name="TenantAgreements[lease_extended_date]" value="1" />
                                <li>
                                    Lease End Date
                                    <span>
                                        <div class="form-group required has-success">
                                            <input readonly="readonly" type="text" id="tenantagreements-lease_end_date-2" class="form-control" name="TenantAgreements[ext_lease_end_date]" value="<?= $tenantAgreements->lease_end_date; ?>" placeholder="Lease End Date*" aria-required="true" aria-invalid="false">
                                            <div class="help-block"></div>
                                        </div>
                                    </span>
                                </li>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Extend</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                </div>
                            </ul>
                        </div>
                        <p>&nbsp;</p>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
            <div class="modal-footer">
                <!--<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->
                <!--<button type="button" class="btn btn-primary">Save changes</button>-->
            </div>
        </div>
    </div>
</div>

<!-- Modal 2 for lease termination -->
<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
                <div class="row">
                    <div class="col-lg-12 col-sm-12">
                        <div class="">
                            <input type="hidden" name="TenantAgreements[late_penalty_percent]" value="<?= $tenantAgreements->late_penalty_percent; ?>">
                            <input type="hidden" name="TenantAgreements[min_penalty]" value="<?= $tenantAgreements->min_penalty; ?>">
                            <input type="hidden" name="TenantAgreements[lease_start_date]" value="<?= $tenantAgreements->lease_start_date; ?>" />
                            <input type="hidden" name="TenantAgreements[lease_end_date]" value="<?= $tenantAgreements->lease_end_date; ?>" />
                            <input type="hidden" name="TenantAgreements[lease_terminate_date]" value="1" />
                            Lease End Date
                            <span>
                                <div class="form-group required has-success">
                                    <input readonly="readonly" type="text" id="tenantagreements-lease_end_date-3" class="form-control" name="TenantAgreements[ext_lease_end_date]" value="<?= $tenantAgreements->lease_end_date; ?>" placeholder="Lease End Date*" aria-required="true" aria-invalid="false">
                                    <div class="help-block"></div>
                                </div>
                            </span>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Terminate</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                        <p>&nbsp;</p>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
            <div class="modal-footer">
                <!--<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->
                <!--<button type="button" class="btn btn-primary">Save changes</button>-->
            </div>
        </div>
    </div>
</div>

<div id="page-wrapper">
    <div class="row">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#home">Tenant information </a></li>
            <li><a data-toggle="tab" href="#menu1">Tenant Contract</a></li>
        </ul>

        <div class="tab-content">
            <div id="home" class="tab-pane fade in active">
                <div class="col-lg-7 col-xs-offset-1">
                    <h4>Tenant information</h4>
                </div>
                <div class="row">
                    <div class="col-lg-12">
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


                        <div class="row">
                            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
                            <div class="col-lg-7 col-xs-offset-1">

                                <div class="myprofile supportperson mytop">
                                    <span>Name </span>
                                    <?php $fullname = Yii::$app->userdata->getFullNameById($model->tenant_id); ?>
                                    <?= $form->field($modelLeads, 'full_name')->textInput(['maxlength' => true, 'placeholder' => 'Full Name*', 'value' => $fullname]) ?>
                                </div>

                                <div class="lineheightbg"></div>
                                <div class="parmentaddress">
                                    <h1>Contact detail </h1>

                                    <?= $form->field($modelLeads, 'login_id')->textInput(['maxlength' => true, 'placeholder' => 'Email*']) ?>

                                    <?= $form->field($model, 'phone')->textInput(['maxlength' => 15, 'placeholder' => 'Phone Number*', 'class' => 'form-control']) ?>


                                </div>

                                <div class="lineheightbg"></div>
                                <div class="parmentaddress">
                                    <h1>Permanent address</h1>
                                    <?= $form->field($model, 'address_line_1')->textInput(['maxlength' => true, 'placeholder' => 'Address Line 1*']) ?>

                                    <?= $form->field($model, 'address_line_2')->textInput(['maxlength' => true, 'placeholder' => 'Address Line 2*']) ?>

                                    <?php
                                    $states = \app\models\States::find()->all();
                                    $stateData = ArrayHelper::map($states, 'code', 'name');
                                    // print_r(array_keys($stateData)); 
                                    ?>
                                    <?= $form->field($model, 'state')->dropDownList($stateData, ['prompt' => 'Select state', 'url' => \Yii::$app->getUrlManager()->createUrl('users/getcity')]); ?>

                                    <?php
                                    //$cities = \app\models\Cities::find()->where(['state_id' => $model->state])->all();
                                    //$cityData = ArrayHelper::map($cities, 'id', 'city_name');
                                    ?>
                                    <?php //echo $form->field($model, 'city')->dropDownList($cityData, ['prompt' => 'Select city', 'url' => \Yii::$app->getUrlManager()->createUrl('advisers/getregions'), $model->city => array('selected' => true)]); ?>
                                    <?= $form->field($model, 'city_name')->textInput(['maxlength' => 25, 'placeholder' => 'City name*', 'class' => 'form-control ']) ?>
                                    <?php
                                    $branches = Yii::$app->role->getBranchesByRole(Yii::$app->user->id, Yii::$app->user->identity->user_type);
                                    $branchData = \yii\helpers\ArrayHelper::map($branches, 'branch_code', 'name');
                                    ?>
                                    <?= $form->field($model, 'region')->dropDownList($branchData, ['prompt' => 'Select Branch'])->label('Branch'); ?>

                                    <?= $form->field($model, 'pincode')->textInput(['maxlength' => 15, 'placeholder' => 'Pincode*', 'class' => 'form-control ']) ?>


                                    <div class="lineheightbg"></div>
                                    <div class="uploadbox">
                                        <div class="changepassword">
                                            <h1 class="advisortext">Identity and Address Proof Documents</h1></div></div>
                                    <div class="row">
                                        <div class="uploadbox">	
                                            <div class="col-md-6">
                                                <option value="">Select ID Proof</option>
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
                                    <p style=""></p>
                                    <h1>Emergency contact detail </h1>
                                    <?= $form->field($model, 'emergency_contact_name')->textInput(['maxlength' => true, 'placeholder' => 'Name*']) ?>

                                    <?= $form->field($model, 'emer_contact')->textInput(['maxlength' => true, 'placeholder' => 'Email*']) ?>

                                    <?= $form->field($model, 'emergency_contact_number')->textInput(['maxlength' => 15, 'placeholder' => 'Number*', 'class' => 'form-control']) ?>
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
                                    <?= $form->field($model, 'account_number')->textInput(['maxlength' => true, 'placeholder' => 'Bank Account Number*', 'class' => 'form-control isNumber']) ?>      	
                                    <?= $form->field($model, 'pan_number')->textInput(['maxlength' => true, 'placeholder' => 'Pan Number*']) ?>    

                                </div>

                                <div class="chancelall">
                                    <?= $form->field($model, 'cancelled_check')->fileInput(['accept' => 'image/*']) ?>
                                    <div class="questionpopup_edit_p">
                                        <a href="javascript:;" data-toggle="popover" title="Lorem" data-content='<p class="circlebox">The cheque should carry name of the account holder and matching applicant name' data-html="true"  data-placement="left">?</a>	
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
                                </style>


                                <div class="lineheightbg"></div>
                                <div class="parmentaddress">
                                    <h1>Employement Data</h1>
                                    <div class="questionpopup11"><a href="javascript:;" data-toggle="popover" title="Lorem" data-content='<p class="circlebox">if you are not employee then put self-employment' data-html="true"  data-placement="left">?</a></div>

                                    <?= $form->field($model, 'employer_name')->textInput(['maxlength' => true, 'placeholder' => 'Employer Name*']) ?>

                                    <?= $form->field($model, 'employee_id')->textInput(['maxlength' => true, 'placeholder' => 'Employee Id*']) ?>
                                    <?= $form->field($model, 'employment_email')->textInput(['maxlength' => true, 'placeholder' => 'Employment Email*']) ?>

                                    <?= $form->field($model, 'employment_start_date')->textInput(['maxlength' => true, 'placeholder' => 'DD-MMM-YYYY*', 'class' => 'datetimepicker form-control']) ?>

                                    <?= $form->field($model, 'employment_proof_url')->fileInput(['accept' => 'application/msword,application/.doc,application/.docx, application/pdf'])->label('Employment Proof') ?>
                                    <a href="<?= Url::home(true) . $model->employment_proof_url ?>" target = '_blank'><?= Url::home(true) . $model->employment_proof_url ?></a>
                                </div>


                                <div class="row">
                                    <div class="col-lg-6">
                                        <?= Html::submitButton('Save', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn savechanges_submit_contractaction']) ?>

                                    </div>
                                    <div class="col-lg-6">
                                        <a style="color:#FFF" class="btn savechanges_submit cancel_confirm_2" href="<?= Url::home(true); ?>sales/applicants" type="button">Cancel</a>

                                    </div> 
                                </div>




                            </div>

                            <div class="col-md-2 col-sm-2"style="margin-left: 10px;">
                                <div class="myprofile supportperson mytop">
                                    <a href="" class="thumbnail" onclick="apply_image('tenantprofile-profile_image'); return false;">
                                        <img id="blah" src="<?= !empty($model->profile_image) ? "../" . $model->profile_image : Url::home(true) . 'images/photo.jpg' ?>" alt="...">
                                    </a>
                                </div>
                                <?= $form->field($model, 'profile_image')->fileInput(['accept' => 'image/*', 'style' => 'display:none'])->label(false) ?>
                            </div>
                            <?php ActiveForm::end(); ?>
                        </div>



                        <!-- /.col-lg-12 -->
                    </div>

                </div>

            </div>

            <div id="menu1" class="tab-pane fade">
                <div class="col-lg-8 col-xs-offset-1">
                    <h4>Tenant Contract</h4>
                </div>
                <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
                <div class="row">
                    <div class="col-lg-8 col-sm-8 col-xs-offset-1">
                        <div class="formtexttable">
                            <ul>
                                <!--<li>Tenant<span><?= Yii::$app->userdata->getUserNameById($tenantAgreements->tenant_id) ?></span></li>-->
                                <li>Property:<span><?= isset($tenantAgreements->parent_id) ? Yii::$app->userdata->getPropertyNameById($tenantAgreements->parent_id) : '' ?> <?php
                                        if (!empty($tenantAgreements->bed_id)) {
                                            echo '[Bed]';
                                        } else if (!empty($tenantAgreements->room_id) && empty($tenantAgreements->bed_id)) {
                                            echo '[Room]';
                                        }
                                        ?></span></li>
                                <?php if (!empty($tenantAgreements->room_id)) { ?>
                                    <li>Room:<span><?= Yii::$app->userdata->getRoomName($tenantAgreements->room_id) ?></span></li>
                                <?php } ?>
                                <?php if (!empty($tenantAgreements->bed_id)) { ?>
                                    <li>Bed<span><?= Yii::$app->userdata->getBedName($tenantAgreements->bed_id) ?></span></li> <br />
                                        <?php } ?>
                                <li style="visibility: hidden;">Property<span><?= isset($tenantAgreements->parent_id) ? Yii::$app->userdata->getPropertyNameById($tenantAgreements->parent_id) : '' ?> <?php
                                        if (!empty($tenantAgreement->bed_id)) {
                                            echo '[Bed]';
                                        } else if (!empty($tenantAgreement->room_id) && empty($tenantAgreement->bed_id)) {
                                            echo '[Room]';
                                        }
                                        ?></span></li> <br />
                                <li>Rent Amount<span><?= $form->field($tenantAgreements, 'rent')->textInput(['maxlength' => true, 'placeholder' => 'Rent*', 'readonly' => 'readonly'])->label(false) ?></span></li>
                                <li>Maintenance Amount<span><?= $form->field($tenantAgreements, 'maintainance')->textInput(['maxlength' => true, 'placeholder' => 'Maintenance*', 'readonly' => 'readonly'])->label(false) ?></span></li>
                                <li>Deposit Amount<span><?= $form->field($tenantAgreements, 'deposit')->textInput(['maxlength' => true, 'placeholder' => 'Deposit*', 'readonly' => 'readonly'])->label(false) ?></span></li>
                                <li>Minimum Stay Period (In Months)<span><?= $form->field($tenantAgreements, 'min_stay')->textInput(['maxlength' => true, 'placeholder' => 'Minimum Stay*', 'readonly' => 'readonly'])->label(false) ?></span></li>
                                <li>Notice Period (In Months)<span><?= $form->field($tenantAgreements, 'notice_period')->textInput(['maxlength' => true, 'placeholder' => 'Notice Period*', 'readonly' => 'readonly'])->label(false) ?></span></li>
                                <li>Late Fee Penalty (%)<span><?= $form->field($tenantAgreements, 'late_penalty_percent')->textInput(['maxlength' => true, 'placeholder' => 'Late Penalty Percent*', 'readonly' => 'readonly'])->label(false) ?></span></li>
                                <li>Late Fee Minimum Penalty<span><?= $form->field($tenantAgreements, 'min_penalty')->textInput(['maxlength' => true, 'placeholder' => 'Minimum Penalty*', 'readonly' => 'readonly'])->label(false) ?></span></li>
                                <li>Lease Start Date<span><?= $form->field($tenantAgreements, 'lease_start_date')->textInput(['maxlength' => true, 'placeholder' => 'Lease Start Date*', 'class' => 'datepicker form-control', 'readonly' => 'readonly'])->label(false) ?></span></li>
                                <li>Lease End Date<span><?= $form->field($tenantAgreements, 'lease_end_date')->textInput(['maxlength' => true, 'placeholder' => 'Lease End Date*', 'class' => 'datepicker form-control', 'readonly' => 'readonly'])->label(false) ?></span></li>
                            </ul>
                        </div>
                        <p>&nbsp;</p>
                        <div class="row">
                            <div class="col-md-4">
                                <button style="width: 99%;border-right: 1px solid white;" type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                                    Extend Lease
                                </button>
                            </div>

                            <div class="col-md-4">
                                <button style="width: 99%;border-left: 1px solid white;" type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal2">
                                    Terminate Lease
                                </button>
                            </div>
                            
                            <div class="col-md-4">
                                <button style="width: 99%;border-left: 1px solid white;" type="button" class="btn btn-primary <?= ($tenantAgreements->notice_status != 0) ? "start-notification" : "stop-notification" ?>" onclick="return stopNotification('t', <?= $tenantAgreements->id ?>, this);">
                                    <?= ($tenantAgreements->notice_status != 0) ? "Restart Notification" : "Stop Notification" ?>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-12">
                            <div class="parmentaddress">
                                <h1>Agreement</h1>
                            </div>
                            <div class="previewDoc " id="agreement_doc">
                                <?php if (!empty($tenantAgreements->agreement_url)) {
                                    ?>			          	
                                    <iframe src="//docs.google.com/gview?url=<?= Url::home(true) . $tenantAgreements->agreement_url ?>&embedded=true" class="abc" width="100%" frameborder="0" allowfullscreen></iframe>						
    <?php
}
?>
                            </div>
                            <div class="row">

                                <div class="col-md-6 col-sm-6">
                                    <div class="pcard box" style="display: none;">
                                        <input name="ID proof" class="id_proof" type="file">
                                    </div>
                                    <div class="dl box" style="display: none;">
                                        <input name="ID proof" class="id_proof" type="file">
                                    </div>
                                </div>
                            </div>
                            &nbsp;
                            <div class="uploadbox">
                                <p class="advisortext documenttext" style="display: none;">Documents Uploaded</p>
                                <?php if (isset($tenantAgreements->agreement_url) && $tenantAgreements->agreement_url != '') {
                                    ?>
                                    <div class="previewDoc " style="display: none;">
                                        <iframe src="//docs.google.com/gview?url=<?= $tenantAgreements->agreement_url ?>&embedded=true" class="abc"></iframe>
                                    </div><br/>	
    <?php
}
?>

                                <!-- <button type="button" class="btn savechanges_submit btn33 uploadTenantAgreement">Upload new agreement</button> -->
                                <p class="advisortext documenttext">Upload New Document</p>
<?= $form->field($tenantAgreements, 'agreement_url')->fileInput(['accept' => ".doc,.docx,.pdf"])->label(false) ?>
                            </div>

                            <div class="row">
                                <div class="row col-lg-12 col-sm-12 commonbtnDiv3" style="padding-top:20px;">

                                    <!--<button type="button" class="btn savechanges_submit_contractaction">Download PDF</button>-->
                                    <a target="_blank" type="button" href="<?= (trim($tenantAgreements->agreement_url)) != '' ? '../' . $tenantAgreements->agreement_url : 'javascript:' ?>" class="" download="agreement"><?= (trim($tenantAgreements->agreement_url)) != '' ? '<button type="button" class="btn savechanges_submit_contractaction">Download PDF</button>' : '<button type="button" class="btn savechanges_submit_contractaction">No PDF</button>' ?></a>

<?= Html::submitButton('Save', ['class' => $tenantAgreements->isNewRecord ? 'btn btn-success' : 'btn savechanges_submit_contractaction']) ?> 

                                    <button type="button" class="btn savechanges_submit cancel_confirm_2">Cancel</button> 

                                </div>
                            </div>
                        </div>
                        <p>&nbsp;</p>

                    </div>


                    <!-- /.col-lg-12 -->
                </div>
<?php ActiveForm::end(); ?>


                <script>
                    function schsubmit(formid) {

                        if ($('#datesch').val() == '') {
                            alert('Please enter date.');
                            return false;

                        } else if ($('#timesch').val() == '') {
                            alert('Please enter time.');
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
                                }
                            });
                        });
                    });
                </script>



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
                                    <input  id="tags" name="userid" type="text" class="form-control" placeholder="Search for item" class="input-medium" >
                                    <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
                                    <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
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

<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modalbg modalbgpopup" role="document">
        <div class="modal-content">

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
                    $('#image_upload_preview').attr('src', e.target.result);
                } else {
                    alert("only images are allowed");
                }
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#tenantprofile-profile_image").change(function () {
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
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah')
                        .attr('src', e.target.result)
                        .width(290)
                        .height(150);
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
        // $('#tenantprofile-employment_start_date').datetimepicker({
        // minDate: new Date(),
        // changeMonth: true,
        // changeYear: true,
        // yearRange: "-0:+1",
        // numberOfMonths: 1,
        // format: 'DD-MMM-YYYY',
        // beforeShowDay: function (date) {

        // return [true, ''];
        // }
        // });
        $('#tenantprofile-employment_start_date').datetimepicker({
            format: 'DD-MMM-YYYY'
                    // beforeShowDay: function (date) {

                    // return [true, ''];
                    // }
        });
    });

    $(document).on('click', '.submit_form_anchor', function (e) {
        e.preventDefault();
        $('#' + $(this).attr('data-value')).submit();
    })

    $(document).on('click', '.uploadTenantAgreement', function () {
        $('#tenantagreements-agreement_url').click();
    })
</script>

<style type="text/css">
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
</style>
<script>
    $("#tenantagreements-lease_end_date-2").datepicker({
        minDate: '<?= date('d-M-Y', strtotime($tenantAgreements->lease_end_date . " + 1 Day")); ?>',
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 1,
        dateFormat: 'dd-M-yy',

    });

    $("#tenantagreements-lease_end_date-3").datepicker({
        //minDate: '<?= date('d-M-Y', strtotime(date('Y-m-d') . " + 1 Day")); ?>',
        maxDate: '<?= date('d-M-Y', strtotime($tenantAgreements->lease_end_date . " - 1 Day")); ?>',
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 1,
        dateFormat: 'dd-M-yy',

    });

    $("#tenantagreements-lease_terminate_date").datepicker({
        maxDate: '<?= date('d-M-Y', strtotime($tenantAgreements->lease_end_date . " - 1 Day")); ?>',
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 1,
        dateFormat: 'dd-M-yy',

    });

    function extendLeaseDate(ele, taId) {
        $(ele).hide();
        $('#tenantagreements-lease_extended_date').removeClass('hide');
    }

    function terminateLeaseDate(ele, taId) {
        $(ele).hide();
        $('#tenantagreements-lease_terminate_date').removeClass('hide');
    }

    function terminateLeaseDate_last(ele, taId) {
        if (confirm('Are you sure, you want to terminate lease?')) {
            if (confirm('Are you sure, this will delete the records from system!')) {
                $('#tenantagreements-lease_terminate_date').val('1');
                $('#w1').submit();
            }
        } else {
            $('#tenantagreements-lease_terminate_date').val('0');
        }
    }
</script>