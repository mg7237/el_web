

<?php
$this->title = 'PMS- My profile';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

//$proofsAll = Yii::$app->userdata->getAllProofs($model->applicant_id);
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> 
<?php //echo "<pre>";print_r($tenantProfile);echo "</pre>";die;?>

<div id="content">
    <button type="button" id="sidebarCollapse" class="btn btn-info navbar-btn">
        <i class="glyphicon glyphicon-align-left"></i>
        <span></span>
    </button>
    <div class="container" style="margin-top: -42px;">
        <div class="col-md-12">
            <h4 ><?= strtoupper(Html::encode(Yii::$app->userdata->getPropertyNameById($_GET['prop_id']))) ?> - TENANT DETAILS</h4>
        </div>
        <!--div class="col-md-6">
            <button id="first_button" class="btn btn-info btn-lg pull-right editbutton" onclick="makeeditable()">Edit</button>
            <button id="first_button" class="btn btn-info btn-lg pull-right savebutton cus_hide" onclick="saveform()" >Save</button>
            <button id="first_button" class="btn btn-info btn-lg pull-right cancelbutton cus_hide" style="margin-right:5px !important;background-color: #aea1a0 !important;border-color: #aea1a0 !important;" onclick="cancelform()">Cancel</button>
        </div-->
    </div>
    <hr>
    <form method="post" enctype="multipart/form-data" action="<?= \Yii::$app->getUrlManager()->createUrl('site/editemployeedetail') ?>" name="employ_info" id="employ_info"> 
        <div class="container">
            <!-- <h4 class="col-md-4" style="margin-bottom: 22px !important;" id="personal_info">MY EMPLOYMENT DATA <span id="em_mark" class="cus_hide tooltip_span" data-toggle="tooltip" title="Enter Self employed if you are not working for any organization.">?</span></h4>				             -->
            <div class="container-fluid" style="margin-left: -15px;"> 
                <h4 class="col-md-4" style="margin-bottom: 22px !important;" id="personal_info">EMPLOYMENT INFORMATION <span id="em_mark" class="cus_hide tooltip_span" data-toggle="tooltip" title="Enter Self employed if you are not working for any organization.">?</span></h4>
            </div>
            <div class="col-md-10 bgstyle " >
                <div class="col-md-8">
                    <div class="col-md-6 emp_details">
                        <b id="bold">Employer Name</b>
                        <div class="input-group cst-inpgrp colorpicker colorpicker-element">
                            <div class="form-line">
                                <input type="text" class="form-control" value="<?= ($tenantProfile) ? $tenantProfile->employer_name : ""; ?>" readonly id="employer_name" name="employer_name">
                                <input type="hidden" id="_csrf" class="form-control name1" value="<?= Yii::$app->request->getCsrfToken() ?>" name="_csrf">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 emp_details">
                        <b id="bold">Employee Id</b>
                        <div class="input-group cst-inpgrp colorpicker colorpicker-element">
                            <div class="form-line focused">
                                <input type="text" class="form-control" value="<?= ($tenantProfile) ? $tenantProfile->employee_id : ""; ?>" readonly id="employee_id" name="employee_id">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 emp_details">
                        <b id="bold">Employment Start Date</b>
                        <div class="input-group cst-inpgrp colorpicker colorpicker-element">
                            <div class="form-line focused">
                                <input type="text" class="form-control datepicker1" value="<?= ($tenantProfile) ? $tenantProfile->employment_start_date : ""; ?>" disabled id="employment_start_date" name="employment_start_date">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 emp_details">
                        <b id="bold">Employment Email</b>
                        <div class="input-group cst-inpgrp colorpicker colorpicker-element">
                            <div class="form-line focused">
                                <input type="text" class="form-control" value="<?= ($tenantProfile) ? $tenantProfile->employment_email : ""; ?>" readonly id="employment_email" name="employment_email" onblur="checkEmail(this.value);">
                            </div>
                        </div>
                    </div>
                </div>
                <?php //echo $model->employmnet_proof_url;die;?>
                <div class="col-md-4">
                    <div class="col-md-12 cust-empproof">
                        <p class="">Employment Proof</p>
                        <div class="well " id="bank_well">
                            <div class="box" style="margin-top: -45px;width: 225px;height: 280px;background-color: #e9e9e9; */padding:;padding: 0px !important;">
                                <img  id="preview_image" src="<?= ($tenantProfile) ? Url::home(true) . $tenantProfile->employment_proof_url : ""; ?>" alt="..."  style="width: 225px;height: 280px;">
                            </div>

                        </div>
                    </div>
                </div>
            </div>
    </form> </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
        });
    });
</script>

<style>
    #content{
        width: 100%;
    }
    .cst-inpgrp{
        width: 100%;
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
    /* .emp_details{
        margin-top: 30px;
    } */
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


