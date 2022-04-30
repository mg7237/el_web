<?php
$this->title = 'Details ';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\base\Model;
?>
<?php
$first_class = "active in";
$second_class = "";
$first_tab = "active";
$second_tab = "";
$end_date_error = "";
$end_date_error_class = "";
if (isset($agreements)) {
    $errors = $agreements->getErrors();
    if (count($errors) != 0) {
        $erragree = 1;
        $second_class = "active in";
        $first_class = "";
        $second_tab = "active";
        $first_tab = "";
    }

    $end_errors = $agreements->getErrors('end_date');
    if (count($end_errors) != 0) {
        $end_date_error = $end_errors[0];
        $end_date_error_class = "has-error";
    }
}
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<div id="page-wrapper">
    <div class="row">
        <ul class="nav nav-tabs">
            <li class="<?= $first_tab ?>"><a data-toggle="tab" href="#home">Advisor information </a></li>
            <li class="<?= $second_tab ?>"><a data-toggle="tab" href="#menu1">Lead information</a></li>
            <li><a data-toggle="tab" href="#lead-assignment">Lead assignment</a></li>
        </ul>

        <div class="tab-content">



            <div id="home" class="tab-pane fade <?= $first_class ?>">


                <?php if (Yii::$app->session->hasFlash('success')) { ?>
                    <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                        <?= Yii::$app->session->getFlash('success'); ?>
                    </div>
                <?php } ?>
                <?php if (Yii::$app->session->hasFlash('leadssuccess')) { ?>
                    <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                        <?= Yii::$app->session->getFlash('leadssuccess'); ?>
                    </div>
                <?php } ?>



                <div class="col-lg-7 col-xs-offset-1">
                    <h4>Advisor information</h4>
                </div>
                <div class="row">
                    <div class="col-lg-12">


                        <div class="row">  				
                            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
                            <div class="col-lg-7 col-xs-offset-1">

                                <div class="myprofile supportperson mytop">
                                    <span>Name </span>
                                    <?php $fullname = Yii::$app->userdata->getFullNameById($model->advisor_id); ?>
                                    <?= $form->field($modelLeads, 'full_name')->textInput(['maxlength' => true, 'placeholder' => 'Full Name*', 'value' => $fullname]) ?>
                                </div>

                                <div class="myprofile supportperson mytop">
                                    <span>Properties Held </span>
                                    <?= $form->field($model, 'ad_properties')->textInput(['maxlength' => true, 'placeholder' => 'Number of properties held by advisor *']) ?>
                                </div>

                                <div class="lineheightbg"></div>
                                <div class="parmentaddress">
                                    <h1>Contact detail </h1>

                                    <?= $form->field($model, 'email_id')->textInput(['maxlength' => true, 'placeholder' => 'Email*']) ?>

                                    <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'placeholder' => 'Phone Number*']) ?>


                                </div>

                                <div class="lineheightbg"></div>
                                <div class="parmentaddress">
                                    <h1>Permanent address</h1>
                                    <?= $form->field($model, 'address_line_1')->textInput(['maxlength' => true, 'placeholder' => 'Address Line 1*']) ?>

                                    <?= $form->field($model, 'address_line_2')->textInput(['maxlength' => true, 'placeholder' => 'Adddress Line 2*']) ?>

                                    <?php
                                    $states = \app\models\States::find()->all();
                                    $stateData = ArrayHelper::map($states, 'code', 'name');
                                    ?>
                                    <?= $form->field($model, 'state')->dropDownList($stateData, ['prompt' => 'Select state', 'url' => \Yii::$app->getUrlManager()->createUrl('users/getcity')]); ?>

                                    <?php
                                    $cities = \app\models\Cities::find()->where(['state_code' => $model->state])->all();
                                    $cityData = ArrayHelper::map($cities, 'code', 'city_name');
                                    // echo $model
                                    ?>
                                    <?= $form->field($model, 'city')->dropDownList($cityData, ['prompt' => 'Select city', 'url' => \Yii::$app->getUrlManager()->createUrl('advisers/getregions'), $model->city => array('selected' => true)]); ?>

                                    <?php
                                    $regions = \app\models\Regions::find()->where(['city_id' => $model->city])->all();
                                    $cityData = ArrayHelper::map($regions, 'id', 'name');
                                    ?>


                                    <?= $form->field($model, 'pincode')->textInput(['maxlength' => true, 'placeholder' => 'Pincode*']) ?>


                                    <div class="lineheightbg"></div>



                                    <!-- upload document area -->


                                </div>
                                <!-- Emergency contact details area -->

                                <div class="changepassword">

                                    <h1>Bank details</h1>
                                    <?= $form->field($model, 'account_holder_name')->textInput(['maxlength' => true, 'placeholder' => 'Account Holder Name*', 'class' => 'form-control isCharacter']) ?>

                                    <?= $form->field($model, 'bank_name')->textInput(['maxlength' => true, 'placeholder' => 'Bank Name*', 'class' => 'form-control isCharacter']) ?>

                                    <?= $form->field($model, 'bank_branchname')->textInput(['maxlength' => true, 'placeholder' => 'Bank Branch Name*', 'class' => 'form-control isCharacter']) ?>
                                    <?= $form->field($model, 'bank_ifcs')->textInput(['maxlength' => true, 'placeholder' => 'IFSC Code*'])->label('IFSC Code') ?>
                                    <?= $form->field($model, 'account_number')->textInput(['maxlength' => true, 'placeholder' => 'Bank Account Number*', 'class' => 'form-control']) ?>
                                    <?= $form->field($model, 'pan_number')->textInput(['maxlength' => true, 'placeholder' => 'Pan Number*']) ?>

                                    <?= $form->field($model, 'service_tax_number')->textInput(['maxlength' => true, 'placeholder' => 'Service Tax Number*', 'class' => 'form-control']) ?>
                                </div>

                                <div class="changepassword">
                                    <div class="uploadbox">
                                        <div class="changepassword">
                                            <h1 class="advisortext">Identity and Address Proof Documents</h1></div></div>
                                    <div class="row">
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

                                <div class="lineheightbg"></div><br/><br/>

                                <div class="row">
                                    <div class="chancelall">
                                        <?= $form->field($model, 'cancelled_check')->fileInput(['accept' => 'image/*', 'onchange' => "readURL13(this);"])->label('Cancelled Cheque') ?>
                                        <div class="questionpopup_edit_p cancelled_check_new">
                                            <a href="javascript:;" data-toggle="popover" title="" data-content='<p class="circlebox1">The account holder name on cheque should match advisor name.' data-html="true"  data-placement="left">?</a>
                                        </div>
                                        <div class="uploadbox <?= !empty($model->cancelled_check) ? 'canceledCheck' : '' ?>">

                                            <div class="col-md-6 col-sm-6">
                                                <img  id="blah" src="<?= !empty($model->cancelled_check) ? "../" . $model->cancelled_check : Url::home(true) . 'images/doumentupload.jpg' ?>" alt="..." >
                                                <?= !empty($model->cancelled_check) ? '<a class="removeCancelledCheck" attr-id="' . $model->advisor_id . '"></a>' : '' ?>
                                                <p class="identityp">Cancelled Cheque</p>

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
                                    .questionpopup_edit_p.cancelled_check_new {
                                        float: right;
                                        font-size: 20px;
                                        margin-right: 79%;
                                        margin-top: -56px;
                                    }
                                    .uploadbox img {
                                        width: 96%;
                                        height: 200px;
                                    }
                                </style>
                                <style>
                                    .questionpopup {
                                        position: relative;
                                    }
                                    .questionpopup11 {
                                        position: relative;
                                        right: 0px;
                                    }
                                </style>

                                <br>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <?= Html::submitButton('Save', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn savechanges_submit_contractaction']) ?>

                                    </div>
                                    <div class="col-lg-6">
                                        <a style="color:#FFF" class="btn savechanges_submit cancel_confirm_1" href="<?= Url::home(true); ?>sales/applicants" type="button">Cancel</a>

                                    </div>
                                </div>




                            </div>

                            <div class="col-md-2 col-sm-2" style="margin-left: 10px;">
                                <div class="myprofile supportperson mytop">
                                    <a href="" class="thumbnail" onclick="apply_image('advisorprofile-profile_image'); return false;">
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




























            <div id="menu1" class="tab-pane fade <?= $second_class ?>">
                <div class="col-lg-9 col-xs-offset-1">
                    <h3>Lead information</h3>
                </div>
                <div class="row">
                    <div class="col-lg-9 col-xs-offset-1">

                        <div class="advisorbox">
                            <?php $formLeads = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

                            <?php
                            $regions = \app\models\RefStatus::find()->all();
                            $cityData = ArrayHelper::map($regions, 'id', 'name');
                            ?>
                            <?= $formLeads->field($modelLeads, 'ref_status')->dropDownList($cityData, array('options' => array('6' => array('selected' => true))))->label('Lead Status');
                            ?>
                            <?= $formLeads->field($modelLeads, 'follow_up_date_time')->textInput(['maxlength' => true, 'placeholder' => 'DD-MMM-YYYY h:i:s', 'class' => 'datetimepickerss form-control']) ?>

                            <?= $formLeads->field($modelLeads, 'schedule_date_time')->textInput(['maxlength' => true, 'placeholder' => 'DD-MMM-YYYY h:i:s', 'class' => 'form-control datetimepickerss'])->label('Schedule Start Date Time') ?>

                            <div>
                                <label for="exampleInputName2">Assigned sales person name</label>&nbsp;&nbsp;<span class="glyphicon glyphicon-search" data-toggle="modal" data-target=".bs-modal-sm1"></span>
                                <input type="text" id="sales_name" readonly value="<?= Yii::$app->userdata->getFullNameById($model->sales_id); ?>" class="form-control" placeholder="Assigned Sales Person Name">
                                <?= $formLeads->field($model, 'sales_id')->hiddenInput()->label(false) ?>

                            </div>





                            <script>


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
                                        if ($('#comments-description').val() == '') {
                                            alert('Please enter comment.');
                                            return false;

                                        }
                                        startLoader();

                                        $.ajax({
                                            type: "POST",
                                            url: 'addcomment',
                                            data: 'comments=' + $('#comments-description').val() + '&user_id=' + $('#comments-user_id').val(), //only input
                                            success: function (response) {
                                                hideLoader();
                                                if (response == 'done') {
                                                    // $("#comment_form")[0].reset();
                                                    $('#comments-description').val('');
                                                    $('#dataComments').load(' #dataComments');
                                                    // location.reload();
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
                                    });
                                });

                                $(document).on('click', '.activateAdvisor', function () {
                                    var element = $(this);
                                    var action = element.attr('data-action');
                                    if (action == '0') {
                                        activateAdviserFunc(element);
                                    } else {
                                        var formdata1 = new FormData($('#w0')[0]);
                                        startLoader();
                                        $.ajax({
                                            type: "POST",
                                            url: 'advisersdetailsajax?id=<?= $_GET['id'] ?>',
                                            data: formdata1,
                                            processData: false,
                                            contentType: false,
                                            success: function (response) {
                                                var formdata2 = new FormData($('#w1')[0]);
                                                $.ajax({
                                                    type: "POST",
                                                    url: 'advisersdetailsajax?id=<?= $_GET['id'] ?>',
                                                    data: formdata2,
                                                    processData: false,
                                                    contentType: false,
                                                    success: function (response) {
                                                        hideLoader();
                                                        var abc = activateAdviserFunc(element);
                                                        if (abc) {
                                                            location.reload();
                                                        }
                                                        // location.reload();
                                                    },
                                                    error: function (data) {
                                                        hideLoader();
                                                        // alert(data.responseText);
                                                        alert('Failure');
                                                    }
                                                })

                                            },
                                            error: function (data) {
                                                hideLoader();
                                                alert('Some error occured');
                                            }
                                        })
                                    }
                                })


                                function activateAdviserFunc(element) {
                                    // var element = $(this);
                                    var id = element.attr('data-id');
                                    var action = element.attr('data-action');
                                    startLoader();
                                    $.ajax({
                                        type: "POST",
                                        url: 'activateadvisor',
                                        data: 'id=' + id + '&action=' + action,
                                        dataType: 'json',
                                        success: function (response) {
                                            if (response.success == '1') {
                                                hideLoader();
                                                if (action == '0') {
                                                    element.text('Activate Adviser');
                                                    element.attr('data-action', '1');
                                                    alert('Advisor Deactivated Successfully');
                                                    return true;
                                                } else {
                                                    element.text('Deactivate Adviser');
                                                    element.attr('data-action', '0');
                                                    alert('Advisor Activated Successfully');
                                                    return true;
                                                }
                                            } else {
                                                hideLoader();
                                                alert(response.msg);
                                                return true;
                                            }

                                        }
                                    });
                                }
                            </script>




                            <div class="clearfix"></div>








                            <div class="row">



                                <div class="col-md-1 note_c">
                                    <p>Notes</p>
                                </div>
                                <div class="col-md-12 comment_c">
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
                                <span style="width: auto;margin-right: 10px;color: grey;"><b>Advisor Company:</b> <?= ($model->ad_company == '') ? 'Not Mentioned' : $model->ad_company ?></span><br>
                                <span style="margin-right: 10px;color: grey;"><b>No.of properties:</b> <?= ($model->ad_properties == '') ? 'Not Mentioned' : $model->ad_properties ?></span>
                                <!--<span style="width: auto;margin-right: 10px;color: grey;font-size: 15px;width:100%;margin-top:10px;margin-bottom:10px"><b>Advisor Comments:</b></span><br>-->
                                <span style="width: auto;margin-right: 10px;color: grey;"><b>Advisor Comments:</b> <?= ($model->comment == '') ? 'No Comments' : $model->comment ?></span><br> 
                                <?php
                                if ($comments) {
                                    foreach ($comments as $comment) {
                                        ?>
                                        <p><?= $comment->description; ?>,
                                            <span>-<?= Yii::$app->userdata->getFullNameById($comment->created_by); ?> on  <?= date('d-M-Y', strtotime($comment->created_date)); ?> At  <?= date('h:i A', strtotime($comment->created_date)); ?></span>
                                        </p>
                                        <?php
                                    }
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
                            <?= $form->field($agreements, 'start_date')->textInput(['class' => "form-control datepicker advisersdetails", 'value' => !empty($agreements) ? $agreements->start_date : '0', 'placeholder' => 'DD-MM-YYYY']) ?>
                            <?= $form->field($agreements1, 'end_date')->textInput(['class' => "form-control datepicker", 'value' => !empty($agreements) ? $agreements->end_date : '0', 'placeholder' => 'DD-MM-YYYY']) ?>
                            <?= $form->field($agreements, 'id')->hiddenInput(['value' => !empty($agreements) ? $agreements->id : '0'])->label(false) ?>
                            <h5 class="advisortext">Advisor Agreement</h5>
                            <?php if (isset($agreements) && $agreements->agreement_doc != '') {
                                ?>
                                <div class="previewDoc ">
                                    <iframe src="http://docs.google.com/gview?url=<?= Url::home(true) . $agreements->agreement_doc ?>&embedded=true" class="abc" width="100%" frameborder="0" allowfullscreen></iframe>
                                </div><br/>
                                <?php
                            }
                            ?>

                            <label>Upload New Agreement</label>

                            <?= $form->field($agreements, 'agreement_doc')->fileInput(['accept' => ".doc,.docx,.pdf"])->label(false) ?>

                            <div class="row">
                                <br />
                                <div class="col-lg-4 text-right">
                                    <?= Html::submitButton('Save', ['class' => $modelLeads->isNewRecord ? 'btn btn-success' : 'btn savechanges_submit_contractaction']) ?>

                                </div>
                                <div class="col-lg-4">
                                    <a style="color:#FFF" class="btn savechanges_submit cancel_confirm_1" href="<?= Url::home(true); ?>sales/applicants" type="button">Cancel</a>

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

            <div id="lead-assignment" class="tab-pane fade">
                <div class="col-lg-9 col-sm-9 col-xs-offset-1">
                    <h3>Lead assignment</h3>
                </div>
                <div class="row">
                    <div class="col-lg-9 col-sm-9 col-xs-offset-1">
                        <div class="advisorbox">

                            <?php $formLeads = ActiveForm::begin(['id' => 'advisor_lead_assignment', 'action' => 'leadassignmentadvisor', 'method' => 'post', 'options' => ['enctype' => 'multipart/form-data']]); ?>
                            <input type="hidden" value="1" name="lead_assignment" />
                            <?php
                            $userModel = app\models\User::findOne(['login_id' => $modelLeads->email_id]);
                            $advisorProfileModel = app\models\AdvisorProfile::findOne(['advisor_id' => $userModel->id]);
                            $currentRoleCode = NULL;
                            $currentRoleValue = NULL;
                            $currentBranchCode = $advisorProfileModel->branch_code;

                            if (Yii::$app->user->identity->user_type == 6) {
                                $currentSaleProfile = app\models\SalesProfile::findOne(['sale_id' => Yii::$app->user->id]);
                                $currentRoleCode = @$currentSaleProfile->role_code;
                                $currentRoleValue = @$currentSaleProfile->role_value;
                            } else if (Yii::$app->user->identity->user_type == 7) {
                                $currentOperationProfile = app\models\OperationsProfile::findOne(['operations_id' => Yii::$app->user->id]);
                                $currentRoleCode = @$currentOperationProfile->role_code;
                                $currentRoleValue = @$currentOperationProfile->role_value;
                            }

                            $advisorId = $advisorProfileModel->advisor_id;
                            $ownerOperation = $advisorProfileModel->operation_id;
                            $ownerSale = $advisorProfileModel->sales_id;

                            $operationRoleCode = null;
                            $operationRoleValue = null;

                            $saleRoleCode = null;
                            $saleRoleValue = null;

                            if (!empty($ownerOperation)) {
                                $operationProfileModel = app\models\OperationsProfile::findOne(['operations_id' => $ownerOperation]);
                                $operationRoleCode = $operationProfileModel->role_code;
                                $operationRoleValue = $operationProfileModel->role_value;
                            }

                            if (!empty($ownerSale)) {
                                $saleProfileModel = app\models\SalesProfile::findOne(['sale_id' => $ownerSale]);
                                $saleRoleCode = $saleProfileModel->role_code;
                                $saleRoleValue = $saleProfileModel->role_value;
                            }
                            ?>
                            <?php
                            $regions = \app\models\RefStatus::find()->all();
                            $cityData = ArrayHelper::map($regions, 'id', 'name');
                            //echo "<pre>";print_r($modelLeads);echo "</pre>";die;
                            if ($modelLeads->ref_status != 0) {
                                $stat = $modelLeads->ref_status;
                            } else {
                                $stat = 6;
                            }
                            ?>

                            <input type="hidden" name="operation_role_code" value="<?= $operationRoleCode ?>" />
                            <input type="hidden" name="operation_role_value" value="<?= $operationRoleValue ?>" />
                            <input type="hidden" name="sale_role_code" value="<?= $saleRoleCode ?>" />
                            <input type="hidden" name="sale_role_value" value="<?= $saleRoleValue ?>" />
                            <input type="hidden" name="redirect" value="<?= $_GET['id'] ?>" />

                            <input type="hidden" name="advisor_id" value="<?= $advisorId ?>" />

                            <?php //print_r($modelLeads); //echo $formLeads->field($modelLeads, 'ref_status')->dropDownList($cityData, array('options' => array($stat => array('selected' => true))))->label('Lead Status');   ?>

                            <div class="form-group field-leadsowner-ref_status has-success" style="display: none;">
                                <label class="control-label" for="leadsowner-ref_status">Branch Assigned To</label>
                                <input type="text" readonly="readonly" name="assigned_to" value="<?= (!empty($modelLeads->branch_code)) ? $modelLeads->branch_code : ""; ?>" placeholder="<?= (!empty($modelLeads->branch_code)) ? $modelLeads->branch_code : "N/A"; ?>" class="form-control" />
                                <div class="help-block"></div>
                            </div><br />

                            <label class="control-label" for="assigned_level_1">
                                <?php
                                $assignLevelCheck = ($operationRoleCode == 'OPCTMG' || $saleRoleCode == 'SLCTMG') ? true : false;
                                $assignLevelCheck2 = ($operationRoleCode == 'OPBRMG' && $saleRoleCode == 'SLBRMG') ? true : false;
                                $assignLevelCheck3 = ($operationRoleCode == 'OPBRMG' && $saleRoleCode == 'SLEXE') ? true : false;
                                $assignLevelCheck4 = ($operationRoleCode == 'OPEXE' && $saleRoleCode == 'SLBRMG') ? true : false;
//$assignLevelCheck6 = (($currentRoleCode == 'OPBRMG' || $currentRoleCode == 'SLBRMG') && ($operationRoleCode != 'OPCTMG' || $saleRoleCode != 'SLCTMG')) ? true : false ;

                                $assignLevelCheck5 = ($operationRoleValue == 'ELHQ') ? true : false;
                                ?>

                                <?php if ($currentRoleCode != 'OPEXE' && $currentRoleCode != 'SLEXE') { ?>
                                    <input <?= ($assignLevelCheck) ? 'checked' : '' ?> type="radio" name="assigned_level" value="1" class="assigned_level" id="assigned_level_1" />
                                    City Level &nbsp;&nbsp;</label>
                            <?php } ?>

                            <label class="control-label" for="assigned_level_2">
                                <input <?= ($assignLevelCheck2 || $assignLevelCheck3 || $assignLevelCheck4/* || $assignLevelCheck6 */) ? 'checked' : '' ?> type="radio" name="assigned_level" value="2" class="assigned_level" id="assigned_level_2" />
                                Branch Level &nbsp;&nbsp;</label>

                            <?php if (($currentRoleCode == 'OPCTMG' || $currentRoleCode == 'SLCTMG' || $currentRoleCode == 'OPSTMG' || $currentRoleCode == 'SLSTMG') || $currentRoleValue == 'ELHQ') { ?>
                                <label class="control-label" for="assigned_level_3">
                                    <input <?= ($assignLevelCheck5) ? 'checked' : ''; ?> type="radio" name="assigned_level" value="3" class="assigned_level" id="assigned_level_3" />
                                    ELHQ Level &nbsp;&nbsp;</label>
                            <?php } ?>

                            <br /><br />

                            <div class="form-group field-leadsowner-ref_status has-success">
                                <label class="control-label" for="leadsowner-ref_status">City</label>
                                <select class="form-control" name="assigned_city" id="assigned_city">
                                    <option value="">Select City</option>
                                    <?php
                                    $userType = Yii::$app->user->identity->user_type;
                                    $userId = Yii::$app->user->identity->id;
                                    if ($userType == 7) {
                                        $opsModel = \app\models\OperationsProfile::findOne(['operations_id' => $userId]);
                                        if ($opsModel->role_code == 'OPEXE' && $opsModel->role_value == 'ELHQ') {
                                            $cities = \app\models\Cities::find()->where(['status' => 1])->all();
                                        } else if ($opsModel->role_code == 'OPSTMG') {
                                            $cities = \app\models\Cities::find()->where(['status' => 1, 'state_code' => $opsModel->role_value])->all();
                                        } else if ($opsModel->role_code == 'OPCTMG') {
                                            $cities = \app\models\Cities::find()->where(['status' => 1, 'code' => $opsModel->role_value])->all();
                                        } else if ($opsModel->role_code == 'OPBRMG') {
                                            $branchModel = \app\models\Branches::findOne(['branch_code' => $opsModel->role_value]);
                                            $cities = \app\models\Cities::find()->where(['status' => 1, 'code' => $branchModel->city_code])->all();
                                        } else if ($opsModel->role_code == 'OPEXE') {
                                            $branchModel = \app\models\Branches::findOne(['branch_code' => $opsModel->role_value]);
                                            $cities = \app\models\Cities::find()->where(['status' => 1, 'code' => $branchModel->city_code])->all();
                                        }
                                    } else if ($userType == 6) {
                                        $saleModel = \app\models\SalesProfile::findOne(['sale_id' => $userId]);
                                        if ($saleModel->role_code == 'SLEXE' && $saleModel->role_value == 'ELHQ') {
                                            $cities = \app\models\Cities::find()->where(['status' => 1])->all();
                                        } else if ($saleModel->role_code == 'SLSTMG') {
                                            $cities = \app\models\Cities::find()->where(['status' => 1, 'state_code' => $saleModel->role_value])->all();
                                        } else if ($saleModel->role_code == 'SLCTMG') {
                                            $cities = \app\models\Cities::find()->where(['status' => 1, 'code' => $saleModel->role_value])->all();
                                        } else if ($saleModel->role_code == 'SLBRMG') {
                                            $branchModel = \app\models\Branches::findOne(['branch_code' => $saleModel->role_value]);
                                            $cities = \app\models\Cities::find()->where(['status' => 1, 'code' => $branchModel->city_code])->all();
                                        } else if ($saleModel->role_code == 'SLEXE') {
                                            $branchModel = \app\models\Branches::findOne(['branch_code' => $saleModel->role_value]);
                                            $cities = \app\models\Cities::find()->where(['status' => 1, 'code' => $branchModel->city_code])->all();
                                        }
                                    }

                                    foreach ($cities as $city) {
                                        //$citiesModel = \app\models\Cities::find(['code' => $modelLeads->lead_city])->one();
                                        echo $modelLeads->lead_city;
                                        ?>

                                        <option <?= ($modelLeads->lead_city == $city->code) ? 'selected' : '' ?> value="<?= $city->code; ?>"><?= ($city->city_name); ?></option>

                                    <?php }
                                    ?>

                                </select>
                                <div class="help-block"></div>
                            </div>

                            <div class="form-group field-leadsowner-ref_status has-success">
                                <label class="control-label" for="leadsowner-ref_status">Branch</label>
                                <select class="form-control" name="assigned_branch" id="assigned_branch">
                                    <option value="">Select Branch</option>
                                    <?php
                                    if ($userType == 7) {
                                        $opsModel = \app\models\OperationsProfile::findOne(['operations_id' => $userId]);
                                        if ($opsModel->role_code == 'OPEXE' && $opsModel->role_value == 'ELHQ') {
                                            $branches = \app\models\Branches::find()->where('branch_code != :branch_code', ['branch_code' => 'NA'])->all();
                                        } else if ($opsModel->role_code == 'OPSTMG') {
                                            $branches = \app\models\Branches::find()->where('branch_code != :branch_code AND state_code = :state_code', ['branch_code' => 'NA', 'state_code' => $opsModel->role_value])->all();
                                        } else if ($opsModel->role_code == 'OPCTMG') {
                                            $branches = \app\models\Branches::find()->where('branch_code != :branch_code AND city_code = :city_code', ['branch_code' => 'NA', 'city_code' => $opsModel->role_value])->all();
                                        } else if ($opsModel->role_code == 'OPBRMG') {
                                            $branches = \app\models\Branches::find()->where('branch_code = :branch_code', ['branch_code' => $opsModel->role_value])->all();
                                        } else if ($opsModel->role_code == 'OPEXE') {
                                            $branches = \app\models\Branches::find()->where('branch_code = :branch_code', ['branch_code' => $opsModel->role_value])->all();
                                        }
                                    } else if ($userType == 6) {
                                        $saleModel = \app\models\SalesProfile::findOne(['sale_id' => $userId]);
                                        if ($saleModel->role_code == 'SLEXE' && $saleModel->role_value == 'ELHQ') {
                                            $branches = \app\models\Branches::find()->where('branch_code != :branch_code', ['branch_code' => 'NA'])->all();
                                        } else if ($saleModel->role_code == 'SLSTMG') {
                                            $branches = \app\models\Branches::find()->where('branch_code != :branch_code AND state_code = :state_code', ['branch_code' => 'NA', 'state_code' => $saleModel->role_value])->all();
                                        } else if ($saleModel->role_code == 'SLCTMG') {
                                            $branches = \app\models\Branches::find()->where('branch_code != :branch_code AND city_code = :city_code', ['branch_code' => 'NA', 'city_code' => $saleModel->role_value])->all();
                                        } else if ($saleModel->role_code == 'SLBRMG') {
                                            $branches = \app\models\Branches::find()->where('branch_code = :branch_code', ['branch_code' => $saleModel->role_value])->all();
                                        } else if ($saleModel->role_code == 'SLEXE') {
                                            $branches = \app\models\Branches::find()->where('branch_code = :branch_code', ['branch_code' => $saleModel->role_value])->all();
                                        }
                                    }

                                    foreach ($branches as $branch) {
                                        ?>

                                        <option <?= ($modelLeads->branch_code == $branch->branch_code) ? 'selected' : '' ?> value="<?= $branch->branch_code; ?>"><?= $branch->name . " [" . $branch->branch_code . "]"; ?></option>

                                    <?php }
                                    ?>
                                </select>
                                <div class="help-block"></div>
                            </div>

                            <div class="form-group field-leadsowner-ref_status has-success">
                                <label class="control-label" for="leadsowner-ref_status">Operation's Member</label>
                                <select class="form-control" name="assigned_operation" id="assigned_operation">
                                    <option value="">Select Operation</option>
                                    <?php if (!empty($advisorProfileModel->operation_id)) { ?>
                                        <option selected="selected" value="<?= $advisorProfileModel->operation_id; ?>"><?= (Yii::$app->userdata->getEmailById($advisorProfileModel->operation_id)) . " [" . Yii::$app->userdata->getRoleCodeByUserIdOps($advisorProfileModel->operation_id) . "] [" . Yii::$app->userdata->getRoleValueByUserIdOps($advisorProfileModel->operation_id) . "]"; ?></option>
                                    <?php } ?>
                                    <?php
                                    if (!empty($modelLeads->branch_code)) {
                                        $opUsers = app\models\OperationsProfile::find()->where(' role_value = "' . $modelLeads->branch_code . '" ')->all();
                                        foreach ($opUsers as $op) {
                                            ?>
                                            <option value="<?= $op->operations_id; ?>"><?= ($op->email) . " [" . $op->role_code . "] [" . $op->role_value . "]"; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                                <div class="help-block"></div>
                            </div>

                            <div class="form-group field-leadsowner-ref_status has-success">
                                <label class="control-label" for="leadsowner-ref_status">Sale's Member</label>
                                <select class="form-control" name="assigned_sale" id="assigned_sale">
                                    <option value="">Select Sale</option>
                                    <?php if (!empty($advisorProfileModel->sales_id)) { ?>
                                        <option selected="selected" value="<?= $advisorProfileModel->sales_id; ?>"><?= (Yii::$app->userdata->getEmailById($advisorProfileModel->sales_id)) . " [" . Yii::$app->userdata->getRoleCodeByUserIdSales($advisorProfileModel->sales_id) . "] [" . Yii::$app->userdata->getRoleValueByUserIdSales($advisorProfileModel->sales_id) . "]"; ?></option>
                                    <?php } ?>
                                    <?php
                                    if (!empty($modelLeads->branch_code)) {
                                        $slUsers = app\models\SalesProfile::find()->where(' role_value = "' . $modelLeads->branch_code . '" ')->all();
                                        foreach ($slUsers as $sl) {
                                            ?>
                                            <option value="<?= $sl->sale_id; ?>"><?= ($sl->email) . " [" . $sl->role_code . "] [" . $sl->role_value . "]"; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                                <div class="help-block"></div>
                            </div>

                            <br />

                            <div class="row">
                                <div class="col-lg-6 col-sm-6">
                                    <?php echo Html::submitButton('Save', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn savechanges_submit_contractaction', 'id' => 'owner-lead-assign-btn', 'onclick' => 'return assignLead()']) ?>
                                </div>
                                <div class="col-lg-6 col-sm-6">
                                    <a style="color:#FFF" class="btn savechanges_submit cancel_confirm_2" href="<?= Url::home(true); ?>sales/applicants" type="button">Cancel</a>
                                </div>
                            </div>

                            <?php ActiveForm::end(); ?>

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
            </div>
        </div>
    </div>
</div>

<!-- /.col-lg-12 -->

<div class="modal fade bs-modal-sm" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
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
                                    <input  id="tags1" name="userid" type="text" class="form-control" placeholder="Search Support Person" class="input-medium" >
                                    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
                                    <script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
                                    <style>
                                        ul.ui-autocomplete {
                                            z-index: 1100;
                                        }
                                    </style>

                                    <script>
                                function assignLead() {
                                    var assignedLevel = $('input[name=assigned_level]:checked').val();
                                    var assignedOperation = $('#assigned_operation').val();
                                    var assignedSale = $('#assigned_sale').val();
                                    var assignedBranch = $('#assigned_branch').val();
                                    var assignedCity = $('#assigned_city').val();

                                    if (assignedLevel == '' || assignedLevel == null || assignedLevel == undefined) {
                                        alert('Please select "Level"');
                                        return false;
                                    }

                                    if (assignedLevel == 1) {
                                        if (assignedCity == '' || assignedCity == null || assignedCity == undefined) {
                                            alert('Please select "City"');
                                            return false;
                                        }

                                        if (assignedOperation == '' || assignedOperation == null || assignedOperation == undefined) {
                                            alert('Please select "Operation member"');
                                            return false;
                                        }

                                        if (assignedSale == '' || assignedSale == null || assignedSale == undefined) {
                                            alert('Please select "Sale member"');
                                            return false;
                                        }
                                    } else if (assignedLevel == 2) {
                                        if (assignedCity == '' || assignedCity == null || assignedCity == undefined) {
                                            alert('Please select "City"');
                                            return false;
                                        }

                                        if (assignedBranch == '' || assignedBranch == null || assignedBranch == undefined) {
                                            alert('Please select "Branch"');
                                            return false;
                                        }

                                        if (assignedOperation == '' || assignedOperation == null || assignedOperation == undefined) {
                                            alert('Please select "Operation member"');
                                            return false;
                                        }

                                        if (assignedSale == '' || assignedSale == null || assignedSale == undefined) {
                                            alert('Please select "Sale member"');
                                            return false;
                                        }
                                    } else if (assignedLevel == 3) {
                                        return true;
                                    }

                                    return true;
                                }

                                $(function () {
                                    $('#assigned_city').change(function () {
                                        var assignedCity = $(this).val();
                                        var assignedLevel = $('input[name=assigned_level]:checked').val();
                                        var url = null;
                                        if (assignedLevel == 1) {
                                            $.ajax({
                                                url: 'getopsbycitycode',
                                                type: 'POST',
                                                data: {city_code: assignedCity},
                                                success: function (data) {
                                                    $('#assigned_operation').html(data);
                                                },
                                                error: function (data) {
                                                    alert('Something went wrong.');
                                                }
                                            });

                                            $.ajax({
                                                url: 'getsalebycitycode',
                                                type: 'POST',
                                                data: {city_code: assignedCity},
                                                success: function (data) {
                                                    $('#assigned_sale').html(data);
                                                },
                                                error: function (data) {
                                                    alert('Something went wrong.');
                                                }
                                            });
                                        } else if (assignedLevel == 2) {
                                            $.ajax({
                                                url: 'getbranchbycitycode',
                                                type: 'POST',
                                                data: {city_code: assignedCity},
                                                success: function (data) {
                                                    $('#assigned_branch').html(data);
                                                },
                                                error: function (data) {
                                                    alert('Something went wrong.');
                                                }
                                            });
                                        } else {
                                            return;
                                        }
                                    });

                                    $('#assigned_branch').change(function () {
                                        var assignedBranch = $(this).val();
                                        var assignedLevel = $('input[name=assigned_level]:checked').val();
                                        var assignedCity = $('#assigned_city').val();
                                        $.ajax({
                                            url: 'getopsbybranchcode',
                                            type: 'POST',
                                            data: {city_code: assignedCity, branch: assignedBranch},
                                            success: function (data) {
                                                $('#assigned_operation').html(data);
                                            },
                                            error: function (data) {
                                                alert('Something went wrong.');
                                            }
                                        });

                                        $.ajax({
                                            url: 'getsalesbybranchcode',
                                            type: 'POST',
                                            data: {city_code: assignedCity, branch: assignedBranch},
                                            success: function (data) {
                                                $('#assigned_sale').html(data);
                                            },
                                            error: function (data) {
                                                alert('Something went wrong.');
                                            }
                                        });
                                    });

                                    $('.assigned_level').click(function () {
                                        var assignedLevel = $('input[name=assigned_level]:checked').val();

                                        $('#assigned_city').removeAttr('disabled');
                                        $('#assigned_branch').removeAttr('disabled');
                                        $('#assigned_operation').removeAttr('disabled');
                                        $('#assigned_sale').removeAttr('disabled');

                                        if (assignedLevel == 1) {
                                            $('#assigned_city').removeProp('disabled');
                                            $('#assigned_branch').prop('disabled', 'disabled');
                                            $('#assigned_operation').removeProp('disabled');
                                            $('#assigned_sale').removeProp('disabled');

                                            $('#assigned_city').val('');
                                            $('#assigned_branch').html('');
                                            $('#assigned_operation').html('');
                                            $('#assigned_sale').html('');
                                        }

                                        if (assignedLevel == 2) {
                                            $('#assigned_branch').removeProp('disabled');
                                            $('#assigned_city').removeProp('disabled');
                                            $('#assigned_operation').removeProp('disabled');
                                            $('#assigned_sale').removeProp('disabled');

                                            $('#assigned_city').val('');
                                            $('#assigned_branch').html('');
                                            $('#assigned_operation').html('');
                                            $('#assigned_sale').html('');
                                        }

                                        if (assignedLevel == 3) {
                                            $('#assigned_branch').prop('disabled', 'disabled');
                                            $('#assigned_city').prop('disabled', 'disabled');
                                            $('#assigned_operation').prop('disabled', 'disabled');
                                            $('#assigned_sale').prop('disabled', 'disabled');

                                            $('#assigned_city').html('');
                                            $('#assigned_branch').html('');
                                            $('#assigned_operation').html('');
                                            $('#assigned_sale').html('');
                                        }
                                    });

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

                                    <script>

                                        $(function () {
                                            $("#tags1").autocomplete({
                                                source: "getoperationsname",
                                                select: function (event, ui) {
                                                    event.preventDefault();
                                                    console.log(ui.item.value);
                                                    $("#operation_name").val(ui.item.label);
                                                    $("#operationemail").val(ui.item.email);
                                                    $("#operationphone").val(ui.item.phone);
                                                    $("#advisorprofile-operation_id").val(ui.item.value);
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
                                    <input required="" id="tags" name="userid" type="text" class="form-control" placeholder="Search Sales Person" class="input-medium" required="">
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
                                                    $("#advisorprofile-sales_id").val(ui.item.value);
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

</script>
<style>
    .field-applicantprofile-emer_contact_address label, .field-applicantprofile-emer_contact_indentity label{
        display:none;
    }
</style>
<script type="text/javascript">
    $(document).ready(function () {

        var assignedLevel = $('input[name=assigned_level]:checked').val();

        $('#assigned_city').removeAttr('disabled');
        $('#assigned_branch').removeAttr('disabled');
        $('#assigned_operation').removeAttr('disabled');
        $('#assigned_sale').removeAttr('disabled');

        if (assignedLevel == 1) {
            $('#assigned_city').removeProp('disabled');
            $('#assigned_branch').prop('disabled', 'disabled');
            $('#assigned_operation').removeProp('disabled');
            $('#assigned_sale').removeProp('disabled');
        }

        if (assignedLevel == 2) {
            $('#assigned_branch').removeProp('disabled');
            $('#assigned_city').removeProp('disabled');
            $('#assigned_operation').removeProp('disabled');
            $('#assigned_sale').removeProp('disabled');
        }

        if (assignedLevel == 3) {
            $('#assigned_branch').prop('disabled', 'disabled');
            $('#assigned_city').prop('disabled', 'disabled');
            $('#assigned_operation').prop('disabled', 'disabled');
            $('#assigned_sale').prop('disabled', 'disabled');
        }


        if ('<?= $end_date_error ?>' != "") {
            $('.field-advisoragreements-end_date').addClass('<?= $end_date_error_class ?>');
            $('.field-advisoragreements-end_date .help-block').text('<?= $end_date_error ?>');
        } else {
            $('.field-advisoragreements-end_date').removeClass('has-error');
            $('.field-advisoragreements-end_date .help-block').text('');
        }
    })
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#imgInp").change(function () {
        readURL(this);
    });

    $(document).on("click", ".cancel_confirm_1", function (e) {
        e.preventDefault();
        var element = $(this);
        var confirm_var = confirm('Are you sure you want to cancel ?');
        if (confirm_var == true) {
            window.history.back();
        } else {

        }
    });
    function readURL13(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah')
                        .attr('src', e.target.result)
                        .width(290)
                        .height(200);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

</script>




<style type="text/css">
    img.preview {
        width: 96%;
        height: 200px;
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
