<style>
    img.preview {
        width: 96%;
        height: 200px;
    }
</style>

<?php
$this->title = 'PMS- Edit profile';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<div id="page-wrapper">
    <div class="row">
        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
        <!--<div class="col-lg-5 col-md-5 col-sm-7 col-xs-7 col-xs-offset-1">-->
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 col-xs-offset-1">

            <?php //echo $form->errorSummary($model) ;   ?>
            <div class="editprofilepict">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="changepassword mytop">
                        <h1>My contact details</h1>
                        <div class="form-group">
                            <table class="pp_edit">
                                <tr>
                                    <th>Name</th><th><?= Yii::$app->userdata->getFullNameById($model->advisor_id); ?></th>
                                </tr>
                                <tr>
                                    <th>Email</th><th><?= Yii::$app->userdata->getUserEmailById($model->advisor_id); ?> </th>
                                </tr>
                            </table>

                            <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'placeholder' => 'Phone Number*', 'class' => 'form-control ']) ?>



                            <?= $form->field($model, 'address_line_1')->textInput(['maxlength' => true, 'placeholder' => 'Address Line 1*']) ?>

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

                            <?php
                            $regions = \app\models\Regions::find()->where(['city_id' => $model->city])->all();
                            $cityData = ArrayHelper::map($regions, 'id', 'name');
                            ?>


                            <?= $form->field($model, 'pincode')->textInput(['maxlength' => true, 'placeholder' => 'Pincode*', 'class' => 'form-control isNumber']); ?>

                        </div>

                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="lineheightbg"></div>
                        <div class="supportperson parmentaddress mytop">
                            <h6> and Address Proof Documents</h6>
                            <div class="uploadbox">
                                <div class="col-md-6 col-sm-6">
                                    <select class="selectpicker1 IDproof">
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

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="lineheightbg"></div>
                        <div class="supportperson parmentaddress mytop">
                            <h6>Financial Data</h6>
                            <ul>
                                <li style="width: 100%;">
                                    <?= $form->field($model, 'account_holder_name')->textInput(['maxlength' => true, 'placeholder' => 'Account Holder Name*']) ?>
                                </li><li style="width: 100%;">
                                    <?= $form->field($model, 'bank_name')->textInput(['maxlength' => true, 'placeholder' => 'Bank Name*']) ?>
                                </li><li style="width: 100%;">
                                    <?= $form->field($model, 'bank_branchname')->textInput(['maxlength' => true, 'placeholder' => 'Bank Branch Name*']) ?>      
                                </li><li style="width: 100%;">	
                                    <?= $form->field($model, 'bank_ifcs')->textInput(['maxlength' => true, 'placeholder' => 'IFSC Code*'])->label('IFSC Code') ?>      
                                    <div id="ifsc-error-box" style="color: #a94442; display: none;" class="help-block">Invalid IFSC code.</div>
                                </li><li style="width: 100%;">	
                                    <?= $form->field($model, 'account_number')->textInput(['maxlength' => true, 'placeholder' => 'Bank Account Number*', 'class' => 'form-control isNumber']) ?>    
                                </li><li style="width: 100%;">  	
                                    <?= $form->field($model, 'pan_number')->textInput(['maxlength' => true, 'placeholder' => 'Pan Number*']) ?>      	
                                </li>
                                <li style="width: 100%;">  	
                                    <?= $form->field($model, 'service_tax_number')->textInput(['maxlength' => true, 'placeholder' => 'Service Tax Number*']) ?>      	
                                </li>			
                            </ul>	
                        </div>
                    </div>

                    <p>&nbsp;</p>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <?= Html::submitButton('Save', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn savechanges_submit_contractaction', 'onclick' => 'return checkForIfsc(event, this);']) ?>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <a style="color:#FFF" class="btn savechanges_submit cancel_confirm_2" href="<?= Url::home(true); ?>advisers/myprofile" type="button">Cancel</a>
                    </div>
                </div>

            </div>


        </div>
        <div class="col-md-2 col-sm-3 rightpfrolietop">
            <a href="#" class="thumbnail">
                <img src="<?= !empty($model->profile_image) ? "../" . $model->profile_image : Url::home(true) . 'images/photo.jpg' ?>" alt="...">
                <div class="documentbut">
                    <button  onclick="apply_image('advisorprofile-profile_image'); return false;" type="button" class="btn documentbut_submit" id="showmenu">Upload Photo</button>
                </div>
            </a>
            <image src="" id="image_upload_preview"/>
            <?= $form->field($model, 'profile_image')->fileInput(['accept' => 'image/*', 'style' => 'display:none'])->label(false) ?>
        </div>

    </div>
    <?php ActiveForm::end(); ?>
    <!-- /.col-lg-12 -->
</div>

<script>

    function checkForIfsc(e, ele) {
        e.preventDefault();
        var ifsc = $('#advisorprofile-bank_ifcs').val();
        $.ajax({
            url: 'checkifsc',
            type: 'post',
            data: {'ifsc': ifsc},
            success: function (res) {
                if (res == 'error') {
                    $('#advisorprofile-bank_ifcs').css('border', '1px solid #a94442');
                    $('#ifsc-error-box').css('display', 'block');
                    $(document).scrollTop($('#advisorprofile-bank_ifcs').position().top + 500);
                    return false;
                } else {
                    $('#advisorprofile-bank_ifcs').css('border', '1px solid #c7c7c7');
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
        $('#IDproof').append('<div class=" identity_file_' + count + ' identity_box box col-md-12 col-sm-12"><input type="file" attr-class="identity_file_' + count + '" name="address_proof[' + $(this).val() + '][]" attr-type="' + $('.IDproof option:selected').text() + '" class="identity_files" accept="image/*"></div>');
    });

    $(document).on('change', '.identity_files', function (e) {
        readurlIdentity(this);
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
                    $('.thumbnail img').attr('src', e.target.result);
                } else {
                    alert("only images are allowed");
                }
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#advisorprofile-profile_image").change(function () {
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
