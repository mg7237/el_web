

<?php
$this->title = 'PMS- My profile';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

//$proofsAll = Yii::$app->userdata->getAllProofs($model->applicant_id);
//echo "<pre>";print_r($model);echo "</pre>";die;
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<div id="content">
    <button type="button" id="sidebarCollapse" class="btn btn-info navbar-btn">
        <i class="glyphicon glyphicon-align-left"></i>
        <span></span>
    </button>
    <div class="container" style="margin-top: -42px;">
        <div class="col-md-6">
            <h4>PROFILE</h4>
        </div>
        <div class="col-md-6" style="margin-left: 20px;">
            <!--button id="first_button" class="btn btn-info btn-lg pull-right editbutton" onclick="makeeditable()">Edit</button>
            <button id="first_button" class="btn btn-info btn-lg pull-right savebutton cus_hide" onclick="saveform()" >Save</button>
            <button id="first_button" class="btn btn-info btn-lg pull-right cancelbutton cus_hide" style="margin-right:5px !important;background-color: #aea1a0 !important;border-color: #aea1a0 !important;" onclick="cancelform()">Cancel</button-->
        </div>
    </div>
    <hr>
    <form method="post" enctype="multipart/form-data" action="<?= \Yii::$app->getUrlManager()->createUrl('site/editpersonalinfo') ?>" name="personal_info" id="personal_info"> 
        <div class="container">
            <div class="container-fluid">
                <h4 id="personal_info" style="margin-bottom: 22px !important;">SUPPORT PERSON DETAILS</h4>
            </div>
            <div class="col-md-12 bgstyle disable_sec" id="disable_sec_div" >
                <div class="col-sm-3 sidenav">
                    <?php
                    /* $dataImage = Yii::$app->userdata->getProfileImageById(Yii::$app->user->id, Yii::$app->user->identity->user_type);
                      if ($dataImage != '') {
                      $imgarr = (explode("/", $dataImage));
                      $filename = Url::home(true) . $dataImage;
                      $headers = get_headers($filename, 1);
                      } else {
                      $filename = '';
                      $headers = array();
                      $headers[0] = '';
                      } */
                    //$filename = "http://localhost:8018/pms/web/dfgfh.php";
//print_r($headers);die;
                    //echo $filename;die;
                    ?>


                    <label for="fileupload" style="text-align: center;" disabled>
                        <img src="<?php echo Url::home(true); ?>uploads/profiles/no-image.png" id="van-pill-image">
                    </label>

                    <br>
                </div>
                <div class="col-sm-9 cust-name">
                    <div class="col-md-12 col-sm-12">
                        <b id="bold">Name</b>
                        <div class="form-group" style="margin-bottom: 0px;">
                            <input type="text" class="form-control" id="s_name" value="<?= Yii::$app->userdata->getFullNameById($model->operation_id) ?>" readonly name="s_name">
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12">
                        <b id="bold">Email</b>
                        <div class="form-group" style="margin-bottom: 0px;">
                            <input type="text" class="form-control" id="s_email" value="<?= Yii::$app->userdata->getLoginIdById($model->operation_id) ?>" readonly name="s_email">
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12">
                        <b id="bold">Number</b>
                        <!-- <select class="form-control" disabled id="state" name="state" onchange="getcities(this.value);"> -->
                        <div class="form-group" style="margin-bottom: 0px;">
                            <input type="text" class="form-control" id="s_number" value="<?= Yii::$app->userdata->getPhoneNumberById($model->operation_id, '7') ?>" readonly name="s_number">
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>
<script>
    $(document).ready(function () {
        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
        });
    });
