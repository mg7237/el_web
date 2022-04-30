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
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<div id="page-wrapper">
    <div class="row">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#home">Owner information </a></li>
            <li><a data-toggle="tab" href="#menu1">Lead information</a></li>
        </ul>

        <div class="tab-content">
            <div id="home" class="tab-pane fade in active">
                <?php if (Yii::$app->session->hasFlash('success')) { ?>
                    <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                        <?= Yii::$app->session->getFlash('success'); ?>
                    </div>   
                <?php } ?>

                <h3>Owner information </h3>
                <?php $form = ActiveForm::begin(['id' => 'users_agreement', 'options' => ['enctype' => 'multipart/form-data']]); ?>
                <?php //echo $form->errorSummary($model); ?>
                <?php //echo $form->errorSummary($modelUser); ?>

                <div class="row">

                    <div class="col-lg-7 col-xs-offset-1">
                        <div class="myprofile">
                            <span>Name</span>
                            <p><?= Yii::$app->userdata->getFullNameById($model->owner_id); ?></p>

                        </div>
                        <div class="lineheightbg"></div>

                        <div class="parmentaddress">
                            <h1>Permanent address</h1>
                            <div class="parmentaddress resetbox">
                                <?= $form->field($model, 'address_line_1')->textInput(['maxlength' => true, 'placeholder' => 'Address Line 1*']) ?>
                            </div>

                            <div class="parmentaddress resetbox">
                                <?= $form->field($model, 'address_line_2')->textInput(['maxlength' => true, 'placeholder' => 'Address Line 2*']) ?>
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
                                <?= $form->field($model, 'region')->dropDownList($cityData, ['prompt' => 'Select Branch'])->label('Branch'); ?>
                            </div>  
                            <div class="parmentaddress resetbox">

                                <?= $form->field($model, 'pincode')->textInput(['maxlength' => true, 'placeholder' => 'Pincode*', 'class' => 'form-control isNumber']) ?>

                            </div>
                            <div class="parmentaddress resetbox">
                                <?= $form->field($modelUser, 'login_id')->textInput(['maxlength' => true, 'placeholder' => 'Email*']) ?>	
                            </div>
                            <div class="parmentaddress resetbox">

                                <?= $form->field($model, 'phone')->textInput(['maxlength' => 15, 'placeholder' => 'Phone Number*', 'class' => 'form-control isPhone']) ?>

                            </div>	

                        </div>
                        <div class="lineheightbg"> </div>
                        <p style="height:20px;"></p>
                        <div class="changepassword">
                            <h1 class="advisortext">Identity and Address Proof Documents</h1>

                            <div class="row">
                                <div class="uploadbox">	
                                    <div class="col-md-6">
                                        <select class="selectpicker IDproof">
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

                        <div class="lineheightbg"></div>

                        <div class="parmentaddress">
                            &nbsp;
                            <h1>Emergency Contact Detail</h1>




                            <div class="parmentaddress resetbox">
                                <?= $form->field($model, 'emer_contact')->textInput(['maxlength' => true, 'placeholder' => 'Name*', 'class' => 'form-control isCharacter'])->label('Emergency Contact Name') ?>

                            </div>
                            <div class="parmentaddress resetbox">
                                <?= $form->field($model, 'emergency_contact_email')->textInput(['maxlength' => true, 'placeholder' => 'Email*']) ?>

                            </div>
                            <div class="parmentaddress resetbox">
                                <?= $form->field($model, 'emergency_contact_number')->textInput(['maxlength' => true, 'placeholder' => 'Number*', 'class' => 'form-control isPhone']) ?>
                            </div>
                            &nbsp;


                        </div>
                        <div class="lineheightbg"></div>

                        <div class="changepassword">
                            &nbsp;
                            <h1>Bank details</h1>
                            <?= $form->field($model, 'account_holder_name')->textInput(['maxlength' => true, 'placeholder' => 'Account Holder Name*', 'class' => 'form-control isCharacter']) ?>

                            <?= $form->field($model, 'bank_name')->textInput(['maxlength' => true, 'placeholder' => 'Bank Name*', 'class' => 'form-control isCharacter']) ?>

                            <?= $form->field($model, 'bank_branchname')->textInput(['maxlength' => true, 'placeholder' => 'Branch Name*']) ?>      	
                            <?= $form->field($model, 'bank_ifcs')->textInput(['maxlength' => true, 'placeholder' => 'IFSC Code*'])->label('IFSC Code') ?>      	
                            <?= $form->field($model, 'bank_account_number')->textInput(['maxlength' => true, 'placeholder' => 'Account Number*', 'class' => 'form-control isNumber']) ?>      	
                            <?= $form->field($model, 'pan_number')->textInput(['maxlength' => true, 'placeholder' => 'Pan number*', 'class' => 'form-control']) ?>      	


                        </div>
                        <div class="row">
                            <p class="circlebox">The cheque should carry name of the account holder and matching applicant name</p>
                            <?= $form->field($model, 'cancelled_check')->fileInput(['accept' => 'image/*']) ?>
                            <div class="uploadbox">

                                <div class="col-md-6">

                                    <img id="cancelled" src="<?= !empty($model->cancelled_check) ? "../" . $model->cancelled_check : Url::home(true) . 'images/doumentupload.jpg' ?>" alt="...">

                                    <p>Cancelled Cheque</p>
                                </div>
                            </div>
                            <p></p>
                        </div>

                        <div class="parmentaddress">
                            &nbsp;
                            <h1>Support Person Details</h1>

                            <div class="form-group">
                                <label for="exampleInputPassword1">Support Person Name</label>&nbsp;&nbsp;<span data-target=".bs-modal-sm" data-toggle="modal" class="glyphicon glyphicon-search"></span>
                                <input type="text" id="operation_name" readonly value="<?= Yii::$app->userdata->getFullNameById($model->operation_id); ?>" class="form-control isCharacter" placeholder="Support Person Name">
                                <?= $form->field($model, 'operation_id')->hiddenInput()->label(false) ?>

                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Support Email</label>
                                <input type="text" readonly value="<?= Yii::$app->userdata->getUserEmailById($model->operation_id); ?>" id="operationemail" placeholder="Support Person Email" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Support Phone</label>
                                <input type="text" readonly value="<?= Yii::$app->userdata->getPhoneById($model->operation_id, 7); ?>"  id="operationphone"  placeholder="Support Person Phone" class="form-control">
                            </div>




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
                                                                <label> Search Support person </label>	
                                                                <input id="tags1" name="userid" type="text" class="form-control" placeholder="Search for item" class="input-medium" >

                                                                <style>
                                                                    ul.ui-autocomplete {
                                                                        z-index: 1100;
                                                                    }
                                                                </style>
                                                                <script>

                                                                    $(function () {
                                                                        $("#tags1").autocomplete({
                                                                            source: "getoperationsname",
                                                                            select: function (event, ui) {
                                                                                event.preventDefault();
                                                                                //	console.log(ui.item);
                                                                                $("#operation_name").val(ui.item.label);
                                                                                $("#operationemail").val(ui.item.email);
                                                                                $("#operationphone").val(ui.item.phone);
                                                                                $("#ownerprofile-operation_id").val(ui.item.value);
                                                                                $("#tags1").val(ui.item.label);
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













                        </div>



                    </div>
                    <div class="col-xs-2 col-md-2 rightpfrolietop">
                        <a href="" class="thumbnail" onclick="apply_image('ownerprofile-profile_image');
                                return false;
                           ">
                            <img id="blah" src="<?= !empty($model->profile_image) ? "../" . $model->profile_image : Url::home(true) . 'images/photo.jpg' ?>" alt="...">
                        </a>
                    </div>
                </div>


                <div class="row col-lg-11" style="padding-top:20px;">
                    <div class="col-lg-3">
                        <?= Html::submitButton('Save', ['class' => 'btn savechanges_submit_contractaction']) ?>
                    </div>
                    <div class="col-lg-3">
                        <a style="color:#FFF" class="btn savechanges_submit cancel_confirm" href="<?= Url::home(true); ?>sales/owners" type="button">Cancel</a>

                    </div>
                    <div class="col-lg-5" style="padding-left:10px;">
                        <?php
                        if ($modelUser->status == 0 || $modelUser->status == 2) {
                            ?>
                            <button type="button" class="btn savechanges_submit_contractaction activate_owner_link" data-id="<?= $model->owner_id ?>">Activate property owner</button> 
                            <?php
                        } else {
                            ?>
                            <button type="button" class="btn savechanges_submit_contractaction deactivate_owner_link" data-id="<?= $model->owner_id ?>">De-activate property owner</button> 
                            <?php
                        }
                        ?>

                    </div>
                </div>
                <?php ActiveForm::end(); ?>



            </div>


            <div id="menu1" class="tab-pane fade">
                <h3>Lead information</h3>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="advisorbox">

                            <?php if (Yii::$app->session->hasFlash('leadssuccess')) { ?>
                                <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                                    <?= Yii::$app->session->getFlash('leadssuccess'); ?>
                                </div>   
                            <?php } ?>
                            <?php $formLeads = ActiveForm::begin(['id' => 'leads_agreement', 'options' => ['enctype' => 'multipart/form-data']]); ?>

                            <?php
                            $regions = \app\models\RefStatus::find()->all();
                            $cityData = ArrayHelper::map($regions, 'id', 'name');
                            ?>
                            <?= $formLeads->field($modelLeads, 'ref_status')->dropDownList($cityData, ['prompt' => 'Select Branch'])->label('Branch'); ?>
                            <?= $formLeads->field($modelLeads, 'follow_up_date_time')->textInput(['maxlength' => true, 'placeholder' => 'DD-MMM-YYYY HH:MM*', 'class' => 'datetimepickerss form-control']) ?>

                            <div>
                                <label for="exampleInputName2">Assigned sales person name</label>&nbsp;&nbsp;<span class="glyphicon glyphicon-search" data-toggle="modal" data-target=".bs-modal-sm1"></span>
                                <input type="text" id="sales_name" readonly value="<?= Yii::$app->userdata->getFullNameById($model->sales_id); ?>" class="form-control is Character" placeholder="Enter Sales Person Name">
                                <?= $form->field($model, 'sales_id')->hiddenInput()->label(false) ?>

                            </div>
                            <div class="row">
                                <br />
                                <div class="col-lg-6">
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
                                    <div class="col-md-11 comment_c">
                                        <div class="col-md-10 comment_c">
                                            <?= $form->field($modelComments, 'description')->textarea(['rows' => '1'])->label(false) ?>
                                            <?= $form->field($modelComments, 'user_id')->hiddenInput(['value' => $model->owner_id])->label(false) ?>

                                        </div>

                                        <div class="col-md-2 comment_c">
                                            <?= Html::Button('Add Comment', ['id' => 'add_comment', 'class' => 'btn btn-default replybut']) ?>

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
                                                <p><?= $property->address_line_1 . ' ' . $property->address_line_2; ?></p>
                                                <div class="col-lg-12 rentboxnew">
                                                    <h6><?= isset($property->propertyListing) ? $property->propertyListing->rent : ''; ?></h6>



                                                </div>
                                                <p class="text-right" style="padding-right:9px; margin-bottom:0px;">
                                                    <?php
                                                    if (!Yii::$app->userdata->getPropertyBookedStatus($property->id)) {
                                                        ?>
                                                        <a href="<?= Url::home(true) ?>external/editproperty?id=<?= $property->id; ?>" onclick="$('body').on('hidden.bs.modal', '.modal', function () {
                                                                    $(this).removeData('bs.modal');
                                                                });"  data-toggle="modal" data-target="#myModal_edit">Edit </a>
                                                           <?php
                                                       }
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
                                <div class="col-lg-6">
                                    <a style="color:#FFF" class="btn savechanges_submit_contractaction submit_form_anchor" data-value="leads_agreement" href="#">Save</a>

                                </div>
                                <div class="col-lg-6">
                                    <a style="color:#FFF" class="btn savechanges_submit cancel_confirm" href="<?= Url::home(true); ?>sales/applicants" type="button">Cancel</a>

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


    $(document).ready(function () {

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
                        alert('Please try again');
                        hideLoader();
                    }
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
                $('#image_upload_preview').attr('src', e.target.result);
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
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#ownerprofile-cancelled_check").change(function () {
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

    $(document).on('click', '.activate_owner_link', function () {
        startLoader();
        var thiselement = $(this);
        $.ajax({
            type: "POST",
            url: 'activateowner',
            data: 'id=' + $(this).attr('data-id'), //only input
            dataType: 'json',
            success: function (response) {
                if (response.success == 0) {
                    hideLoader();
                    alert(response.msg);
                } else {
                    thiselement.text('De-activate Property Owner');
                    thiselement.removeClass('activate_owner_link');
                    thiselement.addClass('deactivate_owner_link');
                    hideLoader();
                }
            }
        });
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
                    thiselement.removeClass('deactivate_owner_link')
                    hideLoader();
                } else {
                    alert('Can\'t Deactivate Owner.Some Property Is Activated');
                    hideLoader();
                }
            }
        });
    });
    $(document).on('click', '.submit_form_anchor', function (e) {
        e.preventDefault();
        $('#' + $(this).attr('data-value')).submit();
    })

</script>

