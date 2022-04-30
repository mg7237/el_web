

<?php
$this->title = 'PMS- My profile';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$proofsAll = Yii::$app->userdata->getAllProofs($model->advisor_id);
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
        <!--div class="col-md-6">
            <button id="first_button" class="btn btn-info btn-lg pull-right">Edit</button>
        </div-->
    </div>
    <hr>
    <div class="container">
        <div class="container-fluid">
            <h4 id="personal_info" style="margin-bottom: 22px !important;">Agreement Contract Details</h4>
        </div>
        <div class="row bgstyle cust-bgstyle">
            <div class="col-md-12 col-sm-12" style="padding-left: 0px !important;">
                <div class="col-md-6 col-sm-6">
                    <div class="col-md-12">
                        <b id="bold">Start Date</b>
                        <div class="form-group" style="margin-top: 15px;">
                            <!--<div class="form-line">-->
                            <input type="text" class="form-control cust-group" value="<?= date('d-M-Y', strtotime($advisorAgreements->start_date)); ?>" readonly>
                            <!--</div>-->
                        </div>
                    </div>
                    <div class="col-md-12">
                        <b id="bold">End Date</b>
                        <div class="form-group" style="margin-top: 15px;">
                            <!--<div class="form-line focused">-->
                            <input type="text" class="form-control cust-group" value="<?= date('d-M-Y', strtotime($advisorAgreements->end_date)); ?>" readonly>
                            <!--</div>-->
                        </div>
                    </div>

                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="col-md-12 col-sm-12">
                        <p class="col-md-12 col-sm-12 cust-agreement">Agreement</p>
                        <div class="well " id="bank_well">

                            <div class="box"style="height: 290px;">
                                <?php if (isset($advisorAgreements->agreement_doc) && $advisorAgreements->agreement_doc != '') {
                                    ?>
                                    <iframe  src="http://docs.google.com/gview?url=<?= Url::home(true) . $advisorAgreements->agreement_doc ?>&embedded=true" class="abc" width="100%" height="250px" frameborder="0" allowfullscreen></iframe>						
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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

    function checkForIfsc() {
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


</script>
<style>
    #content{
        width: 100%;
    }

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
    select.form-control,input.form-control
    {
        /*border: 1px solid #ccc;*/
        background-color:transparent !important;
    }
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
        margin-top: 30px;
        height: 290px;
    }
    .preview_image_bank {
        height: 30%;
        width: 100%;
        align-items: right;
        // margin-left: 125px;


    }
    .preview_image_bank bank_plus{

    }

    @media screen and (max-width: 767px){
        .cust-bgstyle{
            margin-left: -15px !important;
        }
        .cust-agreement{
            margin-left: -15px;
        }
        .cust-group{
            margin: -15px 0;
        }
    }
    @media screen and (max-width: 990px){
        .well {
            width: 100% !important;
            padding: 0;
        }
    }
</style>






