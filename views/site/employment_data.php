

<?php
$this->title = 'PMS- My profile';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$proofsAll = Yii::$app->userdata->getAllProofs($model->applicant_id);
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> 
<style>
    .cust-frame {
        width: 100%;
        margin-left: 9px;
        height: 150px;
    }
</style>

<div id="content">
    <button type="button" id="sidebarCollapse" class="btn btn-info navbar-btn">
        <i class="glyphicon glyphicon-align-left"></i>
        <span></span>
    </button>
    <div class="container" style="margin-top: -42px;">
        <div class="col-md-4">
            <h4 class="profile_heading">PROFILE</h4>
        </div>
        <div class="col-md-6">
            <button id="first_button" class="btn btn-info btn-lg pull-right editbutton cust-edit" onclick="makeeditable()">Edit</button>
            <button id="first_button" class="btn btn-info btn-lg pull-right savebutton cus_hide cust-save" onclick="saveform()" >Save</button>
            <button id="first_button" class="btn btn-info btn-lg pull-right cancelbutton cus_hide cust-save" style="margin-right:5px !important;background-color: #aea1a0 !important;border-color: #aea1a0 !important;" onclick="cancelform()">Cancel</button>
        </div>
    </div>
    <hr>
    <form method="post" enctype="multipart/form-data" action="<?= \Yii::$app->getUrlManager()->createUrl('site/editemployeedetail') ?>" name="employ_info" id="employ_info"> 
        <div class="container">
            <!-- <h4 class="col-md-4" style="margin-bottom: 22px !important;" id="personal_info">MY EMPLOYMENT DATA <span id="em_mark" class="cus_hide tooltip_span" data-toggle="tooltip" title="Enter Self employed if you are not working for any organization.">?</span></h4>				             -->
            <div class="container-fluid" style="margin-left: -15px;"> 
                <h4 class="col-md-4" style="margin-bottom: 22px !important;" id="personal_info">MY EMPLOYMENT DATA <span id="em_mark" class="cus_hide tooltip_span" data-toggle="tooltip" title="Enter Self employed if you are not working for any organization.">?</span></h4>
            </div>
            <div class="col-md-10 bgstyle disable_sec" id="disable_sec_div">
                <div class="col-md-8">
                    <div class="col-md-6 emp_details">
                        <b id="bold">Employer Name</b>
                        <div class="input-group cust-inputgrp colorpicker colorpicker-element">
                            <div class="form-line">
                                <input type="text" class="form-control" value="<?= $model->employer_name; ?>" readonly id="employer_name" name="employer_name">
                                <input type="hidden" id="_csrf" class="form-control name1" value="<?= Yii::$app->request->getCsrfToken() ?>" name="_csrf">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 emp_details">
                        <b id="bold">Employee Id</b>
                        <div class="input-group cust-inputgrp colorpicker colorpicker-element">
                            <div class="form-line focused">
                                <input type="text" class="form-control" value="<?= $model->employee_id; ?>" readonly id="employee_id" name="employee_id">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 emp_details">
                        <b id="bold">Employment Start Date</b>
                        <div class="input-group cust-inputgrp colorpicker colorpicker-element">
                            <div class="form-line focused">
                                <input type="text" class="form-control datepicker1" value="<?= $model->employment_start_date; ?>" disabled id="employment_start_date" name="employment_start_date">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 emp_details">
                        <b id="bold">Employment Email</b>
                        <div class="input-group cust-inputgrp colorpicker colorpicker-element">
                            <div class="form-line focused">
                                <input type="text" class="form-control" value="<?= $model->employment_email ?>" readonly id="employment_email" name="employment_email" onblur="checkEmail(this.value);">
                            </div>
                        </div>
                    </div>
                </div>
                <?php //echo $model->employmnet_proof_url;die;?>
                <div class="col-md-4">
                    <div class="col-md-12">
                        <p class="">Employment Proof</p>
                        <div class="well cust-bankwell" id="bank_well">
                            <div class="box" style=" margin-top: -45px; margin-left: -18px;">
                                <input type="file" id="uploadPDF" accept="application/pdf/doc/docx"  onchange="PreviewImage()" name="employment_proof"/>
                                <br/>
                                <div style="clear:both">
                                    <iframe id="viewer" frameborder="0" class="display_css_hide cust-frame" scrolling="no" width="400" height="600"></iframe>
                                </div>
                                <object data="<?php echo Url::home(true) . $model->employmnet_proof_url; ?>" type="application/pdf" width="230" height="250" id="editpdf">
                                    <a href="<?php echo Url::home(true) . $model->employmnet_proof_url; ?>">test.pdf</a>
                                </object>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
    </form> </div>
</div>

