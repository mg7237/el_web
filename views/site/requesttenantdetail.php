
<?php
$this->title = 'PMS- Create New Request';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

//echo $model->property_id;die;
?>
<style>

    .container
    {
        width:100%;
    }
    .bgstyle {
        background-color: #fafafa !important;
        background-color: var(--white);
        margin-left: 0px !important;
        /*padding-top: 15px;*/
    }
    #propertyservicerequest-title, #propertyservicerequest-description, #property-message, #servicerequestattachment-attachment {
        /*background: whitesmoke;*/
        box-shadow: 1px 1px #f5f5f5;

    }
    #servicerequestattachment-attachment {
        color: #59abe3;
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
        <?php if (count($attachModel) > 0) { ?>
            min-height: 270px !important;
            max-height: 270px !important;
            <?php
        } else {
            ?>
            min-height: 249px !important;
            max-height: 249px !important;
        <?php }
        ?>

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
        /*width: 89% !important;*/
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
        width: 85%;
        /* padding-top: 15px; */
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
        margin-right:20px;
    }

    .identity_bg {
        /* background-color: #ffffff !important; */
        background-color: #f5f5f5;
        /*margin-top: 15px;*/
        margin-left: 15px;
        width: 47.5%;

    }
    select.form-control,input.form-control
    {
        /*border: 1px solid #ccc;*/
        background-color:transparent !important;
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
        margin-top: 20px;
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
        /*margin-left: 5px;*/
        margin-top: 10px;
        margin-bottom: 10px;
    }

    input.form-control {
        /*margin-left: 5px;*/
    }
    select
    {
        text-indent:-1.5px;
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

    .form-group
    {
        margin-bottom:0px;
    }
    hr
    {
        margin-top: 10px;
        margin-bottom: 10px;
    }
    #attach-box
    {
        max-width: 100%;
        overflow: hidden;
        /*margin-left: 25px;*/
        color: #59abe3;
    }
    #attach
    {
        max-width:450px;
        overflow: hidden;
    }
    @media screen and (max-width: 320px){
        button#first_button{
            width: 95px !important;
        }
    }

    @media screen and (max-width: 767px){
        .identity_bg{
            width: 100%;
        }
        button#first_button{
            width: 117px;
        }
        #servicerequestattachment-attachment{
            width: 100%;
        }
        .cust-hr{
            width: 80%;
        }
        button#add_comment{
            width: 112%;
        }
        .cmt-mar{
            margin-top: -15px;
        }
        hr.custom_hr{
            margin-top: 0px;
        }
        div#dataComments{
            min-height: 0px !important;
        }
        .tit-mar{
            margin-top: 15px !important;
        }
    }

</style>
<?php
$form = ActiveForm::begin([
            'options' => ['id' => 'dynamic-form111', 'enctype' => 'multipart/form-data']
        ]);
?>

