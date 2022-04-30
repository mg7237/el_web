

<?php
$this->title = 'PMS- My profile';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$proofsAll = Yii::$app->userdata->getAllProofs($model->tenant_id);
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<div id="content">
    <button type="button" id="sidebarCollapse" class="btn btn-info navbar-btn">
        <i class="glyphicon glyphicon-align-left"></i>
        <span></span>
    </button>
    <div class="container" style="margin-top: -42px;">
        <div class="col-md-4">
            <h4>PROFILE</h4>
        </div>
        <div class="col-md-8">
            <button id="first_button" class="btn btn-info btn-lg pull-right editbutton cust-edit" onclick="makeeditable()">Edit</button>
            <button id="first_button" class="btn btn-info btn-lg pull-right savebutton cus_hide cust-save" onclick="saveform()" >Save</button>
            <button id="first_button" class="btn btn-info btn-lg pull-right cancelbutton cus_hide cust-save" style="margin-right:5px !important;background-color: #aea1a0 !important;border-color: #aea1a0 !important;" onclick="cancelform()">Cancel</button>
        </div>
    </div>
    <hr>
    <form method="post" enctype="multipart/form-data" action="<?= \Yii::$app->getUrlManager()->createUrl('site/editpersonalinfotenant') ?>" name="personal_info" id="personal_info"> 
        <div class="container">
            <div class="container-fluid">
                <h4 id="personal_info" style="margin-bottom: 22px !important;">PERSONAL INFORMATION</h4>
            </div>
            <div class="col-md-12 bgstyle disable_sec" id="disable_sec_div" >
                <div class="col-sm-4 sidenav" style="min-height:466px;">
                    <?php
                    $dataImage = Yii::$app->userdata->getProfileImageById(Yii::$app->user->id, Yii::$app->user->identity->user_type);
                    if ($dataImage != '') {
                        $imgarr = (explode("/", $dataImage));
                        $filename = Url::home(true) . $dataImage;
                        $headers = get_headers($filename, 1);
                    } else {
                        $filename = '';
                        $headers = array();
                        $headers[0] = '';
                    }
                    //$filename = "http://localhost:8018/pms/web/dfgfh.php";
                    //print_r($headers);die;
                    //echo $filename;die;
                    ?>
                    <input type="file" name="fileupload" value="fileupload" id="fileupload" class="disabled" data-multiple-caption="{count} files selected" multiple="" style="display: none;" accept="image/*">
                    <!-- <input type="file" name="fileupload" value="fileupload" id="fileupload" class="cus_hide"> -->
                    <!--img id="van-pill-image" src="<?php echo Url::home(true); ?>images/img/Natasha.png"-->
                    <?php
                    if (isset($imgarr[2]) && $headers[0] == "HTTP/1.1 200 OK") {
                        //echo $filename;die;
                        ?>
                        <label for="fileupload" style="text-align: center;"  disabled="disabled" style="display: none;" >
                            <img src="<?php echo Url::home(true) . $dataImage; ?>" id="van-pill-image" for="fileupload" disabled  class="prflimg">
                        </label>
                    <?php } else { ?>
                        <label for="fileupload" style="text-align: center;" disabled>
                            <img src="<?php echo Url::home(true); ?>uploads/profiles/no-image.png" id="van-pill-image">
                        </label>
                    <?php } ?>
                    <input type="hidden" id="dltimg" class="form-control name1" value="1" name="dltimg">
                    <?php
                    if (isset($imgarr[2]) && $headers[0] == "HTTP/1.1 200 OK") {
                        //echo $filename;die;
                        ?><div class="dlt_img"><a  href="javascript:void(0);"><img src="<?php echo Url::home(true); ?>images/trash.png" height="50%" onclick="deleteprofile();" id="img_delete" class="cus_hide" style="margin: 15px;" ></a></div><?php } ?>
                    <label><p style="color: black; font-size: 10px; text-align: center; margin-top: -10px; margin-left: 15px;">Allow only jpeg, jpg, png files.</p></label>
                    <div class="row clearfix cust-name" style="margin-left: 0px;">
                        <div class="col-md-12">
                            <b id="bold-text">Name *</b>
                            <div class="input-group cust-inptgrp colorpicker colorpicker-element">
                                <div class="form-line">
                                    <input type="text" id="name" class="form-control name1" value="<?= Yii::$app->userdata->getFullNameById($model->tenant_id); ?>" readonly name="name">
                                    <input type="hidden" id="_csrf" class="form-control name1" value="<?= Yii::$app->request->getCsrfToken() ?>" name="_csrf">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <b id="bold-text">Email</b>
                            <div class="input-group cust-inptgrp colorpicker colorpicker-element">
                                <div class="form-line focused">
                                    <input type="text" id="email" class="form-control name1" value="<?php echo $UsersModel->login_id; ?>" name="email">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <b id="bold-text">Mobile No *</b>
                            <div class="input-group cust-inptgrp colorpicker colorpicker-element">
                                <div class="form-line focused">
                                    <input type="text" id="mobile" class="form-control name1" value="<?= $model->phone; ?>" readonly name="mobile" maxlength="15">
                                </div>
                            </div>
                        </div>
                    </div>

                    <br>
                </div>
                <div class="col-sm-8 cust-address">
                    <p style="margin-left: 15px;">Permanent Address</p>
                    <div class="col-md-12">
                        <b id="bold">Address Line 1 *</b>
                        <div class="form-group">
                            <input type="text" class="form-control" id="address1" value="<?= $model->address_line_1; ?>" readonly name="address1">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <b id="bold">Address Line 2 *</b>
                        <div class="form-group">
                            <input type="text" class="form-control" id="address2" value="<?= $model->address_line_2; ?>" readonly name="address2">
                        </div>
                    </div>
                    <div class="col-md-12 tbl_height1">
                        <b id="bold">City *</b>
                        <div class="form-group cust-frmgrp">
                            <input type="text" class="form-control" id="city_name" value="<?= $model->city_name; ?>" name="city_name" onblur="removevalidation(this.id, this.value);">
                        </div>
                    </div>
                    <?php $states = \app\models\States::find()->where(['status' => 1])->all(); ?>
                    <div class="col-md-12 tbl_height1">
                        <b id="bold">State *</b>
                        <!-- <select class="form-control" disabled id="state" name="state" onchange="getcities(this.value);"> -->
                        <!--<select class="form-control" disabled id="state" name="state" onchange="getcities(this.value);">-->
                        <select class="form-control" disabled id="state" name="state">
                            <option value="" class="">Select State</option>
                            <?php foreach ($states as $st) { ?>
                                <option id="<?php echo $st->id; ?>" <?php
                                if ($model->state == $st->code) {
                                    echo "selected";
                                }
                                ?> value="<?php echo $st->code; ?>"><?php echo $st->name; ?></option>
<?php } ?>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <b id="bold">Pincode *</b>
                        <div class="form-group">
                            <input type="text" class="form-control" id="pincode" value="<?= $model->pincode; ?>" readonly name="pincode" maxlength="15">
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </form>
</div>

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
        $("#img_delete").addClass("cus_show").removeClass("cus_hide");

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
        $("#img_delete").addClass("cus_show").removeClass("cus_hide");
        $("#dltimg").val(1);
    });

