<style>
    #propertyservicerequest-title, #propertyservicerequest-description, #property-message, #servicerequestattachment-attachment {
        background: whitesmoke;
        box-shadow: 1px 1px #f5f5f5;
    }
    input#propertyservicerequest-updated_date { 
        background: whitesmoke;
    }
    button#add_comment {
        width: 107%;
        background: #59abe3;
        margin-left: -15px;
    }
    div#dataComments {
        height: 270px;
        max-height: 486px;
        overflow-y: scroll;
    }
    div#comment_data {
        width: 100%;
        /* padding-left: 65px; */
    }
    hr.custom_hr_comment {
        border: 1px solid;
        /* width: 70%;
        margin-left: 65px; */
    }
    div#comment_data:nth-child(even) {
        text-align: right;
        /* padding-right: 66px */
    }
    div#comment_data:nth-child(odd) {
        /* background: #FFF */
    }
    div#comment_data_child:nth-child(even) {
        text-align: right;
        padding-right: 66px;
    }
    select.form-control {
        width: 89% !important;
        border-top: none;
        border-right: none;
        background: #f5f5f5;
        box-shadow: none;
        border-left: none;
        padding-left: 0;
        border: 1px solid #f5f5f5;
        border-bottom: 1px solid rgba(50, 58, 71, 0.2);
    }

    .bgstyle {
        background-color: #fafafa !important;
        background-color: var(--white);
    }

    .box+.box {
        margin-top: 2.5rem;
    }

    .well {
        min-height: 20px;
        padding-top: 19px;
        margin-bottom: 20px;
        background-color: transparent !important;
        border: none !important;
        border-radius: 0px !important;
        -webkit-box-shadow: none !important;
        box-shadow: none !important;
        padding-left: 0px;
    }

    .box {
        background-color: #e9e9e9;
        padding: 6.25rem 1.25rem;
    }

    .inputfile {
        width: 0.1px;
        height: 0.1px;
        opacity: 0;
        overflow: hidden;
        position: absolute;
        z-index: -1;
    }

    .input-group {
        position: relative;
        display: table;
        border-collapse: separate;
        width: 75%;
        padding-top: 15px;
    }

    input.form-control {
        border-top: none;
        border-right: navajowhite;
        background: #fafafa;
        box-shadow: none;
        border-left: navajowhite;
        padding-left: 0;
    }

    button#first_button {
        background-color: #F44336;
        border-color: #f44336;
        width: 39%;
        /* margin-top: -15px; */
        /* margin-top: -42px; */
        width: 140px;
        height: 40px;
        border-radius: 3px;
        font-size: 13px;
        font-weight: bold;
        font-style: normal;
        font-stretch: normal;
    }

    .identity_bg {
        /* background-color: #ffffff !important; */
        background-color: #f5f5f5;
        margin-top: 15px;
        margin-left: 15px;
        width: 47%;
    }

    .adar_img {
        width: 286px;
        height: 288px;
    }

    #content {
        width: 100%;
    }

    input.form-control {
        background: whitesmoke;
    }

    hr.custom_hr {
        border: 1px solid;
        /* margin-top: 90px; */
    }

    b#bold {
        font-size: 12px;
        font-weight: normal;
        font-style: normal;
        font-stretch: normal;
        line-height: 1.33;
        letter-spacing: 0.8px;
        text-align: left;
        color: #848c99;
        margin-left: 5px;
        margin-top: 10px;
        margin-bottom: 10px;
    }

    input.form-control {
        margin-left: 5px;
    }

    .form-line {
        min-width: 118%;
    }
    button#first_button {
        background-color: #59abe3;
        border-color: #59abe3;
        width: 39%;
        /* margin-top: -15px; */
        /* margin-top: -42px; */
        width: 140px;
        height: 40px;
        border-radius: 3px;
        font-size: 13px;
        font-weight: bold;
        font-style: normal;
        font-stretch: normal;
    }
    button#first_button1 {
        width: 140px;
        height: 40px;
        width: 140px;
        height: 40px;
        border-radius: 3px;
        font-size: 13px;
        font-weight: bold;
        font-style: normal;
        font-stretch: normal;
        background: #f44336 !important;
        border: 1px solid #f44336;
    }

</style>
<?php
$this->title = 'PMS- Create New Request';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
?>
<?php
$form = ActiveForm::begin([
            'options' => ['id' => 'dynamic-form111', 'enctype' => 'multipart/form-data']
        ]);
