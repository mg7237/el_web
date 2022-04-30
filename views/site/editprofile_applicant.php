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
        min-width: 430px;
    }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<div id="page-wrapper">
    <div class= "row">
        <div class="col-lg-12 wallettop">

            <?php if (Yii::$app->session->hasFlash('success')) { ?>
                <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                    <?= Yii::$app->session->getFlash('success'); ?>
                </div>   
            <?php } ?>



            <?php if (Yii::$app->session->hasFlash('error')) { ?>
                <div class="alert alert-danger fade in alert-dismissable" style="margin-top:18px;">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                    <strong>Error!</strong> <?= Yii::$app->session->getFlash('error'); ?>
                </div>   
            <?php } ?>

            <div class="parmentaddress page_heading">
                <div class="col-lg-6 col-sm-6 col-xs-offset-2">
                    <h1>Personal Details</h1>
                </div>
            </div>
            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
            <div class="col-lg-6 col-sm-6 col-xs-offset-2">

                <div class="myprofile supportperson mytop">
                    <span>Name </span>
                    <p><?= Yii::$app->userdata->getFullNameById($model->applicant_id); ?></p>			
                </div>

                <div class="lineheightbg"></div>
                <div class="parmentaddress">	
                    <h1>Contact detail </h1>


                    <?php // echo  $form->errorSummary($model);   ?>

                    <?= $form->field($model, 'email_id')->textInput(['maxlength' => true, 'placeholder' => 'Email*', 'readonly' => true]) ?>

                    <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'placeholder' => 'Phone Number*']) ?>




                </div>



                <div class="lineheightbg"></div>
                <div class="parmentaddress">
                    <h1>Permanent address</h1>


                    <?= $form->field($model, 'address_line_1')->textInput(['maxlength' => true, 'placeholder' => 'Address Line 1*']) ?>

                    <?= $form->field($model, 'address_line_2')->textInput(['maxlength' => true, 'placeholder' => 'Address Line 2*']) ?>

                    <?php
                    $states = \app\models\States::find()->all();
                    $stateData = ArrayHelper::map($states, 'id', 'name');
                    ?>
                    <?= $form->field($model, 'state')->dropDownList($stateData, ['prompt' => 'Select state', 'url' => \Yii::$app->getUrlManager()->createUrl('users/getcity')]); ?>

                    <?php
                    $cities = \app\models\Cities::find()->where(['state_id' => $model->state])->all();
                    $cityData = ArrayHelper::map($cities, 'id', 'city_name');
                    // echo $model
                    ?>
                    <?= $form->field($model, 'city')->dropDownList($cityData, ['prompt' => 'Select city', 'url' => \Yii::$app->getUrlManager()->createUrl('advisers/getregions'), $model->city => array('selected' => true)]); ?>



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
                            <div class="col-md-6 col-sm-6 col-xs-12" id="IDproof">
                            </div>
                            <div class="clearfix"></div>
                            <div class="row identity_image_preview">
                                <?php
                                foreach ($address_proofs as $key => $value) {
                                    ?>
                                    <div class="col-md-6 col-sm-6"> 
                                        <img class= "preview" alt="" src="../<?= $value->proof_document_url ?>"><p class="identityp"><?php echo Yii::$app->userdata->getProofType($value->proof_type); ?></p><a class="removeIdentity" data-id="<?= $value->id ?>">Remove</a>
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
                    <?= $form->field($model, 'emergency_contact_name')->textInput(['maxlength' => true, 'placeholder' => 'Emergency Contact Name*'])->label('Contact name') ?>

                    <?= $form->field($model, 'emergency_contact_email')->textInput(['maxlength' => true, 'placeholder' => 'Emergency Contact Email*'])->label('Contact Email') ?>

                    <?= $form->field($model, 'emergency_contact_number')->textInput(['maxlength' => true, 'placeholder' => 'Emergency Contact Number*', 'class' => 'form-control isPhone'])->label('Contact Number') ?>

                                        <!-- <p class="myprofiletext">Emergency Contact Name<span><?= $model->emergency_contact_name; ?></span></p>
                                        <p class="myprofiletext">Email<span><?= $model->emergency_contact_email; ?></span></p>
                                        <p class="myprofiletext">Emergency Number<span><?= $model->emergency_contact_number; ?></span></p> -->

                    <div class="uploadbox">	
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select class="selectpicker EMERProof">
                                <option value="">Select Emergency Proof</option>
                                <?php foreach ($proofType as $type) {
                                    ?>
                                    <option value="<?= $type->id ?>"><?= $type->name ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12" id="EMERProof">

                        </div>
                        <div class="clearfix"></div>
                        <div class="row emergency_image_preview">
                            <?php
                            foreach ($emergency_proofs as $value) {
                                ?>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <img class= "preview" alt=""  src="../<?php echo $value->proof_document_url ?>"><p><?php echo Yii::$app->userdata->getProofType($value->proof_type); ?></p><a class="removeEmergency" data-id="<?= $value->id ?>">Remove</a>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>


                </div>
                <div class="lineheightbg"></div>


                <div class="parmentaddress">

                    <h1>My Bank details</h1>
                    <?= $form->field($model, 'account_holder_name')->textInput(['maxlength' => true, 'placeholder' => 'Account Holder Name*']) ?>

                    <?= $form->field($model, 'bank_name')->textInput(['maxlength' => true, 'placeholder' => 'Bank Name*']) ?>

                    <?= $form->field($model, 'bank_branchname')->textInput(['maxlength' => true, 'placeholder' => 'Bank Branch Name*'])->label('Branch Name') ?>      	
                    <?= $form->field($model, 'bank_ifcs')->textInput(['maxlength' => true, 'placeholder' => 'IFSC Code*'])->label('IFSC Code') ?>      	
                    <div id="ifsc-error-box" style="color: #a94442; display: none;" class="help-block">Invalid IFSC code.</div>
                    <?= $form->field($model, 'bank_account_number')->textInput(['maxlength' => true, 'placeholder' => 'Bank Account Number*'])->label('Account Number') ?>      	
                    <?= $form->field($model, 'pan_number')->textInput(['maxlength' => true, 'placeholder' => 'Pan Number*']) ?>      	






                    <div class="chancelall uploadbox">
                        <?= $form->field($model, 'cancelled_check')->fileInput(['accept' => 'image/*', 'class' => 'canceled_check_files']) ?>
                        <!--<img src="<?= !empty($model->cancelled_check) ? "../" . $model->cancelled_check : Url::home(true) . 'images/doumentupload.jpg' ?>" alt="..." style="padding-bottom: 10px; width: 100%;">-->
                        <div class="questionpopup_edit_p" style="position: relative;left: 30%;top: -50px;font-weight: bold;font-size: 15px;">
                            <a href="javascript:;" data-toggle="popover" title="" data-content='<p style="width: 400px;" class="">Name on the cancelled cheque should match your name</p>' data-html="true"  data-placement="left">?</a>	
                        </div>
                        <div class="row canceled_cheque_image_preview">
                            <?php if (!empty($model->cancelled_check)) { ?>
                                <img  id="blah" src="<?= "../" . $model->cancelled_check ?>" alt="..." >
                                <p class="identityp">Cancelled Cheque</p>
                            <?php } ?>
                            <?= !empty($model->cancelled_check) ? '<a class="removeCancelledCheck" attr-id="' . $model->applicant_id . '"></a>' : '' ?>
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
                </div>
                <div class="lineheightbg"></div>
                <div class="parmentaddress">



                    <h1>My employment data <a data-placement="left" data-html="true" data-content="Enter Self employed if you are not working for any organization" title="" data-toggle="popover" href="javascript:;" data-original-title="">?</a>
                    </h1>



                    <?= $form->field($model, 'employer_name')->textInput(['maxlength' => true, 'placeholder' => 'Employer Name']) ?>

                    <?= $form->field($model, 'employee_id')->textInput(['maxlength' => true, 'placeholder' => 'Employee Id']) ?>
                    <?= $form->field($model, 'employment_email')->textInput(['maxlength' => true, 'placeholder' => 'Employment Email']) ?>

                    <?= $form->field($model, 'employment_start_date')->textInput(['maxlength' => true, 'placeholder' => 'DD-MMM-YYYY', 'class' => 'form-control datepicker']) ?>
                    <div class="chancelall uploadbox">
                        <?= $form->field($model, 'employmnet_proof_url')->fileInput(['accept' => 'image/*', 'class' => 'employment_proof_files']) ?>
                        <!--<img src="<?= !empty($model->cancelled_check) ? "../" . $model->employmnet_proof_url : Url::home(true) . 'images/doumentupload.jpg' ?>" alt="..." style="padding-bottom: 10px; width: 100%;">-->
                        <div class="questionpopup_edit_p" style="position: relative;left: 30%;top: -50px;font-weight: bold;font-size: 15px;">
                            <a href="javascript:;" data-toggle="popover" title="" data-content='<p style="width: 400px;" class="">Name on the employment proof should match your name</p>' data-html="true"  data-placement="left">?</a>	
                        </div>
                        <div class="row employment_image_preview">
                            <?php if (!empty($model->employmnet_proof_url)) { ?>
                                <img  id="blah1" src="<?= "../" . $model->employmnet_proof_url ?>" alt="..." >
                                <p class="identityp">Employment Proof</p>
                            <?php } ?>
                            <?= !empty($model->employmnet_proof_url) ? '<a class="removeCancelledCheck" attr-id="' . $model->applicant_id . '"></a>' : '' ?>
                        </div>
                    </div>
                    <br>

                </div>

                <div class="col-lg-6 col-sm-6">
                    <?= Html::submitButton('Save', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn savechanges_submit_contractaction', 'onclick' => 'return checkForIfsc(event, this);']) ?>
                </div>
                <div class="col-lg-6 col-sm-6">
                    <a style="color:#FFF" class="btn savechanges_submit cancel_confirm_2" href="<?= Url::home(true); ?>site/myprofile" type="button">Cancel</a>
                </div>


            </div>


            <div class="col-md-2 col-sm-2 common_profile_pic"style="margin-left: 10px;">
                <div class="myprofile supportperson mytop">
                    <a href="" class="thumbnail" onclick="apply_image('applicantprofile-profile_image'); return false;">
                        <img id="blah-p" src="<?= !empty($model->profile_image) ? "../" . $model->profile_image : Url::home(true) . 'images/photo.jpg' ?>" alt="...">
                    </a>
                    <?= $form->field($model, 'profile_image')->fileInput(['accept' => 'image/*', 'style' => 'display:none'])->label(false) ?>
                    <!--image src="" id="image_upload_preview"/-->

                </div>
            </div>
            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>

<script>
    var box_identity;

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

//    $('[data-toggle="popover"]').popover({
//        container: 'body'
//    });

// function disableFirst(){
// 	var address_proof_text1 = $('input[name="address_proof_text"]').val();
// 	var identity_proof_text1 = $('input[name="identity_proof_text"]').val();
// 	$('.IDproof option').removeAttr('disabled');
// 	if(address_proof_text1!=''){
// 		$('.IDproof option[value="'+address_proof_text1+'"]').attr('disabled','true');
// 	}
// 	if(identity_proof_text!=''){
// 		$('.IDproof option[value="'+identity_proof_text1+'"]').disabled('	','true');
// 	}
// }

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

    $(document).on('change', '.canceled_check_files', function (e) {
        readurlIdentity2(this);

    });

    $(document).on('change', '.employment_proof_files', function (e) {
        readurlIdentity3(this);

    });

    $(document).on('change', '.emergency_files', function (e) {
        readurlIdentity1(this);

    });

    $(function () {
        $('#applicantprofile-employment_start_date').datepicker({
            startDate: '01/01/1970',
            changeMonth: true,
            changeYear: true,
            beforeShowDay: onlyPastdays
        }
        );
    });

    function onlyPastdays(date) {
        var day = date.getDay();
        var today = new Date();
        today.setDate(today.getDate());
        return [(date < today), ''];
    }

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
                if (e.target.result != '') {
                    var target_image = e.target.result.substr(0, 100);
                    console.log("hello" + target_image);
                    var image_check = check_image(e.target.result);
                    if (image_check == 'image') {
                        $('.emergency_image_preview').append('<div class="col-md-6 col-sm-6"><img class= "preview" alt=""src="' + e.target.result + '"><a class="removeLocal" id="' + attrclass + '"></a><p class="emergencyp">' + $('.EMERProof option:selected').text() + '</p></div>');
                        // $('.EMERProof').val('');
                        $('.EMERProof').prop('selectedIndex', 0);
                        $('.emergency_box').hide();
                    } else {
                        alert('Only images are allowed');
                        $('.EMERProof').val('');
                        $('#EMERProof div.' + attrclass).remove();
                        $('a#' + attrclass).closest('.col-md-6').remove();
                    }
                } else {
                    alert('Only images are allowed');
                    $('.EMERProof').val('');
                    $('#EMERProof div.' + attrclass).remove();
                    $('a#' + attrclass).closest('.col-md-6').remove();
                }
            }


            reader.readAsDataURL(input.files[0]);
        }
    }

    function readurlIdentity2(input) {
        if (input.files && input.files[0]) {
            var attrclass = $(input).attr('attr-class');
            var reader = new FileReader();

            reader.onload = function (e) {
                if (e.target.result != '') {
                    var target_image = e.target.result.substr(0, 100);
                    console.log("hello" + target_image);
                    var image_check = check_image(e.target.result);
                    if (image_check == 'image') {
                        //$('.canceled_cheque_image_preview').html('<div class="col-md-6 col-sm-6"><img class= "preview" alt=""src="' + e.target.result + '"><a class="removeLocal" id="' + attrclass + '"></a><p class="emergencyp">' + $('.EMERProof option:selected').text() + '</p></div>');
                        $('.canceled_cheque_image_preview').html('<div class="col-md-6 col-sm-6"><img class= "preview" alt=""src="' + e.target.result + '"><a class="removeLocal" id="' + attrclass + '"></a><p class="identityp">Cancelled Cheque</p></div>');
                    } else {
                        alert('Only images are allowed');
                    }
                } else {
                    alert('Only images are allowed');
                }
            }


            reader.readAsDataURL(input.files[0]);
        }
    }

    function readurlIdentity3(input) {
        if (input.files && input.files[0]) {
            var attrclass = $(input).attr('attr-class');
            var reader = new FileReader();

            reader.onload = function (e) {
                if (e.target.result != '') {
                    var target_image = e.target.result.substr(0, 100);
                    console.log("hello" + target_image);
                    var image_check = check_image(e.target.result);
                    if (image_check == 'image') {
                        //$('.canceled_cheque_image_preview').html('<div class="col-md-6 col-sm-6"><img class= "preview" alt=""src="' + e.target.result + '"><a class="removeLocal" id="' + attrclass + '"></a><p class="emergencyp">' + $('.EMERProof option:selected').text() + '</p></div>');
                        $('.employment_image_preview').html('<div class="col-md-6 col-sm-6"><img class= "preview" alt=""src="' + e.target.result + '"><a class="removeLocal" id="' + attrclass + '"></a><p class="identityp">Employment Proof</p></div>');
                    } else {
                        alert('Only images are allowed');
                    }
                } else {
                    alert('Only images are allowed');
                }
            }


            reader.readAsDataURL(input.files[0]);
        }
    }

// $(document).on('change','#IDProof1',function(){
// 	$('input[name="address_proof_text"]').val($('.IDproof').val());
// 	$('.addressp').text($('.IDproof option:selected').text());
// 	readURL1(this);
// })

// $(document).on('change','#IDProof2',function(){
// 	$('input[name="identity_proof_text"]').val($('.IDproof').val());
// 	$('.identityp').text($('.IDproof option:selected').text());	
// 	readURL2(this);
// })

// $(document).on('click','#passport',function(){
// 	box_identity=$('.pcard.box');
// 	$(this).addClass('selectedImage');
// 	$('#dl').removeClass('selectedImage');
// });

// $(document).on('click','#dl',function(){
// 	box_identity=$('.dl.box');
// 	$(this).addClass('selectedImage');
// 	$('#passport').removeClass('selectedImage');
// });

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

                    $('#blah-p').attr('src', e.target.result);
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

    // $("#IDProof2").change(function () {
    //     readURL1(this);
    // });
    function readURL2(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#dl').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    // $("#IDproof").change(function () {
    //     readURL2(this);
    // });
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
    img.selectedImage {
        border: 2px solid #347c17;
        margin-top: 4px;
    }
</style>
<script type="text/javascript">

    $("#imgInp").change(function () {
        readURL(this);
    });

    $(document).on("click", ".cancel_confirm_2", function (e) {
        e.preventDefault();
        var element = $(this);
        var confirm_var = confirm('Are you sure you want to cancel ?');
        if (confirm_var == true) {
            window.location.href = (element.attr('href'));

        }
    });
</script>