</script>
<script>
    function makeeditable()
    {
        document.getElementById("address1").readOnly = false;
        document.getElementById("address2").readOnly = false;
        document.getElementById("pincode").readOnly = false;
        document.getElementById("name").readOnly = false;
        document.getElementById("mobile").readOnly = false;
        $("#state").prop("disabled", false);
        $("#city").prop("disabled", false);
        $("#address1").addClass("edit_input_bg");
        $("#address2").addClass("edit_input_bg");
        $("#pincode").addClass("edit_input_bg");
        $("#state").addClass("edit_input_bg");
        $("#city").addClass("edit_input_bg");
        //document.getElementById("email").readOnly = false;
        // $("#fileupload").addClass("cus_show").removeClass("cus_hide");
        $("#disable_sec_div").removeClass("disable_sec");
        jQuery(".editbutton").hide();

        $(".savebutton").addClass("cus_show").removeClass("cus_hide");
        $(".cancelbutton").addClass("cus_show").removeClass("cus_hide");

    }


    function saveform()
    {
        var address1 = $("#address1").val();
        var address2 = $("#address2").val();
        var state = $("#state").val();
        var city = $("#city").val();
//alert(city);
        var pincode = $("#pincode").val();
        var name = $("#name").val();
        var mobile = $("#mobile").val();
        if (address1 == '')
        {
            $("#address1").addClass('error');
        } else if (address2 == '') {
            $("#address2").addClass('error');
        } else if (state == '') {
            $("#state").addClass('error1');
        } else if (city == '')
        {
            $("#city").addClass('error1');
        } else if (pincode == '')
        {
            $("#pincode").addClass('error');
        } else if (name == '')
        {
            $("#name").addClass('error');
        } else if (mobile == '')
        {
            $("#mobile").addClass('error');
        } else {
            var n = mobile.length;
            if (n >= 10)
            {
                $("#personal_info").submit();
            } else
            {
                alert("Please provide a valid mobile number");
            }
        }
        //location.reload();	
    }

    function cancelform()
    {
        location.reload();
    }

    function readURL(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#van-pill-image').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#fileupload").change(function () {
        readURL(this);
    });

    $('#mobile').keypress(function (event) {

        if (event.which != 8 && isNaN(String.fromCharCode(event.which))) {
            event.preventDefault(); //stop character from entering input
        }

    });
    $('#pincode').keypress(function (event) {

        if (event.which != 8 && isNaN(String.fromCharCode(event.which))) {
            event.preventDefault(); //stop character from entering input
        }

    });

    function getcities(target)
    {
        var state = target;
        var _csrf = "<?= Yii::$app->request->getCsrfToken() ?>";
        $.ajax({
            url: '<?php echo Url::home(true); ?>users/getcities',
            type: 'post',
            data: {'state': state, '_csrf': _csrf},
            success: function (res) {

                $(".city").html('<b id="bold">City *</b><select class="form-control" id="city" name="city"><option value="" class="">Select city</option>' + res);
            },
        });
    }

    window.onload = function () {
        var cityid = "<?php echo $model->city; ?>";
        //alert(cityid);
        var _csrf = "<?= Yii::$app->request->getCsrfToken() ?>";
        $.ajax({
            url: '<?php echo Url::home(true); ?>site/getcurrentcity',
            type: 'post',
            data: {'city': cityid, '_csrf': _csrf},
            success: function (res) {
                //alert(res);

                $(".city").html('<b id="bold">City *</b><select class="form-control" id="city" name="city"><option value="" class="">Select city</option>' + res);
            },
        });
    };

</script>
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
    .container
    {
        width:100%;
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
    .bgstyle {
        background-color: #fafafa !important;
        background-color: var(--white);
        margin-left: 0px !important;
        /*padding-top: 15px;*/
    }
    .input-group.colorpicker.colorpicker-element {
        margin-top: 10px;
        margin-bottom: 10px;
    }
    input#address1 , input#address2,select#state,select#city,input#pincode {
        background: #fafafa;
    }
    select#state {
        margin-bottom: 10px;
    }
    select#city {
        margin-bottom: 10px;
    }
    .form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
        background-color: #fafafa !important;
        opacity: 1;
    }
    .sidenav{
        background-color: #59abe3;
        /* margin-top: 36px; */
        height: 210px;
        width: 210px;
        /* margin-left: 15px; 
        padding-left: 32px;
        padding-top: 13px;*/

    }
    img#van-pill-image{
        width: 180px !important;
        height: 180px !important;
        /*margin-left: 5px;
        margin-top: 18px;*/
    }
    .sidenav {
        background-color: #59abe3;
        /*margin-top: -15px;
        margin-left: -15px;*/
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
        line-height: 3em !important;
    }

    @media screen and (max-width: 767px){
        .sidenav{
            width: 100% !important;
        }
    }

</style>


