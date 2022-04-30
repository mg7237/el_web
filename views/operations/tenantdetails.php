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
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<div id="page-wrapper">
    <div class="row">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#home">Tenant information </a></li>
            <li><a data-toggle="tab" href="#menu1">Tenant Contract</a></li>
        </ul>

        <div class="tab-content">
            <div id="home" class="tab-pane fade in active">

                <h4>Tenant information</h4>
                <div class="row">
                    <div class="col-lg-12">
                        <?php if (Yii::$app->session->hasFlash('success')) { ?>
                            <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                                <?= Yii::$app->session->getFlash('success'); ?>
                            </div>   
                        <?php } ?>

                        <div class="row">
                            <div class="col-lg-7 col-xs-offset-2">

                                <div class="myprofile supportperson mytop">
                                    <span>Name </span>
                                    <p><?= Yii::$app->userdata->getFullNameById($model->tenant_id); ?></p>

                                </div>
                                <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

                                <div class="parmentaddress">
                                    <h1>Contact detail </h1>

                                    <?= $form->field($modelLeads, 'login_id')->textInput(['maxlength' => true, 'placeholder' => 'Email*']) ?>

                                    <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'placeholder' => 'Phone Number*']) ?>

                                    <?= $form->field($model, 'profile_image')->fileInput(['accept' => 'image/*', 'style' => 'display:none'])->label(false) ?>
                                </div>

                                <div class="lineheightbg"></div>
                                <div class="parmentaddress">
                                    <h1>Permanent address</h1>
                                    <?= $form->field($model, 'address_line_1')->textInput(['maxlength' => true, 'placeholder' => 'Address line one*']) ?>

                                    <?= $form->field($model, 'address_line_2')->textInput(['maxlength' => true, 'placeholder' => 'Adddress line two*']) ?>

                                    <?php
                                    $states = \app\models\States::find()->all();
                                    $stateData = ArrayHelper::map($states, 'id', 'name');
                                    ?>
                                    <?= $form->field($model, 'state')->dropDownList($stateData, ['prompt' => 'Select state', 'url' => \Yii::$app->getUrlManager()->createUrl('users/getcity')]); ?>

                                    <?php
                                    $cities = \app\models\Cities::find()->where(['state_id' => $model->state])->all();
                                    $cityData = ArrayHelper::map($cities, 'id', 'city_name');
                                    ?>
                                    <?= $form->field($model, 'city')->dropDownList($cityData, ['prompt' => 'Select city', 'url' => \Yii::$app->getUrlManager()->createUrl('advisers/getregions'), $model->city => array('selected' => true)]); ?>

                                    <?php
                                    $regions = \app\models\Regions::find()->where(['city_id' => $model->city])->all();
                                    $cityData = ArrayHelper::map($regions, 'id', 'name');
                                    ?>
                                    <?= $form->field($model, 'region')->dropDownList($cityData, ['prompt' => 'Select region']); ?>

                                    <?= $form->field($model, 'pincode')->textInput(['maxlength' => true, 'placeholder' => 'Pincode*']) ?>


                                    <div class="lineheightbg"></div>



                                    <div class="uploadbox">
                                        <div class="changepassword">
                                            <h1 class="advisortext">Identity and Address Proof Documents</h1></div></div>
                                    <div class="row">
                                        <div class="uploadbox">	
                                            <div class="col-md-6">
                                                <select class="selectpicker IDproof">
                                                    <option selected="">Choose file</option>
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
                                                        <img class= "preview" alt="" src="<?= $value->proof_document_url ?>"><p class="identityp"><?php echo Yii::$app->userdata->getProofType($value->proof_type); ?></p>
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
                                    <?= $form->field($model, 'emergency_contact_name')->textInput(['maxlength' => true, 'placeholder' => 'Emergency Contact Name*']) ?>

                                    <?= $form->field($model, 'emer_contact')->textInput(['maxlength' => true, 'placeholder' => 'Emergency Contact Email*']) ?>

                                    <?= $form->field($model, 'emergency_contact_number')->textInput(['maxlength' => true, 'placeholder' => 'Emergency Contact Number*']) ?>
                                    <div class="uploadbox">	
                                        <div class="col-md-6">
                                            <select class="selectpicker EMERProof">
                                                <option selected="">Choose file</option>
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
                                                    <img class= "preview" alt=""  src="<?php echo $value->proof_document_url ?>"><p><?php echo Yii::$app->userdata->getProofType($value->proof_type); ?></p>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>


                                </div>
                                <div class="lineheightbg"></div>
                                <div class="changepassword">
                                    &nbsp;
                                    <h1>Bank details</h1>
                                    <?= $form->field($model, 'account_holder_name')->textInput(['maxlength' => true, 'placeholder' => 'Account Holder Name*', 'class' => 'form-control isCharacter']) ?>

                                    <?= $form->field($model, 'bank_name')->textInput(['maxlength' => true, 'placeholder' => 'Bank Name*', 'class' => 'form-control isCharacter']) ?>

                                    <?= $form->field($model, 'bank_branchname')->textInput(['maxlength' => true, 'placeholder' => 'Bank Branch Name*', 'class' => 'form-control isCharacter']) ?>      	
                                    <?= $form->field($model, 'bank_ifcs')->textInput(['maxlength' => true, 'placeholder' => 'Bank Ifcs*']) ?>      	
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
                                    &nbsp;
                                    <h1>Employement Data</h1>
                                    <div class="questionpopup11"><a href="javascript:;" data-toggle="popover" title="Lorem" data-content='<p class="circlebox">if you are not employee then put self-employment' data-html="true"  data-placement="left">?</a></div>

                                    <?= $form->field($model, 'employer_name')->textInput(['maxlength' => true, 'placeholder' => 'employer_name*']) ?>

                                    <?= $form->field($model, 'employee_id')->textInput(['maxlength' => true, 'placeholder' => 'employee_id*']) ?>
                                    <?= $form->field($model, 'employment_email')->textInput(['maxlength' => true, 'placeholder' => 'employment_email*']) ?>

                                    <?= $form->field($model, 'employment_start_date')->textInput(['maxlength' => true, 'placeholder' => 'DD-MM-YYYY*']) ?>

                                    <?= $form->field($model, 'employment_proof_url')->fileInput(['accept' => 'image/*']) ?>
                                </div>

                                <div class="lineheightbg"></div>



                                <div class="lineheightbg"></div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <?= Html::submitButton('Save', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn savechanges_submit_contractaction']) ?>

                                    </div>
                                    <div class="col-lg-6">
                                        <a style="color:#FFF" class="btn savechanges_submit cancel_confirm" href="<?= Url::home(true); ?>sales/applicants" type="button">Cancel</a>

                                    </div> 
                                </div>

                                <?php ActiveForm::end(); ?>


                            </div>

                            <div class="col-md-2 col-sm-2"style="margin-left: 10px;">
                                <div class="myprofile supportperson mytop">
                                    <a href="" class="thumbnail" onclick="apply_image('tenantprofile-profile_image'); return false;">
                                        <img id="blah" src="<?= !empty($model->profile_image) ? $model->profile_image : Url::home(true) . 'images/photo.jpg' ?>" alt="...">
                                    </a>
                                </div>
                            </div>
                        </div>



                        <!-- /.col-lg-12 -->
                    </div>

                </div>

            </div>
            <div id="menu1" class="tab-pane fade">
                <h4>Tenant Contract</h4>
                <?php $form = ActiveForm::begin(); ?>
                <div class="row">
                    <div class="col-lg-8 col-sm-8 col-xs-offset-1">
                        <div class="formtexttable">
                            <ul>
                                <li>Tenant ID<span><?= $tenantAgreements->tenant_id ?></span></li>
                                <li>Property ID<span><?= isset($tenantAgreements->parent_id) ? $tenantAgreements->parent_id : '' ?></span></li>
                                <li>Rent amount<span><?= $form->field($tenantAgreements, 'rent')->textInput(['maxlength' => true, 'placeholder' => 'Rent*'])->label(false) ?></span></li>
                                <li>Maintenance amount<span><?= $form->field($tenantAgreements, 'maintainance')->textInput(['maxlength' => true, 'placeholder' => 'Maintainance*'])->label(false) ?></span></li>
                                <li>Deposit amount<span><?= $form->field($tenantAgreements, 'deposit')->textInput(['maxlength' => true, 'placeholder' => 'Deposit*'])->label(false) ?></span></li>
                                <li>Minimum stay period (in months)<span><?= $form->field($tenantAgreements, 'min_stay')->textInput(['maxlength' => true, 'placeholder' => 'Minimum Stay*'])->label(false) ?></span></li>
                                <li>Notice period (in months)<span><?= $form->field($tenantAgreements, 'notice_period')->textInput(['maxlength' => true, 'placeholder' => 'Notice Period*'])->label(false) ?></span></li>
                                <li>Late fee penalty (%)<span><?= $form->field($tenantAgreements, 'late_penalty_percent')->textInput(['maxlength' => true, 'placeholder' => 'Late Penalty Percent*'])->label(false) ?></span></li>
                                <li>Lease start date<span><?= $form->field($tenantAgreements, 'lease_start_date')->textInput(['maxlength' => true, 'placeholder' => 'Lease Start Date*'])->label(false) ?></span></li>
                                <li>Lease termination date<span><?= $form->field($tenantAgreements, 'lease_end_date')->textInput(['maxlength' => true, 'placeholder' => 'Lease End Date*'])->label(false) ?></span></li>
                                <li>Late fee minimum penalty<span><?= $form->field($tenantAgreements, 'min_penalty')->textInput(['maxlength' => true, 'placeholder' => 'Minimum Penalty*'])->label(false) ?></span></li>

                            </ul>

                        </div>
                        <div class="formtexttable">
                            <div class="parmentaddress">
                                <h1>Agreement</h1></div>
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <select class="selectpicker">
                                        <option selected="">Choose file</option>
                                        <option value="pcard">PAN Card</option>
                                        <option value="dl">DL</option>
                                    </select>
                                </div>
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
                                <p class="advisortext documenttext">Documents Uploaded</p>
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 bannerimg" style="padding:2px;"><img src="images/doumentupload.jpg" alt=""><p>PAN card</p></div><div class="col-md-6 col-sm-6 bannerimg" style="padding:2px;"><img src="images/doumentupload.jpg" alt=""><p>DL</p></div>
                                </div>
                                <p></p>
                                <button type="button" class="btn savechanges_submit">Upload new agreement</button>
                            </div>

                            <div class="row">
                                <div class="row col-lg-12 col-sm-12" style="padding-top:20px;">
                                    <div class="col-lg-6 col-sm-6">
                                        <?= Html::submitButton('Save', ['class' => $tenantAgreements->isNewRecord ? 'btn btn-success' : 'btn savechanges_submit_contractaction']) ?> </div>
                                    <div class="col-lg-6 col-sm-6">
                                        <button type="button" class="btn savechanges_submit cancel_confirm">Cancel</button> 
                                    </div>

                                </div>
                            </div>
                            <p>&nbsp;</p>
                            <button type="button" class="btn savechanges_submit">Download PDF</button>
                        </div>
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

                        $.ajax({
                            type: "POST",
                            url: 'saveschdule',
                            data: $("#sch" + formid).serialize(), //only input
                            success: function (response) {

                                if (response == 'done') {

                                    alert(' Schedule updated');
                                } else {
                                    alert('Please try again');
                                }
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


                            $.ajax({
                                type: "POST",
                                url: form.attr("action"),
                                data: $("#comment_form").serialize(), //only input
                                success: function (response) {

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
        $('#IDproof').append('<div class=" identity_file_' + count + ' identity_box box col-md-12 col-sm-12"><input type="file" name="address_proof[' + $(this).val() + '][]" attr-type="' + $('.IDproof option:selected').text() + '" class="identity_files">');


    });

    $(document).on('change', '.identity_files', function (e) {
        readurlIdentity(this);

    });


    $(document).on('change', '.EMERProof', function () {
        var count = $('#EMERProof .box').length;
        $('.emergency_box').hide();
        $('#EMERProof').append('<div class=" emergency_file_' + count + ' emergency_box box col-md-12 col-sm-12"><input type="file" name="emergency_proof[' + $(this).val() + '][]" attr-type="' + $('.EMERProof option:selected').text() + '" class="emergency_files">');

    });

    $(document).on('change', '.emergency_files', function (e) {
        readurlIdentity1(this);

    });

    function readurlIdentity(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                console.log("hello");
                $('.identity_image_preview').append('<div class="col-md-6"><img class= "preview" alt=""src="' + e.target.result + '"><p class="identityp">' + $('.IDproof option:selected').text() + '</p></div>');
                $('.IDproof').prop('selectedIndex', 0);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    function readurlIdentity1(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                console.log("hello");
                $('.emergency_image_preview').append('<div class="col-md-6"><img class= "preview" alt=""src="' + e.target.result + '"><p class="emergencyp">' + $('.EMERProof option:selected').text() + '</p></div>');
                // $('.EMERProof').val('');
                $('.EMERProof').prop('selectedIndex', 0);
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
                $('#image_upload_preview').attr('src', e.target.result);
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

    $(document).ready(function () {
        $(".scheduledDatePicker").datepicker({
            minDate: 0,
            changeMonth: true,
            changeYear: true,
            yearRange: "-0:+1",
            numberOfMonths: 1,
            beforeShowDay: function (date) {

                if (date.getDay() == 3) {
                    return [false, ''];
                }
                return [true, ''];
            }
        });

        $(".hasDatepicker").datepicker({
            minDate: 0,
            changeMonth: true,
            changeYear: true,
            yearRange: "-0:+1",
            numberOfMonths: 1,
            beforeShowDay: function (date) {

                if (date.getDay() == 3) {
                    return [false, ''];
                }
                return [true, ''];
            }
        });
        $('#tenantprofile-employment_start_date').datepicker({
            minDate: 0,
            changeMonth: true,
            changeYear: true,
            yearRange: "-0:+1",
            numberOfMonths: 1,
            beforeShowDay: function (date) {

                return [true, ''];
            }
        });
    });

    $(document).on('click', '.submit_form_anchor', function (e) {
        e.preventDefault();
        $('#' + $(this).attr('data-value')).submit();
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