//    $('#mobile').keypress(function (event) {
//
//        if (event.which != 8 && isNaN(String.fromCharCode(event.which))) {
//            event.preventDefault(); //stop character from entering input
//        }
//
//    });
//    $('#pincode').keypress(function (event) {
//
//        if (event.which != 8 && isNaN(String.fromCharCode(event.which))) {
//            event.preventDefault(); //stop character from entering input
//        }
//
//    });

    function getcities(target)
    {
        var state = target;
        var _csrf = "<?= Yii::$app->request->getCsrfToken() ?>";
        $.ajax({
            url: 'getcities',
            type: 'post',
            data: {'state': state, '_csrf': _csrf},
            success: function (res) {

                $(".city").html('<b id="bold">City *</b><select class="form-control" id="city" name="city" style="margin-left: -3px;"><option value="" class="">Select city</option>' + res);
            },
        });
    }

    function deleteprofile()
    {
        $(".prflimg").attr("src", "<?php echo Url::home(true); ?>uploads/profiles/no-image.png");
        $("#img_delete").addClass("cus_hide").removeClass("cus_show");
        $("#dltimg").val(0);
    }

    window.onload = function () {
        var cityid = "<?php echo $model->city; ?>";
        //alert(cityid);
        var _csrf = "<?= Yii::$app->request->getCsrfToken() ?>";
        $.ajax({
            url: 'getcurrentcity',
            type: 'post',
            data: {'city': cityid, '_csrf': _csrf},
            success: function (res) {
                //alert(res);

                $(".city").html('<b id="bold">City *</b><select class="form-control" id="city" name="city" style="margin-left: -3px;"><option value="" class="">Select city</option>' + res);
            },
        });
    };

</script>
<style>
    .container
    {
        width:100%;
    }
    #content{
        width: 100%;
    }
    select
    {
        /*text-indent:-1.5px;*/
        margin-left: -4px;
    }
    input#name, input#email, input#mobile {
        color: inherit;
    }
    #personal_info .form-group
    {
        margin-bottom:0px;
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
        margin-bottom: 7px;
    }
    /*input#address1 , input#address2,select#state,select#city,input#pincode {
        background: #fafafa !important;
    }*/
    select#state {
        margin-bottom: 10px;
    }
    select#city {
        margin-bottom: 10px;
    }
    .dlt_img {
        width: 25px;
        height: 25px;
        float: right;
    }
    select.form-control,input.form-control
    {
        /*border: 1px solid #ccc;*/
        background-color:transparent !important;
    }
    img#van-pill-image {
        padding: 15px;
        width: 220px;
        margin-top: 15px;
        height: 200px;
        max-width: 100%;
        max-height: 100%;
        object-fit: fill;
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
        line-height: 4em !important;
    }

    @media screen and (max-width: 320px){
        .cust-save {
            margin-right: 0px !important;
        }
    }

    @media screen and (max-width: 767px){
        .cust-inptgrp{
            width: 100%;
        }
    }

</style>