?>
<?php //echo "<pre>";print_r($attachModel);echo "</pre>";die;?>
<div id="content">
    <button type="button" id="sidebarCollapse" class="btn btn-info navbar-btn">
        <i class="glyphicon glyphicon-align-left"></i>
        <span></span>
    </button>
    <?php if (Yii::$app->session->hasFlash('success')) { ?>
        <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
            <?= Yii::$app->session->getFlash('success'); ?>
        </div>
    <?php } ?>
    <div class="row">
        <div class="col-md-6 col-sm-6">
            <h4><?= strtoupper(Yii::$app->userdata->getPropertyNameById(base64_decode($prop_id))); ?> - SERVICE REQUEST DETAILS</h4>
        </div>

        <div class="col-md-6 col-sm-6">
            <div class="col-md-8 col-sm-8">
                <!--button id="first_button" class="btn btn-info btn-lg pull-right">Save </button-->
                <?= Html::submitButton('Save', ['class' => 'btn btn savechanges_submit_contractaction btn-info save-btn btn-lg pull-right', 'id' => 'first_button']) ?>
            </div>
            <div class="col-md-4 col-sm-4">
                <button id="first_button1" class="btn btn-info btn-lg cancel-btn pull-right" onclick="cancel_confirm();" type="button">Cancel</button>

            </div>
        </div>
    </div>
    <hr/>

    <div class="row cust-reqdetls">
        <div>&nbsp;</div>
        <div class="col-md-6 col-sm-6 identity_bg">
            <div class="row">
                <div class="col-md-12 col-sm12" style="margin-top: 20px;margin-left: 25px;">
                    <b id="bold">Request Type</b>
                    <!--select class="form-control">
                            <option value="" class="" selected="selected">Select city</option>
                            <option label="Banglore" value="string:9">Banglore</option>
                            <option label="Mysore" value="string:5">Mysore</option>
                            <option label="mandya" value="string:4">mandya</option>
                            <option label="Manglore" value="string:3">Manglore</option>
                        </select-->
                    <?php
                    $typeData = ArrayHelper::map($requestType, 'id', 'name');
                    ?>
                    <?= $form->field($model, 'request_type')->dropDownList($typeData, ['prompt' => 'Select Request Type', 'class' => 'form-control'])->label(false); ?>
                </div>
                <div class="col-md-12" style="margin-left: 25px;">
                    <b id="bold">Status</b>
                    <?php
                    $statusData = ArrayHelper::map($status, 'id', 'name');
                    ?>
                    <?= $form->field($model, 'status')->dropDownList($statusData, ['prompt' => 'Select Status', 'class' => 'form-control'])->label(false); ?>
                </div>
                <div class="col-md-12" style="margin-left: 25px;">
                    <b id="bold">Title</b>
                    <div class="input-group colorpicker colorpicker-element textbox_space">
                        <div class="form-line">
                            <!--input type="text" class="form-control" value="1000"-->
                            <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'class' => 'form-control'])->label(false) ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-12" style="margin-top: 20px;margin-left: 25px;">
                    <b id="bold">Description</b>
                    <div class="input-group colorpicker colorpicker-element textbox_space">
                        <div class="form-line">
                            <!--input type="text" class="form-control" value="1000"-->
                            <?= $form->field($model, 'description')->textInput(['maxlength' => true, 'class' => 'form-control'])->label(false); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-12" style="margin-top: 20px;margin-left: 25px;">
                    <b id="bold">Attachment</b>
                    <div class="input-group colorpicker colorpicker-element">
                        <!--<div class="form-line">
                            <button type="button" class="btn">Choose File</button>
                        </div>
                        <span>No. File Choose</span>-->
                        <?php
                        foreach ($attachModel as $am) {
                            $namearr = (explode("/", $am['attachment']));
                            $countarr = count($namearr);
                            $imagename = $namearr[$countarr - 1];
                            ?>
                            <div style="color: #59abe3;width: 446px;word-wrap: break-word;" id="attachdiv_<?php echo $am['a_id']; ?>"><a href="<?php echo Url::home(true) . $am['attachment']; ?>" target="_blank"><?php echo $imagename; ?></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" onclick="deleteattach(<?php echo $am['a_id']; ?>);">X</a></div><br>
                        <?php } ?>
                        <?= $form->field($attachment, 'attachment[]')->fileInput(['multiple' => true, 'accept' => '*'])->label(false) ?>
                    </div>
                    <input type="hidden" name="deletedfile" id="deletedfile"></input>
                </div>
                <div class="col-md-12" style="margin-top: 10px;margin-left: 25px;">
                    <b id="bold">Update Date and Time</b>
                    <div class="input-group colorpicker colorpicker-element textbox_space">

                        <span><?= $form->field($model, 'updated_date')->textInput(['disabled' => 'disabled', 'class' => 'form-control', 'maxlength' => true])->label(false) ?></span>
                    </div>
                </div>
            </div>
            <br/>
            <br/>
            <div>&nbsp;</div>
        </div>
        <div class="col-md-6 col-sm-6 identity_bg">
            <div class="row">
                <div class="col-md-12" style="margin-top: 20px;">
                    <div class="form-group" style="width: 89% !important;margin-left: 25px;">
                        <label for="comment">Add Comment</label>
                        <textarea id="property-message" class="form-control" name="PropertyServiceComment[message]" rows="1" aria-required="true" aria-invalid="false" style="background: white;height: 64px;"></textarea>
                        <br/>
                        <div class="col-md-12">
                            <button type="button" class="btn" id="add_comment">Add Comment</button>
                        </div>
                        <br/>
                        <br />
                        <hr class="custom_hr">
                    </div>


                </div>
            </div>
            <div class="row cust-nocomments">
                <div class="col-md-12" id="dataComments">
                    <?php
                    if ($comments) {
                        foreach ($comments as $comment) {
                            ?>
                            <div class="input-group colorpicker colorpicker-element" id="comment_data">
                                <div id="comment_data_child">
                                    <div class="text-warp">
                                        <span style="word-break: break-word;"><?= $comment->message; ?></span><br/><br/>
                                    </div>
                                    <b>-<?= Yii::$app->userdata->getFullNameById($comment->user_id); ?> on  <?= date('d-M-Y', strtotime($comment->created_datetime)); ?> At  <?= date('h:i a', strtotime($comment->created_datetime)); ?></b>
                                    <br/>
                                </div>

                            </div>
                            <div></div>
                            <hr class="custom_hr_comment">
                            <?php
                        }
                    } else {
                        echo "<p> No comments</p>";
                    }
                    ?> 
                </div>
            </div>
            <br/>
            <div>&nbsp;</div>
        </div>

    </div>

