

<?php
$this->title = 'PMS- My profile';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

//$proofsAll = Yii::$app->userdata->getAllProofs($model->applicant_id);
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<div id="content">
    <button type="button" id="sidebarCollapse" class="btn btn-info navbar-btn">
        <i class="glyphicon glyphicon-align-left"></i>
        <span></span>
    </button>
    <div class="container" style="margin-top: -42px;">
        <div class="col-md-4">
            <h4 class="profile_heading">PROFILE</h4>
        </div>
        <div class="col-md-6 cust-flt" style="margin-left: 20px;">
            <button id="first_button" class="btn btn-info btn-lg pull-right editbutton cust-edit" onclick="makeeditable()">Edit</button>
            <button id="first_button" class="btn btn-info btn-lg pull-right savebutton cus_hide cust-save" onclick="saveform()" >Save</button>
            <button id="first_button" class="btn btn-info btn-lg pull-right cancelbutton cus_hide cust-save" style="margin-right:5px !important;background-color: #aea1a0 !important;border-color: #aea1a0 !important;" onclick="cancelform()">Cancel</button>
        </div>
    </div>
    <hr>
    <form method="post" enctype="multipart/form-data" action="<?= \Yii::$app->getUrlManager()->createUrl('site/editbankowner') ?>" name="bank_info" id="bank_info"> 
        <div class="container">

            <div class="row cust-bankdetails">
                <div class="col-md-10 col-sm-10 bgstyle disable_sec" id="disable_sec_div">     
                    <div class="container-fluid">
                        <h4 id="personal_info" style="margin-bottom: 22px !important;">MY BANK DETAILS</h4>
                    </div>           
                    <div class="form-group cust-martp col-md-4 col-sm-4">
                        <b id="bold">Account Holder Name *</b>
                        <div class="input-group cust-inpgrp colorpicker colorpicker-element">
                            <div class="form-line">
                                <input type="text" class="form-control" value="<?= $model->account_holder_name; ?>" id="account_holder_name" name="account_holder_name" readonly onblur="removevalidation(this.id, this.value);">
                                <input type="hidden" id="_csrf" class="form-control name1" value="<?= Yii::$app->request->getCsrfToken() ?>" name="_csrf">
                            </div>
                        </div>
                    </div>
                    <div class="form-group cust-frmgrop1 col-md-4 col-sm-4">
                        <b id="bold">PAN Number *</b>
                        <div class="input-group cust-inpgrp colorpicker colorpicker-element">
                            <div class="form-line focused">
                                <input type="text" class="form-control" value="<?= $model->pan_number; ?>" id="pan_number"  name="pan_number" readonly onblur="removevalidation(this.id, this.value);">
                            </div>
                        </div>
                    </div>
                    <div class="form-group cust-frmgrop1 col-md-4 col-sm-4">
                        <b id="bold">Account Number *</b>
                        <div class="input-group cust-inpgrp colorpicker colorpicker-element">
                            <div class="form-line focused">
                                <input type="text" class="form-control" value="<?= $model->bank_account_number; ?>" id="bank_account_number" name="bank_account_number" readonly onblur="removevalidation(this.id, this.value);">
                            </div>
                        </div>
                    </div>
                    <div class="form-group cust-inpgrp cust-frmgrop col-md-4 col-sm-4" style="line-height: 10px;">
                        <b id="bold">Bank Name *</b>
                        <div class="input-group cust-inpgrp colorpicker colorpicker-element">
                            <div class="form-line">
                                <input type="text" class="form-control" value="<?= $model->bank_name; ?>" id="bank_name" readonly name="bank_name" onblur="removevalidation(this.id, this.value);">
                            </div>
                        </div>
                    </div>
                    <div class="form-group cust-frmgrop col-md-4 col-sm-4" style="line-height: 10px;">
                        <b id="bold">Branch Name *</b>
                        <div class="input-group cust-inpgrp colorpicker colorpicker-element">
                            <div class="form-line focused">
                                <input type="text" class="form-control" value="<?= $model->bank_branchname; ?>" id="bank_branchname" readonly name="bank_branchname" onblur="removevalidation(this.id, this.value);">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-form-group col-md-4 col-sm-4" style="line-height: 10px;">
                        <b id="bold">IFSC Code *</b>
                        <div class="input-group cust-inpgrp colorpicker colorpicker-element">
                            <div class="form-line focused">
                                <input type="text" class="form-control" value="<?= $model->bank_ifcs; ?>" id="bank_ifcs" readonly name="bank_ifcs" onblur="checkForIfsc(this.value);">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-form-group col-md-6 col-sm-9" style="height: 250px;line-height: 10px;">

                        <b id="bold">Cancelled Cheque <span id="cancelmark" class="cus_hide tooltip_s" data-toggle="tooltip" title="Name on the cancelled cheque should match your name" style="width: 530px;float: right;">?</span></b>
                        <div class="well" id="bank_well" style="padding-right: 5px;padding-left: 7px;padding-top: 5px;margin-bottom: 5px;margin-top: 5px;padding-bottom: 5px;">

                            <div class="box">

                                <input type="file" name="fileupload" id="browse" class="inputfile inputfile-4 cus_hide" data-multiple-caption="{count} files selected" multiple="" style="display: none;">
                                <label for="browse" style="text-align: center;"  disabled="disabled" style="display: none;" > 
                                    <?php
                                    if (!empty($model->cancelled_check)) {

                                        if ($model->cancelled_check != '') {
                                            $imgarr = (explode("/", $model->cancelled_check));
                                            $filename = Url::home(true) . $model->cancelled_check;
                                            $headers = get_headers($filename, 1);
                                        } else {
                                            $filename = '';
                                            $headers = array();
                                            $headers[0] = '';
                                        }
                                        ?>

                                        <?php if (isset($imgarr[2]) && $headers[0] == "HTTP/1.1 200 OK") { ?>										
                                            <img  id="preview_image" src="<?= "../" . $model->cancelled_check ?>" alt="..." class="preview_image_bank bank_plus" id="preview_image">
                                        <?php } else { ?> 
                                            <img src="<?php echo Url::home(true); ?>images/plus1.png" class="preview_image_bank bank_plus" id="preview_image">
                                        <?php } ?>    
                                    <?php } else { ?>
                                        <img src="<?php echo Url::home(true); ?>images/plus1.png"  class="preview_image_bank bank_plus" id="preview_image">
                                    <?php } ?>
                                </label>
                                <?= !empty($model->cancelled_check) ? '<a class="removeCancelledCheck" attr-id="' . $model->owner_id . '"></a>' : '' ?>
                        <!--label for="file-5" style="text-align: center;"><img src="https://image.flaticon.com/icons/svg/59/59565.svg" style="height: 25%;width: 25%;" id="preview_image"/></label-->
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form></div>
<script>
    function makeeditable()
    {
        document.getElementById("account_holder_name").readOnly = false;
        document.getElementById("pan_number").readOnly = false;
        document.getElementById("bank_account_number").readOnly = false;
        document.getElementById("bank_name").readOnly = false;
        document.getElementById("bank_branchname").readOnly = false;
        document.getElementById("bank_ifcs").readOnly = false;
        $("#account_holder_name").addClass("edit_input_bg");
        $("#pan_number").addClass("edit_input_bg");
        $("#bank_account_number").addClass("edit_input_bg");
        $("#bank_name").addClass("edit_input_bg");
        $("#bank_branchname").addClass("edit_input_bg");
        $("#bank_ifcs").addClass("edit_input_bg");
        // $("#browse").addClass("cus_show").removeClass("cus_hide"); 
        $("#cancelmark").addClass("cus_show").removeClass("cus_hide");
        $("#disable_sec_div").removeClass("disable_sec");
        jQuery(".editbutton").hide();

        $(".savebutton").addClass("cus_show").removeClass("cus_hide");
        $(".cancelbutton").addClass("cus_show").removeClass("cus_hide");

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
        var account_holder_name = $("#account_holder_name").val();
        var pan_number = $("#pan_number").val();
        var bank_account_number = $("#bank_account_number").val();
        var bank_name = $("#bank_name").val();
        var bank_branchname = $("#bank_branchname").val();
        var bank_ifcs = $("#bank_ifcs").val();
        if (account_holder_name == '')
        {
            $("#account_holder_name").addClass('error');
        } else if (pan_number == '')
        {
            $("#pan_number").addClass('error');
        } else if (bank_account_number == '')
        {
            $("#bank_account_number").addClass('error');
        } else if (bank_name == '')
        {
            $("#bank_name").addClass('error');
        } else if (bank_branchname == '')
        {
            $("#bank_branchname").addClass('error');
        } else if (bank_ifcs == '')
        {
            $("#bank_ifcs").addClass('error');
        } else {
            var ifsc = $('#bank_ifcs').val();
            var _csrf = "<?= Yii::$app->request->getCsrfToken() ?>";
            $.ajax({
                url: 'checkifsc',
                type: 'post',
                data: {'ifsc': ifsc, '_csrf': _csrf},
                success: function (res) {
                    //alert(res);
                    if (res == 'error') {
                        $("#bank_ifcs").focus();
                        alert("Please enter a valid IFSC code.");
                        //e.preventDefault();
                    } else {
                        $("#bank_info").submit();
                    }
                },
            });
        }
    }
    $('#bank_account_number').keypress(function (event) {

        if (event.which != 8 && isNaN(String.fromCharCode(event.which))) {
            event.preventDefault(); //stop character from entering input
        }

    });

    function checkForIfsc(target) {
        if (target != '')
        {
            $("#bank_ifcs").removeClass('error');
        }
        //alert("fdsfs");
        // e.preventDefault();
        var ifsc = $('#bank_ifcs').val();
        var _csrf = "<?= Yii::$app->request->getCsrfToken() ?>";
        $.ajax({
            url: 'checkifsc',
            type: 'post',
            data: {'ifsc': ifsc, '_csrf': _csrf},
            success: function (res) {
                //alert(res);
                if (res == 'error') {
                    $("#bank_ifcs").focus();
                    //e.preventDefault();
                }
            },
            error: function (a, b, c) {
                alert("Some error occured at server, please try again later.");
            }
        });
    }
    function removevalidation(id, val)
    {

        if (val != '')
        {
            $("#" + id).removeClass('error');
        }

    }