<script>
    function makeeditable()
    {
        document.getElementById("employer_name").readOnly = false;
        document.getElementById("employee_id").readOnly = false;
        //document.getElementById("employment_start_date").readOnly = false;
        document.getElementById("employment_email").readOnly = false;
        $("#employment_start_date").prop("disabled", false);
        $("#browse").prop("disabled", false);
        $("#employer_name").addClass("edit_input_bg");
        $("#employee_id").addClass("edit_input_bg");
        $("#employment_email").addClass("edit_input_bg");
        $("#employment_start_date").addClass("edit_input_bg");
        jQuery(".editbutton").hide();
        $("#disable_sec_div").removeClass("disable_sec");
        $(".savebutton").addClass("cus_show").removeClass("cus_hide");
        $(".cancelbutton").addClass("cus_show").removeClass("cus_hide");
        $("#em_mark").addClass("cus_show").removeClass("cus_hide");

    }
    function readURL(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#preview_image').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#browse").change(function () {
        readURL(this);
    });

    function cancelform()
    {
        location.reload();
    }

    function saveform()
    {
        /*var employer_name = $( "#employer_name" ).val();
         var employee_id = $( "#employee_id" ).val();
         var employment_start_date = $( "#employment_start_date" ).val();
         var employment_email = $( "#employment_email" ).val();
         var csrf = "<?= Yii::$app->request->getCsrfToken() ?>";
         $.ajax({
         url: "<?= \Yii::$app->getUrlManager()->createUrl('site/editemployeedetail') ?>",
         type: "POST",
         data:{"employer_name":employer_name,"employee_id":employee_id,"employment_start_date":employment_start_date,"employment_email":employment_email,"_csrf":csrf},
         success: function(html){
         location.reload();
         }
         });*/
        $("#employ_info").submit();
    }
    $(function () {
        $(".datepicker1").datepicker();
    });
    function checkEmail(str)
    {
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if (!re.test(str))
        {
            alert("Please enter a valid email address");
            $("#employment_email").val('');
        }
    }
    function PreviewImage() {
        pdffile = document.getElementById("uploadPDF").files[0];
        pdffile_url = URL.createObjectURL(pdffile);
        $('#viewer').attr('src', pdffile_url);
        $("#viewer").removeClass("display_css_hide").addClass("display_css_show");
        $("#editpdf").removeClass("display_css_show").addClass("display_css_hide");
    }

</script>
<style>
    @media screen and (max-width: 375px){
        #uploadPDF{
            width: 100% !important;
        }
    }
    @media screen and (max-width: 767px){
        #content{
            width: 100% !important;
        }
        #uploadPDF{
            width: 100% !important;
        }
        .cust-inputgrp{
            width: 100% !important;
        }
        .bgstyle{
            margin-left: 0;
        }
        .well{
            width: 100% !important;
        }
    }

    @media screen and (min-width: 768px) and (max-width: 920px){
        .cust-save{
            margin-right: 40% !important;
        }
        .cust-inputgrp{
            width: 100% !important;
        }
        .well{
            width: 100% !important;
        }
    }
    @media screen and (max-width: 320px){
        .cust-save{
            margin-right: -15% !important;
        }


    }
    .cust-frame{
        width: 100%;
        margin-left: 9px;
    }
    .tooltip_span{
        display: none !important;
    }
    .display_css_hide{
        display: none;
    }
    .display_css_show{
        display: block;
    }

    .cus_hide{
        display: none;
    }
    .cus_show{
        display: block;
    }

    input#employer_name,input#employee_id,input#employment_start_date,input#employment_email{
        background: #fafafa;
        margin-bottom: 10px;
    }
    .emp_details{
        margin-top: 30px;
    }
    .disable_sec{
        z-index: -10000;
    }

    #upload-button {
        width: 150px;
        display: block;
        margin: 20px auto;
    }

    #file-to-upload {
        // display: none;
    }

    #pdf-main-container {
        width: 400px;
        margin: 20px auto;
    }

    #pdf-loader {
        display: none;
        text-align: center;
        color: #999999;
        font-size: 13px;
        line-height: 100px;
        height: 100px;
    }

    #pdf-contents {
        display: none;
    }

    #pdf-meta {
        overflow: hidden;
        margin: 0 0 0px 0;
    }

    #pdf-buttons {
        float: left;
    }

    #page-count-container {
        /* float: right; */
    }

    #pdf-current-page {
        display: inline;
    }

    #pdf-total-pages {
        display: inline;
    }

    #pdf-canvas {
        border: 1px solid rgba(0,0,0,0.2);
        box-sizing: border-box;
    }

    #page-loader {
        height: 100px;
        line-height: 100px;
        text-align: center;
        display: none;
        color: #999999;
        font-size: 13px;
    }
    button#pdf-prev, button#pdf-next {
        height: 35px;
        /* bottom: -53px; */
        /* margin-bottom: 57px; */
        width: 106px;
    }
    #pdf-buttons {
        float: left;
        width: 100%;
    }
    #page-count-container {
        /* float: right; */
        width: 35%;
        margin-right: 33px;
    }
    div#pdf-contents {
        margin-top: -45px;
    }

    .pdfprev_class_hide{
        display: none;
    }
    .pdfprev_class_show{
        // display: block;
    }
    .pdfnext_class{
        display: none;
    }


</style>