</div>
<?php ActiveForm::end(); ?>
<div class="modal fade bs-modal-sm" id="modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
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
                                    <label> Search Property </label>
                                    <input required="" id="tags1" name="userid" type="text" class="form-control" placeholder="Search for Property" class="input-medium" required="">
                                    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
                                    <script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
                                    <script type="text/javascript" src="../js/moment.js"></script>	
                                    <script type="text/javascript" src="../js/timepicker.js"></script>
                                    <link rel="stylesheet" href="../css/timepicker.css" />
                                    <style>
                                        ul.ui-autocomplete {
                                            z-index: 1100;
                                        }
                                        .formtextnew {
                                            margin-top: 10px;
                                        }
                                        .col-lg-10 h2 {
                                            font-size: 19px;
                                            color: #34a506;
                                        }
                                        .lorembox {
                                            background: #f7f6f6 none repeat scroll 0 0;
                                            border: 1px solid #cccccc;
                                            float: left;
                                            padding: 20px;
                                            width: 100%;
                                            font-family: "Open Sans",sans-serif;
                                        }
                                        .lorembox p {
                                            color: #919191;
                                            font-size: 14px;
                                            padding-bottom: 20px;
                                            width: 57%;
                                            clear: both;
                                        }
                                        .lorembox > p:nth-child(2) {
                                            float: right;
                                            text-align: right;
                                        }
                                        .lorembox > p:nth-child(3) {
                                            clear: both;
                                        }
                                        .lorembox > p:nth-child(4) {
                                            float: right;
                                            text-align: right;
                                        }
                                        .lorembox span {
                                            color: #4b0081;
                                            float: left;
                                            font-size: 13px;
                                            width: 100%;
                                        }
                                        input#property_name {
                                            background: #ffffff;
                                        }
                                    </style>
                                    <script>
                            $(function () {
                                $(".datepicker12").datetimepicker({
                                    useCurrent: true,
                                    format: 'YYYY-M-D HH:mm:ss',
                                    sideBySide: true,
                                });
                            });
                                    </script>
                                    <script>

                                        $(function () {
                                            $("#tags1").autocomplete({
                                                source: "getproperty1",
                                                select: function (event, ui) {
                                                    event.preventDefault();
                                                    //  console.log(ui.item);
                                                    $("#property_name").val(ui.item.label);
                                                    $("#propertyservicerequest-property_id").val(ui.item.value);
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
    </div>
</div>

<script type="text/javascript">
    $(document).on('click', '#add_comment', function (e) {

        e.preventDefault();
        var message = $('#property-message').val();
        if (message.trim() == '') {
            return false;
        }
        //startLoader();
        //alert(message);
        var csrfToken = "<?= Yii::$app->request->getCsrfToken() ?>";
        $.ajax({
            url: "addservicecomment?rid=<?= Yii::$app->request->get('rid'); ?>",
            type: "POST",
            dataType: 'json',
            data: 'message=' + message + '&service_id="<?= base64_decode($_GET['id']); ?>"&_csrf=' + csrfToken,
            success: function (data) {
                //alert(data);
                var html = '';
                $(data).each(function (key, value) {
                    html = html + '<div class="input-group colorpicker colorpicker-element" id="comment_data"><div id="comment_data_child"><span>' + value.message + '</span><br/><br/><b>  -' + value.username + ' on  ' + value.created_date + ' At  ' + value.created_time + '</b><br/></div></div><div></div><hr class="custom_hr_comment">';
                });
                $('#dataComments').html(html);
                $('#property-message').val('');
                $('#propertyservicerequest-updated_date').val(data[0].updated_date);
                //hideLoader();
            }

        });
    })

    $(document).ready(function () {
        $('#servicerequestattachment-attachment').change(function () {
            var form = new FormData(document.getElementById('dynamic-form111'));
            form.append('upload_via_ajax', '1');
            form.append('rid', '<?= Yii::$app->request->get('rid') ?>');
            //startLoader();
            $.ajax({
                url: "requestdetails?rid=<?= Yii::$app->request->get('rid') ?>",
                type: "POST",
                dataType: 'json',
                contentType: false,
                data: form,
                processData: false,
                success: function (data) {
                    //hideLoader();
                    location.reload();
                },
                error: function (a, b, c) {
                    console.log('Error from server');
                    //hideLoader();
                }
            });
        });
    });

    function deleteattach(target)
    {
        /*var csrfToken = "<?= Yii::$app->request->getCsrfToken() ?>";
         $.ajax({
         url: "deleteattach",
         type: "POST",
         data: {'aid': target, '_csrf':csrfToken},
         success: function (data) {
         location.reload();
         }
         
         });*/
        $("#attachdiv_" + target).remove();
        var deletedfile = $('#deletedfile').val();
        if (deletedfile == '')
        {
            $('#deletedfile').val(target);
        } else {
            var finalfiles = deletedfile + ',' + target;
            $('#deletedfile').val(finalfiles);
        }
    }
    $(document).on("click", ".cancel_confirm_2", function (e) {
        e.preventDefault();
        var element = $(this);
        var confirm_var = confirm('Are you sure you want to cancel ?');
        if (confirm_var == true) {
            history.go(-1);

        }
    });
</script>
<script type="text/javascript">
    $(window).on('load', function () {
        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
        });
    });
</script>	
<script>
    function cancel_confirm() {
        var r = confirm("Are you sure you want to cancel ?");
        if (r == true) {
            window.location.replace("<?php echo Url::home(true); ?>site/ownerrequests?id=<?php echo $_REQUEST['id']; ?>");
                    }
                }
</script>
<style>
    div#comment_data_child {

        width: 89% !important;
        margin-left: 25px;
    }
    hr.custom_hr_comment{
        width: 89% !important;
        margin-left: 25px;
    }
    .input-group.colorpicker.colorpicker-element.textbox_space {
        margin-top: -10px;
        padding-top: 0px;
    }

    @media screen and (max-width: 767px){
        .save-btn{
            margin-left: 10px;
        }
        #dynamic-form111{
            width: 100%;
        }
        .identity_bg{
            margin-left: 0;
        }
    }
    @media screen and (max-width: 320px){
        .cancel-btn{
            width: 47% !important;
        }
        .save-btn{
            width: 47% !important;
        }
    }
    @media screen and (min-width: 768px) and (max-width: 1024px){
        .save-btn{
            margin-right: 36px !important;
        }
    }
</style>