</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
        });
    });
</script>	
<style>
    .disable_sec{
        z-index: -10000;
    }
    .cus_hide{
        display: none !important;
    }
    .cus_show{
        display: block !important;
    }
    input#account_holder_name ,input#pan_number,input#bank_account_number,input#bank_name,input#bank_branchname,input#bank_ifcs{
        background: #fafafa;    
        margin-bottom: 10px;
    }
    div#bank_well {
        margin-top: 19px;
        height: 190px;
    }
    .preview_image_bank {
        height: 30%;
        width: 100%;
        align-items: right;
        // margin-left: 125px;


    }
    .preview_image_bank bank_plus{

    }
    .preview_image_bank {
        height: 179px;
        width: 100%;
        align-items: right;
    }
    .tooltip_s {
        width: 525px;
        float: right;
        /* margin-top: 18px; */
    }

    @supports (-moz-appearance:none) {
        .preview_image_bank {
            height: 179px;
            width: 645px;
            align-items: right;
        }
    }
    @media screen and (min-width: 768px) and (max-width: 1198px){
        .cust-save{
            margin-right: 0 !important;
        }
        .well{
            width: 100% !important;
        }
    }
    @media screen and (max-width: 767px){

        .well{
            width: 100% !important;
        }
        .cust-inpgrp{
            width: 100% !important;
        }
        .cust-save{
            margin-right: -72px;
        }
        .cust-flt{
            margin-left: 50px !important;
        }
        #content{
            width: 100%;
        }
        .cust-frmgrop{
            margin-bottom: 0px !important;
        }
        .cust-frmgrop1 {
            margin-top: -30px;
        }
        .cust-inpgrp{
            margin-top: -15px;
            width: 100%;
        }
        .cust-martp{
            margin-top: -25px;
        }
    }
    @media screen and (max-width: 375px){
        button#first_button{
            width: 72px !important;
        }
        .preview_image_bank{
            height: 145px !important;
        }
        div#bank_well{
            height: 154px !important;
        }
    }

</style>






