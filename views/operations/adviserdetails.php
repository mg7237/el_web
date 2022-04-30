<?php
$this->title = 'Adviser Detail';

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
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<div id="page-wrapper">
    <div id="home" class="tab-pane fade in active">
        <?php if (Yii::$app->session->hasFlash('success')) { ?>
            <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                <?= Yii::$app->session->getFlash('success'); ?>
            </div>   
        <?php } ?>

        <h3 style="margin-top: 27px">Advisor Management </h3>
        <?php $form = ActiveForm::begin(['id' => 'users_agreement', 'options' => ['enctype' => 'multipart/form-data']]); ?>
        <?php //echo $form->errorSummary($model); ?>
        <?php //echo $form->errorSummary($modelUser); ?>

        <div class="row">

            <div class="col-lg-7 col-xs-offset-1">
                <!-- <div class="myprofile">
                    <span>Name</span>
                        <p><?//= Yii::$app->userdata->getFullNameById( $model->advisor_id) ; ?></p>
                        
  </div> -->



                <div class="parmentaddress resetbox">
                    <?= $form->field($modelUser, 'full_name')->textInput(['maxlength' => true, 'placeholder' => 'Full Name*']) ?>  
                </div>
                <div class="parmentaddress resetbox">
                    <?= $form->field($modelUser, 'login_id')->textInput(['maxlength' => true, 'placeholder' => 'Email*']) ?>  
                </div>
                <div class="parmentaddress resetbox">
                    <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'placeholder' => 'Phone Number*', 'class' => 'form-control isPhone']) ?>
                </div>
                <div class="parmentaddress resetbox">
                    <?= $form->field($model, 'address_line_1')->textInput(['maxlength' => true, 'placeholder' => 'Address line 1*']) ?>
                </div>

                <div class="parmentaddress resetbox">
                    <?= $form->field($model, 'address_line_2')->textInput(['maxlength' => true, 'placeholder' => 'Address line 2*']) ?>
                    <?= $form->field($model, 'profile_image')->fileInput(['accept' => 'image/*', 'style' => 'display:none'])->label(false) ?>

                </div>
                <div class="parmentaddress resetbox">

                    <?php
                    $states = \app\models\States::find()->all();
                    $stateData = ArrayHelper::map($states, 'id', 'name');
                    ?>
                    <?= $form->field($model, 'state')->dropDownList($stateData, ['prompt' => 'Select state', 'url' => \Yii::$app->getUrlManager()->createUrl('users/getcity')]); ?>
                </div>  
                <div class="parmentaddress resetbox">

                    <?php
                    $cities = \app\models\Cities::find()->where(['state_id' => $model->state])->all();
                    $cityData = ArrayHelper::map($cities, 'id', 'city_name');
                    // echo $model
                    ?>
                    <?= $form->field($model, 'city')->dropDownList($cityData, ['prompt' => 'Select city', 'url' => \Yii::$app->getUrlManager()->createUrl('advisers/getregions'), $model->city => array('selected' => true)]); ?>
                </div> 
                <div class="parmentaddress resetbox">

                    <?php
                    $regions = \app\models\Regions::find()->where(['city_id' => $model->city])->all();
                    $cityData = ArrayHelper::map($regions, 'id', 'name');
                    ?>
                    <?= $form->field($model, 'region')->dropDownList($cityData, ['prompt' => 'Select region']); ?>
                </div>  
                <div class="parmentaddress resetbox">

                    <?= $form->field($model, 'pincode')->textInput(['maxlength' => true, 'placeholder' => 'Pincode*', 'class' => 'form-control isNumber']) ?>

                </div>





                <div class="lineheightbg"></div>


                <div class="changepassword">
                    &nbsp;
                    <h1>Financial Data</h1>
                    <?= $form->field($model, 'account_holder_name')->textInput(['maxlength' => true, 'placeholder' => 'Account Holder Name*', 'class' => 'form-control isCharacter']) ?>
                    <?= $form->field($model, 'bank_name')->textInput(['maxlength' => true, 'placeholder' => 'Bank name*', 'class' => 'form-control isCharacter']) ?>

                    <?= $form->field($model, 'bank_branchname')->textInput(['maxlength' => true, 'placeholder' => 'Branch name*']) ?>        
                    <?= $form->field($model, 'bank_ifcs')->textInput(['maxlength' => true, 'placeholder' => 'Bank IFSC*'])->label('Bank IFSC') ?>       
                    <?= $form->field($model, 'account_number')->textInput(['maxlength' => true, 'placeholder' => 'Account number*', 'class' => 'form-control isNumber']) ?>        
                    <?= $form->field($model, 'pan_number')->textInput(['maxlength' => true, 'placeholder' => 'Pan number*', 'class' => 'form-control']) ?>        
                    <?= $form->field($model, 'service_tax_number')->textInput(['maxlength' => true, 'placeholder' => 'Service Tax Number*', 'class' => 'form-control']) ?>      

                </div>


                <div class="lineheightbg"> </div>
                <p style="height:20px;"></p>
                <div class="changepassword">
                    <h1 class="advisortext">Identity and Address Proof Documents</h1>

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


            </div>
            <div class="col-xs-2 col-md-2 rightpfrolietop">
                <a href="" class="thumbnail" onclick="apply_image('advisorprofile-profile_image'); return false;">
                    <img id="blah" src="<?= !empty($model->profile_image) ? $model->profile_image : Url::home(true) . 'images/photo.jpg' ?>" alt="...">
                </a>
            </div>


            <div class="row col-lg-11" style="padding-top:20px;">
                <div class="col-lg-4">
                    <?= Html::submitButton('Save', ['class' => 'btn savechanges_submit_contractaction']) ?>
                </div>
                <div class="col-lg-4">
                    <a style="color:#FFF" class="btn savechanges_submit cancel_confirm" href="<?= Url::home(true); ?>sales/owners" type="button">Cancel</a>

                </div>

            </div>

        </div>



        <?php ActiveForm::end(); ?>



    </div>

