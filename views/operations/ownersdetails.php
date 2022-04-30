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
    <?php $form = ActiveForm::begin(['id' => 'users_agreement', 'options' => ['enctype' => 'multipart/form-data']]); ?>
    <div class="row">
        <div class="parmentaddress">
            <h1>Property Owner Management</h1>
        </div>
        <div class="row">
            <div class="parmentaddress advisorbox">

                <?= $form->field($model, 'owner_id')->hiddenInput()->label(false); ?>
                <?= $form->field($modelUser, 'full_name')->textInput(['maxlength' => true, 'placeholder' => 'Full Name*', 'class' => 'form-control isCharacter'])->label('Property Owner Name') ?>
                <?= $form->field($model, 'address_line_1')->textInput(['maxlength' => true, 'placeholder' => 'Address Line 1*', 'class' => 'form-control ']) ?>  
                <?= $form->field($model, 'address_line_2')->textInput(['maxlength' => true, 'placeholder' => 'Address Line 2*', 'class' => 'form-control ']) ?>  

                <div class="parmentaddress resetbox">
                    <label for="exampleInputEmail1">Contact Details</label>
                    <?= $form->field($modelUser, 'login_id')->textInput(['maxlength' => true, 'placeholder' => 'Email Id*', 'class' => 'form-control'])->label(false); ?> 
                    <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'placeholder' => 'Contact Number*', 'class' => 'form-control isPhone'])->label(false) ?> 

                </div>
                <div class="parmentaddress resetbox">
                    <label for="exampleInputEmail1">Operations Person</label>&nbsp;&nbsp;<span class="glyphicon glyphicon-search" data-toggle="modal" data-target=".bs-modal-sm1"></span>
                    <?= $form->field($model, 'operation_id')->hiddenInput(['maxlength' => true])->label(false) ?> 

                    <input class="form-control" placeholder="Operations Person Name*" type="text" id="operations_name" value="<?= ($model->operation_id != 0 || trim($model->operation_id) != '') ? Yii::$app->userdata->getFullNameById($model->operation_id) : '' ?>">
                </div>
                <div class="parmentaddress resetbox">

                    <label for="exampleInputEmail1">Sales Person</label>&nbsp;&nbsp;<span class="glyphicon glyphicon-search" data-toggle="modal" data-target=".bs-modal-sm"></span>
                    <?= $form->field($model, 'sales_id')->hiddenInput(['maxlength' => true])->label(false) ?>

                    &nbsp;
                    <input class="form-control" placeholder="Sales Person Name*" type="text" id="sales_name" value="<?= ($model->sales_id != 0 || trim($model->sales_id) != '') ? Yii::$app->userdata->getFullNameById($model->sales_id) : '' ?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="advisorbox">
                <p class="advisortext documenttext">Documents</p>

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

                <p class="advisortext">Emergency contact details </p>

                <?= $form->field($model, 'emer_contact')->textInput(['maxlength' => true, 'class' => 'form-control isCharacter'])->label('Emergency Contact Name') ?>

                <?= $form->field($model, 'emergency_contact_email')->textInput(['maxlength' => true, 'class' => 'form-control'])->label('Emergency Contact Email') ?>


                <?= $form->field($model, 'emergency_contact_number')->textInput(['maxlength' => true, 'class' => 'form-control isPhone'])->label() ?>


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
                                        <img  src="<?php echo ($propertiesImages) ? $propertiesImages->image_url : Url::home(true) . 'images/gallery_imgone.jpg'; ?>" class="mCS_img_loaded" alt="image"> </a>
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
                                    <a href="<?= Url::home(true) ?>operations/editproperty?id=<?= $property->id; ?>" onclick="$('body').on('hidden.bs.modal', '.modal', function () {
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
    </div>
    <a href="<?= Url::home(true) ?>operations/addproperty?id=<?= base64_encode($model->owner_id); ?>">Add Property</a>
    <div class="row">
        <br />
        <div class="col-lg-6">
            <?= Html::submitButton('Save', ['class' => 'btn savechanges_submit_contractaction', 'id' => 'ownerCreateBtn']) ?>

        </div>
        <div class="col-lg-6">
            <a style="color:#FFF" class="btn savechanges_submit cancel_confirm" href="<?= Url::home(true); ?>operations/applicants" type="button">Cancel</a>

        </div> 
    </div>

    <?php ActiveForm::end(); ?>

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