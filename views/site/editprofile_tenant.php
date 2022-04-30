<?php
$this->title = 'PMS- Edit profile';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
?>
<style>
    .uploadbox img {
        width: 96%;
        height: 200px;
    }

    .popover{
        min-width: 470px;
    }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<div id="page-wrapper" class= "autoheight">
    <div class="row">
        <div class="col-lg-10 col-lg-md col-sm-10 col-xs-offset-2 wallettop"> 

            <?php if (Yii::$app->session->hasFlash('success')) { ?>
                <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                    <?= Yii::$app->session->getFlash('success'); ?>
                </div>   
            <?php } ?>


            <div class="parmentaddress">
                <h1>Personal Details</h1>
            </div>
            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
            <div class="col-lg-7 col-sm-8 col-md-7">

                <div class="myprofile supportperson mytop">
                    <span>Name </span>
                    <p><?= Yii::$app->userdata->getFullNameById($model->tenant_id); ?></p>

                </div>


                <div class="lineheightbg"></div>
                <div class="parmentaddress">


                    <h1>Contact detail </h1>


                    <?php // echo  $form->errorSummary($model);  ?>

                    <?= $form->field($UsersModel, 'login_id')->textInput(['maxlength' => true, 'readonly' => true]) ?>
                    <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'placeholder' => 'Phone Number*']) ?>




                </div>



                <div class="lineheightbg"></div>
                <div class="parmentaddress">
                    <h1>Permanent address</h1>

                    <?= $form->field($model, 'address_line_1')->textInput(['maxlength' => true, 'placeholder' => 'Address Line 1']) ?>

                    <?= $form->field($model, 'address_line_2')->textInput(['maxlength' => true, 'placeholder' => 'Address Line 2']) ?>

                    <?php
                    $states = \app\models\States::find()->all();
                    $stateData = ArrayHelper::map($states, 'id', 'name');
                    ?>
                    <?= $form->field($model, 'state')->dropDownList($stateData, ['prompt' => 'Select state', 'url' => \Yii::$app->getUrlManager()->createUrl('users/getcity')]); ?>

                    <?php
                    $cities = \app\models\Cities::find()->where(['state_id' => $model->state])->all();
                    $cityData = ArrayHelper::map($cities, 'id', 'city_name');
                    ?>
                    <?= $form->field($model, 'city')->dropDownList($cityData, ['prompt' => 'Select city', 'url' => \Yii::$app->getUrlManager()->createUrl('advisers/getregions')]); ?>


                    <?= $form->field($model, 'pincode')->textInput(['maxlength' => true, 'placeholder' => 'Pincode*']) ?>
                </div>
                <div class="lineheightbg"></div>	
                <div class="parmentaddress">
                    <h1>Identity And Address Proof Documents </h1>

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
                            <div class="clearfix"></div><br>
                            <div class="row identity_image_preview">
                                <?php
                                foreach ($address_proofs as $key => $value) {
                                    ?>
                                    <div class="col-md-6 col-sm-6">
                                        <img class= "preview" alt="" src="../<?= $value->proof_document_url ?>"><p class="identityp doc_title"><?php echo Yii::$app->userdata->getProofType($value->proof_type); ?></p><a class="removeIdentity" data-id="<?= $value->id ?>">Remove</a>
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
                    <h1>My emergency contact detail </h1>
                    <?= $form->field($model, 'emer_contact')->textInput(['maxlength' => true, 'placeholder' => 'Emergency Contact Name*']) ?>
                    <div class="uploadbox">	
                        <div class="col-md-6 col-sm-6">
                            <select class="selectpicker EMERProof">
                                <option value="">Select Emergency ID Proof</option>
                                <?php foreach ($proofType as $type) {
                                    ?>
                                    <option value="<?= $type->id ?>"><?= $type->name ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6 col-sm-6" id="EMERProof">

                        </div>

                        <div class="clearfix"></div><br>
                        <div class="row emergency_image_preview">
                            <?php
                            foreach ($emergency_proofs as $value) {
                                ?>
                                <div class="col-md-6 col-sm-6">
                                    <img class= "preview" alt=""  src="../<?php echo $value->proof_document_url ?>"><p class="doc_title"><?php echo Yii::$app->userdata->getProofType($value->proof_type); ?></p><a class="removeEmergency" data-id="<?= $value->id ?>">Remove</a>
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
                    <?= $form->field($model, 'account_holder_name')->textInput(['maxlength' => true, 'placeholder' => 'Account Holder Name*']) ?>

                    <?= $form->field($model, 'bank_name')->textInput(['maxlength' => true, 'placeholder' => 'Bank Name*']) ?>

                    <?= $form->field($model, 'bank_branchname')->textInput(['maxlength' => true, 'placeholder' => 'Bank Branch Name*']) ?>      	
                    <?= $form->field($model, 'bank_ifcs')->textInput(['maxlength' => true, 'placeholder' => 'IFSC Code*'])->label('IFSC Code') ?>
                    <div id="ifsc-error-box" style="color: #a94442; display: none;" class="help-block">Invalid IFSC code.</div>
                    <?= $form->field($model, 'account_number')->textInput(['maxlength' => true, 'placeholder' => 'Account Number*']) ?>      	
                    <?= $form->field($model, 'pan_number')->textInput(['maxlength' => true, 'placeholder' => 'Pan Number*']) ?>      	



                    <!--p class="circlebox">The cheque should carry name of the account holder and matching applicant name</p-->
                    <div class="chancelall">
                        <?php //echo $form->field($model, 'cancelled_check')->fileInput(['accept' => 'image/*']) ?>
                        <div class="form-group field-tenantprofile-cancelled_check">
                            <label class="control-label" for="tenantprofile-cancelled_check">Cancelled cheque</label>
                            <div class="questionpopup_edit_p cancelled_check_new" style="font-weight: bold;font-size: 15px; display: inline-block;">
                                <a href="javascript:;" data-toggle="popover" title="" data-content='<p style="width: 400px;" class="circlebox1">Name on the cancelled cheque should match your name.' data-html="true"  data-placement="left">?</a>	
                            </div>
                            <input type="hidden" name="TenantProfile[cancelled_check]" value=""><input type="file" id="tenantprofile-cancelled_check" name="TenantProfile[cancelled_check]" accept="image/*">

                            <div class="help-block"></div>
                        </div>
                    </div>
                    <?php if (!empty($model->cancelled_check)) { ?>
                        <img src="<?php echo "../" . $model->cancelled_check; ?>" alt="..." style="padding-bottom: 10px; width: 100%;">
                    <?php } ?>
                </div>
                <div class="lineheightbg"></div>
                <div class="parmentaddress">


                    <div class="chancelall">
                        <h1 style="display:inline;"> employment data</h1>
                        <div class="questionpopup_edit_p cancelled_check_new" style="font-weight: bold;font-size: 15px; display: inline-block;">
                            <a href="javascript:;" data-toggle="popover" title="" data-content='<p style="width: 450px;" class="circlebox1">Enter Self employed if you are not working for any organization.' data-html="true"  data-placement="left">?</a>	
                        </div>
                    </div>
                    <br />
                    <?= $form->field($model, 'employer_name')->textInput(['maxlength' => true, 'placeholder' => 'Employer Name']) ?>

                    <?= $form->field($model, 'employee_id')->textInput(['maxlength' => true, 'placeholder' => 'Employee ID']) ?>
                    <?= $form->field($model, 'employment_email')->textInput(['maxlength' => true, 'placeholder' => 'Employment Email']) ?>

                    <?= $form->field($model, 'employment_start_date')->textInput(['placeholder' => 'DD-MMM-YYYY', 'class' => 'form-control datepicker_default']) ?>

                    <?= $form->field($model, 'employment_proof_url')->fileInput(['accept' => 'image/*']) ?>
                    <?php if (!empty($model->employment_proof_url)) { ?>
                        <img src="<?php echo "../" . $model->employment_proof_url; ?>" alt="..." style="padding-bottom: 10px; width: 100%;">
                    <?php } ?>
                </div>

                <!--div class="lineheightbg"></div>
                <div class="parmentaddress">
                    <h1>Support Person Details</h1>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Support Person Name</label>&nbsp;&nbsp;<span data-target=".bs-modal-sm" data-toggle="modal" class="glyphicon glyphicon-search"></span>
                        <input type="text" placeholder="Support Person Name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Support Email</label>
                        <input type="text" placeholder="Support Person Email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Support Phone</label>
                        <input type="text" placeholder="Support Person Number" class="form-control">
                    </div>
                </div-->
                <br>
                <div class="col-lg-6 col-sm-6">
                    <?= Html::submitButton('Save', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn savechanges_submit_contractaction', 'onclick' => 'return checkForIfsc(event, this);']) ?>
                </div>
                <div class="col-lg-6 col-sm-6">
                    <a style="color:#FFF" class="btn savechanges_submit cancel_confirm_2" href="<?= Url::home(true); ?>site/myprofile" type="button">Cancel</a>
                </div>


            </div>
            <div style="margin-left:10px;" class="col-md-2 col-sm-2 myprofile common_profile_pic"> 
                <a class="thumbnail" href="#">
                    <img id="blah" onclick="apply_image('tenantprofile-profile_image'); return false;" alt="..." src="<?= !empty($model->profile_image) ? "../" . $model->profile_image : Url::home(true) . 'images/photo.jpg' ?>">
                </a>

                <image src="" id="image_upload_preview"/>
                <?= $form->field($model, 'profile_image')->fileInput(['accept' => 'image/*', 'style' => 'display:none'])->label(false) ?>
            </div>
            <?php ActiveForm::end(); ?>

        </div>
    </div>

</div>

<script>
    function checkForIfsc(e, ele) {
        e.preventDefault();
        var ifsc = $('#tenantprofile-bank_ifcs').val();
        $.ajax({
            url: 'checkifsc',
            type: 'post',
            data: {'ifsc': ifsc},
            success: function (res) {
                if (res == 'error') {
                    $('#tenantprofile-bank_ifcs').css('border', '1px solid #a94442');
                    $('#ifsc-error-box').css('display', 'block');
                    $(document).scrollTop($('#tenantprofile-bank_ifcs').position().top + 35);
                    return false;
                } else {
                    $('#tenantprofile-bank_ifcs').css('border', '1px solid #c7c7c7');
                    $('#ifsc-error-box').css('display', 'none');
                    $('#w0').submit();
                }
            },
            error: function (a, b, c) {
                alert("Some error occured at server, please try again later.");
            }
        });
    }

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
                        $('.identity_image_preview').append('<div class="col-md-6 col-sm-6"><img class= "preview" alt=""src="' + e.target.result + '"><a class="removeLocal" id="' + attrclass + '"></a><p class="identityp">' + $('.IDproof option:selected').text() + '</p></div>');
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
                        $('.emergency_files').hide();
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
    // function readURL(input) {
    // if (input.files && input.files[0]) {
    // var reader = new FileReader();

    // reader.onload = function (e) {
    // var image_check=check_image(e.target.result);
    // console.log(image_check);
    // if(image_check=='image'){
    // $('#image_upload_preview').attr('src', e.target.result);
    // }else{
    // alert("only images are allowed");
    // }
    // }

    // reader.readAsDataURL(input.files[0]);
    // }
    // }

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

    $("#tenantprofile-address_proof").change(function () {
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

    $("#tenantprofile-identity_proof").change(function () {
        readURL2(this);
    });
    // function readURL3(input) {
    // if (input.files && input.files[0]) {
    // var reader = new FileReader();

    // reader.onload = function (e) {
    // $('#dle').attr('src', e.target.result);
    // }

    // reader.readAsDataURL(input.files[0]);
    // }
    // }

    $("#tenantprofile-emer_contact_indentity").change(function () {
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

    $("#tenantprofile-emer_contact_address").change(function () {
        readURL4(this);
    });
</script>
<script type="text/javascript">
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
    $(document).on("click", ".cancel_confirm_2", function (e) {
        e.preventDefault();
        var element = $(this);
        var confirm_var = confirm('Are you sure you want to cancel ?');
        if (confirm_var == true) {
            window.location.href = (element.attr('href'));

        }
    });
</script>