<div id="content">
    <button type="button" id="sidebarCollapse" class="btn btn-info navbar-btn">
        <i class="glyphicon glyphicon-align-left"></i>
        <span></span>
    </button>
    <div class="container" style="margin-top: -42px;">
        <div class="col-md-4">
            <h4>SERVICE REQUEST</h4>
        </div>
        <div class="col-md-8">
            <button id="first_button" class="btn btn-info btn-lg pull-right cancelbutton cus_show" onclick="cancelRequest()" style="background-color: #e24c3f !important;
                    border-color: #e24c3f !important; margin-right:5px !important;">Cancel</button>
            <!--button id="first_button" class="btn btn-info btn-lg pull-right">Save </button-->
            <?= Html::submitButton('Save', ['class' => 'btn btn savechanges_submit_contractaction btn-info btn-lg pull-right', 'id' => 'first_button']) ?>



        </div>

    </div>
    <hr class="cust-hr">
    <!--<div class="row">
        <div class="col-md-7">
            <h4>Service Request Details</h4>
    </div>
    </div>-->
    <div class="container">
        <!--<div>&nbsp;</div>-->
        <div class="col-md-6 col-sm-12 identity_bg">
            <div class="row">
                <div class="col-md-12 tit-mar" style="margin-top: 10px;">
                    <b id="bold">Title</b>
                    <div class="input-group colorpicker colorpicker-element">
                        <div class="form-line">
                            <!--input type="text" class="form-control" value="1000"-->
                            <?= $form->field($model, 'title')->textInput(['maxlength' => true])->label(false) ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-12" style="margin-top: 10px;">
                    <b id="bold">Description</b>
                    <div class="input-group colorpicker colorpicker-element">
                        <div class="form-line">
                            <!--input type="text" class="form-control" value="1000"-->
                            <?= $form->field($model, 'description')->textInput(['maxlength' => true])->label(false); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-12" style="margin-top: 10px;">
                    <b id="bold">Request Type</b>
                    <?php
                    $typeData = ArrayHelper::map($requestType, 'id', 'name');
                    ?>

                    <?= $form->field($model, 'request_type')->dropDownList($typeData, ['prompt' => 'Select Request Type', 'class' => 'form-control fieldclass'])->label(false); ?>

                </div>
                <div class="col-md-12" style="margin-top: 10px;">
                    <b id="bold">Status</b>
                    <?php
                    $statusData = ArrayHelper::map($status, 'id', 'name');
                    ?>
                    <?php echo $form->field($model, 'status')->dropDownList($statusData, ['prompt' => 'Select Status'])->label(false); ?>

                    <?= $form->field($model, 'property_id')->hiddenInput(['maxlength' => true, 'class' => 'form-control fieldclass'])->label(false) ?>

                </div>

                <?php if (count($attachModel) > 0) { ?>
                    <div id="attach-box" class="col-lg-12 col-md-12 col-sm-12">
                        <?php foreach ($attachModel as $row) {
                            ?>
                            <div style="float:left; max-width: 94%;overflow: hidden;">
                                <a href="<?= Url::home(true) . $row['attachment']; ?>" title="Attached File, Click to view." target="_blank" >
                                    <?php
                                    $fileNameArr = explode('/', $row['attachment']);
                                    echo $fileNameArr[(count($fileNameArr) - 1)];
                                    ?>
                                </a>
                            </div>
                            <div style="float:left;">
                                <a style="text-decoration: none;" href="requesttenantattachremove?id=<?= base64_encode($row['a_id']); ?>&rid=<?= base64_encode(Yii::$app->request->get('id')); ?>" title="Remove Attachment">&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-remove"></span></a>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
                <div id="attach" class="col-md-12" style="margin-top: 10px;">
                    <b id="bold">Attachment</b>

                    <div class="input-group colorpicker colorpicker-element pickup_data">
                        <?= $form->field($attachment, 'attachment[]')->fileInput(['multiple' => true, 'accept' => '*'])->label(false) ?>
                    </div>
                </div>
                <div class="col-md-12" style="margin-top: 10px;">
                    <b id="bold">Update Date/Time</b>
                    <div class="input-group colorpicker colorpicker-element">

                        <div class="form-line"><?= $form->field($model, 'updated_date')->textInput(['disabled' => 'disabled', 'class' => 'datepicker12 form-control', 'maxlength' => true])->label(false); ?></div>
                    </div>
                </div>
            </div>
            <br/>
            <!--<br/>
            <div>&nbsp;</div>-->
        </div>
        <div class="col-md-6 col-sm-12 identity_bg">
            <div class="row">
                <div class="col-md-12" style="margin-top: 10px;">
                    <div class="form-group cmt-mar">
                        <label for="comment">Add Comment</label>
                        <!--textarea class="form-control" rows="5" id="comment"></textarea-->
                        <textarea id="property-message" class="form-control" name="PropertyServiceComment[message]" rows="2" style="max-width:100%; overflow:hidden;" aria-required="true" aria-invalid="false"></textarea>
                        <br/>
                        <div class="col-md-12">
                            <button type="button" class="btn" id="add_comment">Add Comment</button>
                        </div>
                    </div>
                    <br/>
                    <hr class="custom_hr">

                </div>

                <div class="col-md-12 lorembox" id="dataComments">

                    <?php
                    $i = 1;
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
                            $i++;
                        }
                    } else {
                        echo "<p> No comments</p>";
                    }
                    ?>
                </div>
                <!--div class="col-md-12">
                    <b id="bold">Number*</b>
                    <div class="input-group colorpicker colorpicker-element">
                        <div class="form-line focused">
                            <input type="text" class="form-control" value="+91 9876543210">
                        </div>
                    </div>
                </div-->
            </div>
            <br/>
            <!--<div>&nbsp;</div>-->
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
                                    <style>
                                        ul.ui-autocomplete {
                                            z-index: 1100;
                                        }
                                        .col-lg-10 h2 {
                                            font-size: 19px;
                                            color: #34a506;
                                        }
                                        .lorembox {
                                            background: #f7f6f6 none repeat scroll 0 0;
                                            /*border: 1px solid #cccccc;*/
                                            float: left;
                                            padding: 15px;
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
                                        .btn-default {
                                            color: #ffffff;
                                            background-color: #347c17;
                                            border-color: #347c17;
                                            border-radius: 0px;
                                            font-size: 18px !important;
                                            padding: 2px 13px;
                                        }
                                        .btn-default:hover {
                                            color: #ffffff;
                                            background-color: #347c17;
                                            border-color: #347c17;
                                            border-radius: 0px;
                                            font-size: 18px !important;
                                            padding: 2px 13px;
                                        }
                                    </style>
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
    function cancelRequest() {
        if (confirm("Are you sure you want to cancel?")) {
            window.history.back();
        }
    }

    $(document).on('click', '#add_comment', function (e) {
        e.preventDefault();
        var message = $('#property-message').val();
        if (message.trim() == '') {
            return false;
        }
        startLoader();
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        $.ajax({
            url: "addservicecomment2?id=<?= base64_encode(Yii::$app->request->get('id')); ?>",
            type: "POST",
            dataType: 'json',
            data: 'message=' + message + '&service_request_id=<?= $_GET['id']; ?>&_csrf=' + csrfToken,
            success: function (data) {
                var html = '';
                $(data).each(function (key, value) {
                    html = html + "<span>" + value.message + "</span><br/><br/><b>-" + value.username + " on  " + value.created_date + " At  " + value.created_time + "</b><br/><hr class='custom_hr'>";
                });
                $('#dataComments').html(html);
                $('#property-message').val('');
                $('#propertyservicerequest-updated_date').val(data[0].updated_date);
                hideLoader();
            }

        });
    })

    $(document).ready(function () {
        $('#servicerequestattachment-attachment').change(function () {
            var form = new FormData(document.getElementById('dynamic-form111'));
            form.append('upload_via_ajax', '1');
            form.append('id', '<?= Yii::$app->request->get('id') ?>');
            startLoader();
            $.ajax({
                url: "requesttenantdetails?id=<?= Yii::$app->request->get('id') ?>",
                type: "POST",
                dataType: 'json',
                contentType: false,
                data: form,
                processData: false,
                success: function (data) {
                    hideLoader();
                    location.reload();
                },
                error: function (a, b, c) {
                    console.log('Error from server');
                    hideLoader();
                }
            });
        });
    });
</script>
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
        /*border: 1px solid #cccccc;*/
        float: left;
        padding: 15px;
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

    .fieldclass,select#propertyservicerequest-status,.pickup_data     {
        /*margin-top: 15px;*/
        margin-top: 0px;
    }
    #content
    {
        min-height:0px;
    }
    @-moz-document url-prefix() {
        div#dataComments {
            min-height: 249px;
        }
    }
    /*@supports (-ms-ime-align: auto) {
      div#dataComments {
            min-height: 249px;
        }
    }*/

    _:-ms-lang(x), div#dataComments {
        min-height: 249px;
    }
</style>
