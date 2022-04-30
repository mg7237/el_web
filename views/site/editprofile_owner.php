<?php
$this->title = 'PMS- Edit profile';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

//echo "hello";die;
/* if(isset($profile_image))
  {
  echo "<pre>";print_r($profile_image);echo "</pre>";die;
  } */
?>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> -->
<style>
    .questionpopup_edit_p.cancelled_check_new {
        float: right;
        font-size: 20px;
        margin-right: 75%;
        margin-top: -67px;
    }
    .circlebox1 {
        text-align: left !important;
    }
</style>

<div id="page-wrapper">
    <div class="row">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

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

            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
            <div class="col-lg-6 col-md-6 col-sm-7 col-xs-7 col-xs-offset-1">
                <div class="parmentaddress">
                    <h1>Personal Details</h1>
                </div>
                <div class="myprofile supportperson mytop">
                    <span>Name </span>
                    <p><?= Yii::$app->userdata->getFullNameById($model->owner_id); ?></p>

                </div>


                <div class="parmentaddress">
                    <div class="lineheightbg"></div>	

                    <h1>Contact detail </h1>


                    <?php // echo  $form->errorSummary($model);  ?>
                    <?php // echo  $form->errorSummary($modelUser);   ?>

                    <?= $form->field($modelUser, 'login_id')->textInput(['maxlength' => true, 'placeholder' => 'Email*', 'readonly' => true]) ?>

                    <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'placeholder' => 'Phone Number*', 'class' => 'isPhone form-control parmentaddressinput']) ?>





                </div>



                <div class="lineheightbg"></div>
                <div class="parmentaddress">
                    <h1>Permanent address</h1>

                    <?= $form->field($model, 'address_line_1')->textInput(['maxlength' => true, 'placeholder' => 'Address Line 1*', 'class' => 'form-control parmentaddressinput']) ?>
                    <?= $form->field($model, 'address_line_2')->textInput(['maxlength' => true, 'placeholder' => 'Address Line 2*', 'class' => 'form-control parmentaddressinput']) ?>

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

                    <?php
                    $regions = \app\models\Regions::find()->where(['city_id' => $model->city])->all();
                    $cityData = ArrayHelper::map($regions, 'id', 'name');
                    ?>
        <!--<?//= $form->field($model, 'region')->dropDownList($cityData,['prompt'=>'Select region']);  ?>-->

                    <?= $form->field($model, 'pincode')->textInput(['maxlength' => true, 'placeholder' => 'Pincode*', 'class' => 'isNumber form-control parmentaddressinput']) ?>
                </div>
                <div class="lineheightbg"></div>

                <div class="parmentaddress">
                    <h1>Identity And Address Proof Documents </h1>

                    <div class="uploadbox">	
                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <select class="selectpicker IDproof">
                                <option value="">Select Address Proof</option>
                                <?php foreach ($proofType as $type) {
                                    ?>
                                    <option value="<?= $type->id ?>"><?= $type->name ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-6 col-sm-6 col-xs-6" id="IDproof">
                        </div>
                        <div class="clearfix"></div>
                        <div class="row identity_image_preview">
                            <?php
                            foreach ($address_proofs as $key => $value) {
                                ?>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <img class= "preview" alt="" src="../<?= $value->proof_document_url ?>"><p class="identityp doc_title"><?php echo Yii::$app->userdata->getProofType($value->proof_type); ?></p><a class="removeIdentity" data-id="<?= $value->id ?>">Remove</a>
                                </div>
                                <?php
                            }
                            ?>

                        </div>


                    </div>

                </div>


                <div class="lineheightbg"></div>
                <div class="">
                    <h1 style="color: #347c17;font-size: 17px;">My emergency contact detail </h1>
                    <?= $form->field($model, 'emer_contact')->textInput(['maxlength' => true, 'placeholder' => 'Name'])->label('Name') ?>

                    <?= $form->field($model, 'emergency_contact_email')->textInput(['maxlength' => true, 'placeholder' => 'Email'])->label('Email') ?>

                    <?= $form->field($model, 'emergency_contact_number')->textInput(['maxlength' => true, 'placeholder' => 'Contact Number', 'class' => 'isPhone form-control'])->label('Contact Number') ?>

                </div>
                <div class="lineheightbg"></div>


                <div class="changepassword">

                    <h1>My Bank details</h1>
                    <?= $form->field($model, 'account_holder_name')->textInput(['maxlength' => true, 'placeholder' => 'Account Holder Name*']) ?>

                    <?= $form->field($model, 'bank_name')->textInput(['maxlength' => true, 'placeholder' => 'Bank Name*']) ?>

                    <?= $form->field($model, 'bank_branchname')->textInput(['maxlength' => true, 'placeholder' => 'Bank Branch Name*']) ?>      	
                    <?= $form->field($model, 'bank_ifcs')->textInput(['maxlength' => true, 'placeholder' => 'IFSC Code*'])->label('IFSC Code') ?>      	
                    <div id="ifsc-error-box" style="color: #a94442; display: none;" class="help-block">Invalid IFSC code.</div>
                    <?= $form->field($model, 'bank_account_number')->textInput(['maxlength' => true, 'placeholder' => 'Account Number*', 'class' => 'isNumber form-control']) ?>      	
                    <?= $form->field($model, 'pan_number')->textInput(['maxlength' => true, 'placeholder' => 'Pan Number*']) ?>      	


                    <div class="uploadbox">
                        <div class="chancelall">
                            <?= $form->field($model, 'cancelled_check')->fileInput(['accept' => 'image/*', 'id' => 'cancelled_file']) ?><div class="questionpopup_edit_p cancelled_check_new">
                                <a href="javascript:;" data-toggle="popover" title="" data-content='<p class="circlebox1">The account holder name on cheque should match property owner name.' data-html="true"  data-placement="left">?</a>	
                            </div>
                        </div>
                        <div class="changepassword">
                            <p> 
                                <img src="<?= !empty($model->cancelled_check) ? "../" . $model->cancelled_check : Url::home(true) . 'images/doumentupload.jpg' ?>" alt="..." id="cancelled_cheque">

                            </p>
                            <p>Cancelled cheque </p>
                        </div>
                    </div>


                </div>

                <div class="col-lg-6 col-sm-6">
                    <?= Html::submitButton('Save', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn savechanges_submit_login', 'onclick' => 'return checkForIfsc(event, this);']) ?>
                </div>
                <div class="col-lg-6 col-sm-6">
                    <a style="color:#FFF" class="btn savechanges_submit cancel_confirm_2" href="<?= Url::home(true); ?>site/myprofile" type="button">Cancel</a>
                </div>


            </div>
            <div style="margin-left:10px;" class="col-md-2 col-sm-2 myprofile common_profile_pic"> 
                <a class="thumbnail" href="#">
                    <?php if (isset($profile_image)) {
                        ?>
                        <img onclick="apply_image('ownerprofile-profile_image'); return false;" alt="..." src="<?php echo $profile_image->tempName; ?>" id="image_upload_preview">	
                    <?php } else { ?>
                        <img onclick="apply_image('ownerprofile-profile_image'); return false;" alt="..." src="<?= !empty($model->profile_image) ? "../" . $model->profile_image : Url::home(true) . 'images/photo.jpg' ?>" id="image_upload_preview">
                    <?php } ?>
                </a>

                <?= $form->field($model, 'profile_image')->fileInput(['accept' => 'image/*', 'style' => 'display:none'])->label(false) ?>

            </div>
            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>

<script>

    function checkForIfsc(e, ele) {
        e.preventDefault();
        var ifsc = $('#ownerprofile-bank_ifcs').val();
        $.ajax({
            url: 'checkifsc',
            type: 'post',
            data: {'ifsc': ifsc},
            success: function (res) {
                if (res == 'error') {
                    $('#ownerprofile-bank_ifcs').css('border', '1px solid #a94442');
                    $('#ifsc-error-box').css('display', 'block');
                    $(document).scrollTop($('#ownerprofile-bank_ifcs').position().top + 35);
                    return false;
                } else {
                    $('#ownerprofile-bank_ifcs').css('border', '1px solid #c7c7c7');
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
                        $('.identity_image_preview').append('<div class="col-md-6 col-sm-6 col-xs-6"><img class= "preview" alt=""src="' + e.target.result + '"><a class="removeLocal" id="' + attrclass + '"></a><p class="identityp">' + $('.IDproof option:selected').text() + '</p></div>');
                        $('.IDproof').prop('selectedIndex', 0);
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
                        $('.emergency_image_preview').append('<div class="col-md-6 col-sm-6 col-xs-6"><img class= "preview" alt=""src="' + e.target.result + '"><a class="removeLocal" id="' + attrclass + '"></a><p class="emergencyp">' + $('.EMERProof option:selected').text() + '</p></div>');
                        // $('.EMERProof').val('');
                        $('.EMERProof').prop('selectedIndex', 0);
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
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    $(document).on('change', '#ownerprofile-state', function () {
        var val = $(this).val();
        var url1 = $(this).attr('url');
        $.ajax({
            url: url1,
            type: 'POST',
            data: {'val': val, _csrf: csrfToken},
            success: function (data) {
                if (data) {
                    $('#ownerprofile-city').html(data);
                }
            },
            error: function (data) {
                //    console.log(data.responseText);
                alert(data.responseText);
            }
        });
    })

    $(document).on("click", ".cancel_confirm_2", function (e) {
        e.preventDefault();
        var element = $(this);
        var confirm_var = confirm('Are you sure you want to cancel ?');
        if (confirm_var == true) {
            window.location.href = (element.attr('href'));

        }
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#cancelled_cheque').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#cancelled_file").change(function () {
        readURL(this);
    });

</script>
