<?php
$this->title = 'Details ';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\base\Model;
?>
<?php
// echo "</pre>";
// print_r($model);
// // print_r($operationProfile);
// die;
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<div id="page-wrapper">
    <div class="row">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#home">Advisor information </a></li>
            <li><a data-toggle="tab" href="#menu1">Lead information</a></li>
        </ul>

        <div class="tab-content">
            <div id="home" class="tab-pane fade in active">

                <h4>Advisor information</h4>
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
                                    <p><?= Yii::$app->userdata->getFullNameById($model->advisor_id); ?></p>

                                </div>
                                <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

                                <div class="parmentaddress">
                                    <h1>Contact detail </h1>

                                    <?= $form->field($model, 'email_id')->textInput(['maxlength' => true, 'placeholder' => 'Email*']) ?>

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
                                    // echo $model
                                    ?>
                                    <?= $form->field($model, 'city')->dropDownList($cityData, ['prompt' => 'Select city', 'url' => \Yii::$app->getUrlManager()->createUrl('advisers/getregions'), $model->city => array('selected' => true)]); ?>

                                    <?php
                                    $regions = \app\models\Regions::find()->where(['city_id' => $model->city])->all();
                                    $cityData = ArrayHelper::map($regions, 'id', 'name');
                                    ?>
                                    <?= $form->field($model, 'region')->dropDownList($cityData, ['prompt' => 'Select region']); ?>

                                    <?= $form->field($model, 'pincode')->textInput(['maxlength' => true, 'placeholder' => 'Pincode*']) ?>


                                    <div class="lineheightbg"></div>



                                    <!-- upload document area -->


                                </div>
                                <!-- Emergency contact details area -->

                                <div class="changepassword">
                                    &nbsp;
                                    <h1>Bank details</h1>
                                    <?= $form->field($model, 'account_holder_name')->textInput(['maxlength' => true, 'placeholder' => 'Account Holder Name*', 'class' => 'form-control isCharacter']) ?>

                                    <?= $form->field($model, 'bank_name')->textInput(['maxlength' => true, 'placeholder' => 'Bank Name*', 'class' => 'form-control isCharacter']) ?>

                                    <?= $form->field($model, 'bank_branchname')->textInput(['maxlength' => true, 'placeholder' => 'Bank Branch Name*', 'class' => 'form-control isCharacter']) ?>      	
                                    <?= $form->field($model, 'bank_ifcs')->textInput(['maxlength' => true, 'placeholder' => 'Bank Ifcs*']) ?>      	
                                    <?= $form->field($model, 'account_number')->textInput(['maxlength' => true, 'placeholder' => 'Bank Account Number*', 'class' => 'form-control isNumber']) ?>      	
                                    <?= $form->field($model, 'pan_number')->textInput(['maxlength' => true, 'placeholder' => 'Pan Number*']) ?>    

                                    <?= $form->field($model, 'service_tax_number')->textInput(['maxlength' => true, 'placeholder' => 'Service Tax Number*', 'class' => 'form-control']) ?>
                                </div>

                                <!-- cancelled check area -->

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
                                <!-- employment detail area -->



                                <div class="changepassword">
                                    &nbsp;
                                    <h1>Support Person details</h1>
                                    <?= $form->field($model, 'operation_id')->hiddenInput(['maxlength' => true, 'placeholder' => 'Support Person Name*', 'value' => isset($operationperson->id) ? $operationperson->id : ''])->label(false) ?>
                                    <div class="form-group field-advisorprofile-pan_number required has-success">
                                        <label class="control-label" for="advisorprofile-pan_number">Suppot Person Name</label>
                                        <input type="text" id="support_person_name" placeholder="Support Person Name" value="<?php echo isset($operationperson->full_name) ? $operationperson->full_name : '' ?>">
                                        <ul class="suggestions_operation" style="display:none"></ul>
                                    </div>

                                    <div class="form-group field-advisorprofile-pan_number required has-success">
                                        <label class="control-label" for="advisorprofile-pan_number">Suppot Person Email</label>
                                        <input type="email" id="support_person_email" placeholder="Support Person Email" disabled="true" value="<?php echo isset($operationperson->login_id) ? $operationperson->login_id : '' ?>">
                                    </div>

                                    <div class="form-group field-advisorprofile-pan_number required has-success">
                                        <label class="control-label" for="advisorprofile-pan_number">Suppot Person Contact</label>
                                        <input type="text" id="support_person_number" placeholder="Support Person contact" disabled="true" value="<?php echo isset($operationProfile->phone) ? $operationProfile->phone : '' ?>">
                                    </div>    	


                                    <div class="lineheightbg"></div>

                                </div>

                                <div class="changepassword">




                                    <div class="uploadbox">
                                        <div class="changepassword">
                                            <h1 class="advisortext">Identity and Address Proof Documents</h1></div></div>
                                    <div class="row">
                                        <div class="uploadbox">	
                                            <div class="col-md-6 col-sm-6">
                                                <select class="selectpicker1 IDproof">
                                                    <option selected="true">Choose file</option>
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

                                <!-- cancelled check area -->

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
                                    <a href="" class="thumbnail" onclick="apply_image('advisorprofile-profile_image'); return false;">
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
                <h4>Lead information</h4>
                <?php if (Yii::$app->session->hasFlash('leadssuccess')) { ?>
                    <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                        <?= Yii::$app->session->getFlash('leadssuccess'); ?>
                    </div>   
                <?php } ?>
                <div class="advisorbox">
                    <?php $formLeads = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

                    <?php
                    $regions = \app\models\RefStatus::find()->all();
                    $cityData = ArrayHelper::map($regions, 'id', 'name');
                    ?>
                    <?= $formLeads->field($modelLeads, 'ref_status')->dropDownList($cityData, ['prompt' => 'Select Status']); ?>
                    <?= $formLeads->field($modelLeads, 'follow_up_date_time')->textInput(['maxlength' => true, 'placeholder' => 'DD-MM-YYYY*']) ?>
                    <?= $formLeads->field($modelLeads, 'schedule_date_time')->textInput(['maxlength' => true, 'placeholder' => 'DD-MM-YYYY*', 'class' => 'form-control datepicker'])->label('Schedule Start Date') ?>

                    <div>
                        <label for="exampleInputName2">Assigned sales person name</label>&nbsp;&nbsp;<span class="glyphicon glyphicon-search" data-toggle="modal" data-target=".bs-modal-sm1"></span>
                        <input type="text" id="sales_name" readonly value="<?= Yii::$app->userdata->getFullNameById($model->sales_id); ?>" class="form-control" placeholder="Mahesh">
                        <?= $formLeads->field($model, 'sales_id')->hiddenInput()->label(false) ?>

                    </div>





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
                                if ($('#comments-description').val() == '') {
                                    alert('Please enter comment.');
                                    return false;

                                }


                                $.ajax({
                                    type: "POST",
                                    url: 'addcomment',
                                    data: 'comments=' + $('#comments-description').val() + '&user_id=' + $('#comments-user_id').val(), //only input
                                    success: function (response) {

                                        if (response == 'done') {
                                            // $("#comment_form")[0].reset();
                                            $('#comments-description').val('');
                                            $('#dataComments').load(' #dataComments');
                                            // location.reload();
                                        } else {
                                            alert('Please try again');
                                        }
                                    }
                                });
                            });
                        });

                        $(document).on('click', '.activateAdvisor', function () {
                            var id = $(this).attr('data-id');
                            var action = $(this).attr('data-action');
                            var element = $(this);
                            $.ajax({
                                type: "POST",
                                url: 'activateadvisor',
                                data: 'id=' + id + '&action=' + action,
                                success: function (response) {
                                    if (action == 0) {
                                        element.text('Activate Adviser');
                                        element.attr('data-action', '1');
                                    } else {
                                        element.text('Deactivate Adviser');
                                        element.attr('data-action', '0');
                                    }
                                }
                            });

                        })
                    </script>




                    <div class="clearfix"></div>








                    <div class="row">



                        <div class="col-md-1 note_c">
                            <p>Notes</p>
                        </div>
                        <div class="col-md-11 comment_c">
                            <div class="col-md-10 comment_c">
                                <div class="form-group field-comments-user_id">

                                    <input type="hidden" id="comments-user_id" class="form-control" name="user_id" value="<?php echo $model->advisor_id; ?>">

                                    <div class="help-block"></div>
                                </div>						<div class="form-group field-comments-description required">

                                    <textarea id="comments-description" class="form-control" name="description" rows="1" aria-required="true"></textarea>

                                    <div class="help-block"></div>
                                </div>
                            </div>

                            <div class="col-md-2 comment_c">
                                <button type="button" id="add_comment" class="btn btn-default replybut">Add Comment</button>
                            </div>
                        </div>
                    </div>

                    <div class="lorembox" id="dataComments">
                        <?php
                        if ($comments) {
                            foreach ($comments as $comment) {
                                ?>
                                <p><?= $comment->description; ?>, 
                                    <span>-<?= Yii::$app->userdata->getFullNameById($comment->created_by); ?> on  <?= date('m/d/Y', strtotime($comment->created_date)); ?> At  <?= date('h:i a', strtotime($comment->created_date)); ?></span>
                                </p>
                                <?php
                            }
                        } else {
                            echo "<p> No comments</p>";
                        }
                        ?>


                    </div>


                    <h5 class="advisortext">Referral Contract</h5>
                    <?php
                    if (count($agreements) == 0) {
                        $agreements = $agreements1;
                    }
                    ?>
                    <?= $form->field($agreements, 'advisor_id')->hiddenInput(['value' => $model->advisor_id])->label(false) ?>
                    <?= $form->field($agreements, 'start_date')->textInput(['class' => "form-control", 'value' => !empty($agreements) ? $agreements->start_date : '0']) ?>
                    <?= $form->field($agreements1, 'end_date')->textInput(['class' => "form-control", 'value' => !empty($agreements) ? $agreements->end_date : '0']) ?>
                    <?= $form->field($agreements, 'id')->hiddenInput(['value' => !empty($agreements) ? $agreements->id : '0'])->label(false) ?>

                    <?= $form->field($agreements, 'agreement_doc')->fileInput(['accept' => '*', 'style' => 'display:none'])->label(false) ?>	
                    <div class="row" style="width: 40%;">
                        <button type="button" class="btn uploadAdvisorContract">Upload Advisor contract</button> 
                    </div>
                    <div class="row">
                        <br />
                        <div class="col-lg-4">
                            <?= Html::submitButton('Save', ['class' => $modelLeads->isNewRecord ? 'btn btn-success' : 'btn savechanges_submit_contractaction']) ?>

                        </div>
                        <div class="col-lg-4">
                            <a style="color:#FFF" class="btn savechanges_submit cancel_confirm" href="<?= Url::home(true); ?>sales/applicants" type="button">Cancel</a>

                        </div>
                        <div class="col-lg-4">


                            <button style="color:#FFF;background: #204d74;margin-left: 13px !important;border-color: #204d74;" class="btn btn-primary savechanges_submit activateAdvisor" data-id="<?= $model->advisor_id ?>" data-action="<?= (Yii::$app->userdata->getUserStatusById($model->advisor_id) == 0) ? 1 : 0; ?>" href="#" type="button"><?= (Yii::$app->userdata->getUserStatusById($model->advisor_id) == 0) ? 'Activate Adviser' : 'Deactivate Adviser'; ?></button>
                        </div>  
                    </div>

                    <?php ActiveForm::end(); ?>

                </div>

                <div class="clearfix"></div>





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

    $("#advisorprofile-profile_image").change(function () {
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

    $("#advisorprofile-address_proof").change(function () {
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

    $("#advisorprofile-identity_proof").change(function () {
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

    $(document).on('keyup', '#support_person_name', function () {
        var name = $(this).val();
        $.ajax({
            method: "POST",
            url: "getoperationsname?term=" + name,
            // contenttype : "application/json",
            datatype: "application/json",
            success: function (response) {
                response = JSON.parse(response);
                var list = '';
                $(response).each(function (index, value) {
                    list += '<li class="supportDetails" style="cursor:pointer" data-name="' + value.name + '" data-email="' + value.email + '" data-phone="' + value.phone + '" data-id="' + value.id + '">' + value.name + '</li>';
                })
                if (list.trim() != '') {
                    $('.suggestions_operation').html(list);
                    $('.suggestions_operation').show();
                } else {
                    $('.suggestions_operation').html('');
                    $('.suggestions_operation').hide();
                }

            }
        })

    })

    $(document).on('click', '.supportDetails', function () {
        $('#support_person_name').val($(this).attr('data-name'));
        $('#support_person_email').val($(this).attr('data-email'));
        $('#support_person_number').val($(this).attr('data-phone'));
        $('#advisorprofile-operation_id').val($(this).attr('data-id'));
        $('.suggestions_operation').html('');
        $('.suggestions_operation').hide();
    })

    $(document).on('click', '.uploadAdvisorContract', function () {
        // alert('hello');
        $('#advisoragreements-agreement_doc').click();
    });
    // $("#advisorprofile-emer_contact_indentity").change(function () {
    //     readURL3(this);
    // });
    //  function readURL4(input) {
    //     if (input.files && input.files[0]) {
    //         var reader = new FileReader();

    //         reader.onload = function (e) {
    //             $('#passporte').attr('src', e.target.result);
    //         }

    //         reader.readAsDataURL(input.files[0]);
    //     }
    // }

    // $("#applicantprofile-emer_contact_address").change(function () {
    //     readURL4(this);
    // });
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
</script>




<style type="text/css">
    img.preview {
        width: 80%;
        height: 240px;
    }

    ul.suggestions_operation {
        list-style: none;
        width: 100%;
        position: absolute;
        margin-top: -2px !important;
        padding-left: 0;
        background-color: white;
        z-index: 999;
        max-height: 132px;
        overflow-y: scroll;
    }
    li.supportDetails {
        padding: 6px 10px;
        border-bottom: 1px solid rgba(0,0,0,0.3);
    }

    li.supportDetails:hover {
        background-color: #337ab7;
        color: white;
    }
</style>