</div>

<div class="modal fade" id="myModal_edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modalbg modalbgpopup" role="document">
        <div class="modal-content">
        </div>
    </div>


</div>

<script type="text/javascript">
    $(document).on('change', '.IDproof', function () {
        var count = $('#IDproof .box').length;
        $('.identity_box').hide();
        $('#IDproof').append('<div class=" identity_file_' + count + ' identity_box box col-md-12 col-sm-12"><input type="file" name="address_proof[' + $(this).val() + '][]" attr-type="' + $('.IDproof option:selected').text() + '" class="identity_files">');
    });

    $(document).on('change', '.identity_files', function (e) {
        readurlIdentity(this);
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

</script>

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
                                    <label> Search operation person </label>  
                                    <input required="" id="tags1" name="userid" type="text" class="form-control" placeholder="Search for item" class="input-medium" required="">
                                    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
                                    <script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
                                    <style>
                                        ul.ui-autocomplete {
                                            z-index: 1100;
                                        }
                                    </style>
                                    <script>

    $(function () {
        $("#tags1").autocomplete({
            source: "getoperationname",
            select: function (event, ui) {
                event.preventDefault();
                //  console.log(ui.item);
                $("#operations_name").val(ui.item.label);
                $("#ownerprofile-operation_id").val(ui.item.value);
                $("#tags1").val(ui.item.label);
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


<div class="modal fade bs-modal-sm" id="myModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
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
                                    <input required="" id="tags" name="userid" type="text" class="form-control" placeholder="Search for item" class="input-medium" required="">
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
                //  console.log(ui.item);
                $("#sales_name").val(ui.item.label);
                $("#ownerprofile-sales_id").val(ui.item.value);
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
<script type="text/javascript">
    function apply_image(str) {
        $('#' + str).click();
        return false;
    }
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#advisorprofile-profile_image").change(function () {
        readURL(this);
    });
</script>