

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
        <div class="col-md-6">
            <button id="first_button" class="btn btn-info btn-lg pull-right editbutton cust-edit" onclick="makeeditable()">Edit</button>
            <button id="first_button" class="btn btn-info btn-lg pull-right savebutton cus_hide cust-save" onclick="saveform()" >Save</button>
            <button id="first_button" class="btn btn-info btn-lg pull-right cancelbutton cus_hide cust-save" style="margin-right:5px !important;background-color: #aea1a0 !important;border-color: #aea1a0 !important;" onclick="cancelform()">Cancel</button>
        </div>
    </div>
    <hr>
    <form method="post" enctype="multipart/form-data" action="<?= \Yii::$app->getUrlManager()->createUrl('site/editemergencyowner') ?>" name="em_info" id="em_info"> 
        <div class="container">
            <div class="row cust-emergency">
                <div class="col-md-10 col-sm-10 bgstyle disable_sec" id="disable_sec_div1">
                    <div class="container-fluid">
                        <h4 class="col-md-12 col-sm-12" style="margin-bottom: 22px !important;margin-left: -15px;" id="personal_info">MY EMERGENCY CONTACT DETAILS</h4>				
                    </div>
                    <div class="col-md-4 col-sm-4" style="line-height: 1;">
                        <b id="bold">Name *</b>
                        <div class="input-group cust-inpgroup colorpicker colorpicker-element">
                            <div class="form-line">
                                <input type="text" class="form-control" value="<?= $model->emer_contact; ?>" name="em_name" id="em_name" readonly>
                                <input type="hidden" id="_csrf" class="form-control name1" value="<?= Yii::$app->request->getCsrfToken() ?>" name="_csrf" onblur="removevalidation(this.id, this.value);">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4" style="line-height: 1;">
                        <b id="bold">Email *</b>
                        <div class="input-group cust-inpgroup colorpicker colorpicker-element">
                            <div class="form-line focused">
                                <input type="text" class="form-control" value="<?= $model->emergency_contact_email; ?>" name="em_email" id="em_email" readonly onblur="checkEmail(this.value);">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4" style="line-height: 1;">
                        <b id="bold">Mobile No *</b>
                        <div class="input-group cust-inpgroup colorpicker colorpicker-element">
                            <div class="form-line focused">
                                <input type="text" class="form-control" value="<?= $model->emergency_contact_number; ?>" name="em_mobile" id="em_mobile" readonly maxlength="15" onblur="removevalidation(this.id, this.value);">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="idtype" class="form-control name1" name="idtype">
    </form> </div>

<script>
    function makeeditable()
    {
        document.getElementById("em_name").readOnly = false;
        document.getElementById("em_email").readOnly = false;
        document.getElementById("em_mobile").readOnly = false;
        $("#em_name").addClass("edit_input_bg");
        $("#em_email").addClass("edit_input_bg");
        $("#em_mobile").addClass("edit_input_bg");
        $("#proof_div").addClass("cus_show").removeClass("cus_hide");
        $("#browse").addClass("cus_show").removeClass("cus_hide");
        $("#id_proof").addClass("cus_show").removeClass("cus_hide");
        jQuery(".editbutton").hide();
        $("#disable_sec_div").removeClass("disable_sec");
        $(".savebutton").addClass("cus_show").removeClass("cus_hide");
        $(".cancelbutton").addClass("cus_show").removeClass("cus_hide");
        $("#disable_sec_div1").removeClass("disable_sec");

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
//    $('#em_mobile').keypress(function (event) {
//
//        if (event.which != 8 && isNaN(String.fromCharCode(event.which))) {
//            event.preventDefault(); //stop character from entering input
//        }
//
//    });
    function checkEmail(str)
    {
        if (str != '')
        {
            $("#em_email").removeClass('error');
        }
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if (!re.test(str))
        {
            alert("Please enter a valid email address");
            $("#em_email").val('');
        }
    }
    function cancelform()
    {
        location.reload();
    }

    function saveform()
    {
        var em_name = $("#em_name").val();
        var em_email = $("#em_email").val();
        var em_mobile = $("#em_mobile").val();
        if (em_name == '')
        {
            $("#em_name").addClass('error');
        } else if (em_email == '') {
            $("#em_email").addClass('error');
        } else if (em_mobile == '') {
            $("#em_mobile").addClass('error');
        } else {
            var sessiondata = sessionStorage.getItem("set_id1");
            if (sessiondata) {
                deletedata1();
            }
            $("#em_info").submit();
        }
    }

    window.onload = function () {

        $('#fileupload').addClass('cus_hide');
        jQuery("#fileupload").hide();
    }
</script>
<script>
    var box_identity;

    function checkForIfsc(e, ele) {
        e.preventDefault();
        var ifsc = $('#applicantprofile-bank_ifcs').val();
        $.ajax({
            url: 'checkifsc',
            type: 'post',
            data: {'ifsc': ifsc},
            success: function (res) {
                if (res == 'error') {
                    $('#applicantprofile-bank_ifcs').css('border', '1px solid #a94442');
                    $('#ifsc-error-box').css('display', 'block');
                    $(document).scrollTop($('#applicantprofile-bank_ifcs').position().top + 35);
                    return false;
                } else {
                    $('#applicantprofile-bank_ifcs').css('border', '1px solid #c7c7c7');
                    $('#ifsc-error-box').css('display', 'none');
                    $('#w0').submit();
                }
            },
            error: function (a, b, c) {
                alert("Some error occured at server, please try again later.");
            }
        });
    }

//    $('[data-toggle="popover"]').popover({
//        container: 'body'
//    });

// function disableFirst(){
// 	var address_proof_text1 = $('input[name="address_proof_text"]').val();
// 	var identity_proof_text1 = $('input[name="identity_proof_text"]').val();
// 	$('.IDproof option').removeAttr('disabled');
// 	if(address_proof_text1!=''){
// 		$('.IDproof option[value="'+address_proof_text1+'"]').attr('disabled','true');
// 	}
// 	if(identity_proof_text!=''){
// 		$('.IDproof option[value="'+identity_proof_text1+'"]').disabled('	','true');
// 	}
// }

    $(document).on('change', '.IDproof', function () {

        var count = $('#IDproof .box').length;
        $('.identity_box').hide();
        $('#IDproof').append('<div class=" identity_file_' + count + ' identity_box box col-md-12 col-sm-12"><input type="file" attr-class="identity_file_' + count + '" name="address_proof[' + $(this).val() + '][]" attr-type="' + $('.IDproof option:selected').text() + '" class="identity_files" id="fileupload"  accept="image/*">');
        var typeval = $("#id_proof").val();
        var type_val = $("#idtype").val();
        if (type_val == '')
        {
            $("#idtype").val(typeval);
        } else
        {
            var type = type_val + "," + typeval;
            $("#idtype").val(type);
        }

    });

    $(document).on('change', '.identity_files', function (e) {
        readurlIdentity(this);
    });


    $(document).on('change', '.EMERProof', function () {
        var count = $('#EMERProof .box').length;
        $('.emergency_box').hide();
        $('#EMERProof').append('<div class=" emergency_file_' + count + ' emergency_box box col-md-12 col-sm-12"><input type="file" attr-class="emergency_file_' + count + '" name="emergency_proof[' + $(this).val() + '][]" attr-type="' + $('.EMERProof option:selected').text() + '" class="emergency_files" accept="image/*">');

    });

    $(document).on('change', '.canceled_check_files', function (e) {
        readurlIdentity2(this);

    });

    $(document).on('change', '.employment_proof_files', function (e) {
        readurlIdentity3(this);

    });

    $(document).on('change', '.emergency_files', function (e) {
        readurlIdentity1(this);

    });

    $(function () {
        $('#applicantprofile-employment_start_date').datepicker({
            startDate: '01/01/1970',
            changeMonth: true,
            changeYear: true,
            beforeShowDay: onlyPastdays
        }
        );
    });

    function onlyPastdays(date) {
        var day = date.getDay();
        var today = new Date();
        today.setDate(today.getDate());
        return [(date < today), ''];
    }

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
                        $('.identity_image_preview').append('<div class="col-md-3 col-sm-6"><img class= "preview" alt=""src="' + e.target.result + '"><a class="removeLocal" id="' + attrclass + '"></a><p class="identityp">' + $('.IDproof option:selected').text() + '</p></div>');
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

    function readurlIdentity1(input) {
        if (input.files && input.files[0]) {
            var attrclass = $(input).attr('attr-class');
            var reader = new FileReader();

            reader.onload = function (e) {
                if (e.target.result != '') {
                    var target_image = e.target.result.substr(0, 100);
                    console.log("hello" + target_image);
                    var image_check = check_image(e.target.result);
                    if (image_check == 'image') {
                        $('.emergency_image_preview').append('<div class="col-md-3 col-sm-6"><img class= "preview" alt=""src="' + e.target.result + '"><a class="removeLocal" id="' + attrclass + '"></a><p class="emergencyp">' + $('.EMERProof option:selected').text() + '</p></div>');
                        // $('.EMERProof').val('');
                        $('.EMERProof').prop('selectedIndex', 0);
                        $('.emergency_box').hide();
                    } else {
                        alert('Only images are allowed');
                        $('.EMERProof').val('');
                        $('#EMERProof div.' + attrclass).remove();
                        $('a#' + attrclass).closest('.col-md-6').remove();
                    }
                } else {
                    alert('Only images are allowed');
                    $('.EMERProof').val('');
                    $('#EMERProof div.' + attrclass).remove();
                    $('a#' + attrclass).closest('.col-md-6').remove();
                }
            }


            reader.readAsDataURL(input.files[0]);
        }
    }

    function readurlIdentity2(input) {
        if (input.files && input.files[0]) {
            var attrclass = $(input).attr('attr-class');
            var reader = new FileReader();

            reader.onload = function (e) {
                if (e.target.result != '') {
                    var target_image = e.target.result.substr(0, 100);
                    console.log("hello" + target_image);
                    var image_check = check_image(e.target.result);
                    if (image_check == 'image') {
                        //$('.canceled_cheque_image_preview').html('<div class="col-md-6 col-sm-6"><img class= "preview" alt=""src="' + e.target.result + '"><a class="removeLocal" id="' + attrclass + '"></a><p class="emergencyp">' + $('.EMERProof option:selected').text() + '</p></div>');
                        $('.canceled_cheque_image_preview').html('<div class="col-md-3 col-sm-6"><img class= "preview" alt=""src="' + e.target.result + '"><a class="removeLocal" id="' + attrclass + '"></a><p class="identityp">Cancelled Cheque</p></div>');
                    } else {
                        alert('Only images are allowed');
                    }
                } else {
                    alert('Only images are allowed');
                }
            }


            reader.readAsDataURL(input.files[0]);
        }
    }

    function readurlIdentity3(input) {
        if (input.files && input.files[0]) {
            var attrclass = $(input).attr('attr-class');
            var reader = new FileReader();

            reader.onload = function (e) {
                if (e.target.result != '') {
                    var target_image = e.target.result.substr(0, 100);
                    console.log("hello" + target_image);
                    var image_check = check_image(e.target.result);
                    if (image_check == 'image') {
                        //$('.canceled_cheque_image_preview').html('<div class="col-md-6 col-sm-6"><img class= "preview" alt=""src="' + e.target.result + '"><a class="removeLocal" id="' + attrclass + '"></a><p class="emergencyp">' + $('.EMERProof option:selected').text() + '</p></div>');
                        $('.employment_image_preview').html('<div class="col-md-3 col-sm-6"><img class= "preview" alt=""src="' + e.target.result + '"><a class="removeLocal" id="' + attrclass + '"></a><p class="identityp">Employment Proof</p></div>');
                    } else {
                        alert('Only images are allowed');
                    }
                } else {
                    alert('Only images are allowed');
                }
            }


            reader.readAsDataURL(input.files[0]);
        }
    }

// $(document).on('change','#IDProof1',function(){
// 	$('input[name="address_proof_text"]').val($('.IDproof').val());
// 	$('.addressp').text($('.IDproof option:selected').text());
// 	readURL1(this);
// })

// $(document).on('change','#IDProof2',function(){
// 	$('input[name="identity_proof_text"]').val($('.IDproof').val());
// 	$('.identityp').text($('.IDproof option:selected').text());	
// 	readURL2(this);
// })

// $(document).on('click','#passport',function(){
// 	box_identity=$('.pcard.box');
// 	$(this).addClass('selectedImage');
// 	$('#dl').removeClass('selectedImage');
// });

// $(document).on('click','#dl',function(){
// 	box_identity=$('.dl.box');
// 	$(this).addClass('selectedImage');
// 	$('#passport').removeClass('selectedImage');
// });

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

                    $('#blah-p').attr('src', e.target.result);
                } else {
                    alert("only images are allowed");
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#applicantprofile-profile_image").change(function () {
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

    // $("#IDProof2").change(function () {
    //     readURL1(this);
    // });
    function readURL2(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#dl').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    // $("#IDproof").change(function () {
    //     readURL2(this);
    // });
    function readURL3(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#dle').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#applicantprofile-emer_contact_indentity").change(function () {
        readURL3(this);
    });
    function readURL4(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#passporte').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#applicantprofile-emer_contact_address").change(function () {
        readURL4(this);
    });
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

    @media screen and (max-width: 767px){
        .cust-edit{
            margin-right: -28px;
        }
        .cust-save{
            margin-right: -28px;
        }
        .cust-inpgroup{
            width: 100%;
        }
    }

    #content{
        width: 100%;
    }
    .cus_hide{
        display: none !important;
    }
    .cus_show{
        display: block !important;
    }
    .mt_name {
        margin: 15px;
    }
    .bgstyle {
        background-color: #fafafa !important;
        background-color: var(--white);
        margin-left: 15px;
        padding-top: 15px;
    }
    .input-group.colorpicker.colorpicker-element {
        margin-top: 10px;
        margin-bottom: 10px;
    }

    input#em_name, input#em_email,input#em_mobile {
        background: #fafafa;
        margin-bottom: 10px;
    }
    .emp_details{
        margin-top: 30px;
    }
    .disable_sec{
        z-index: -10000;
    }
    img.selectedImage {
        border: 2px solid #347c17;
        margin-top: 4px;
    }
    .removeLocal {
        position: absolute;
        padding-top: 15%;
        bottom: 57px;
        vertical-align: middle;
        height: 80%;
        width: 90%;
        left: 30px;
        right: 0px;
        text-align: center;
        background-color: rgba(0,0,0,0.6);
        top: -2px;
        display: none;
        cursor: pointer;
        text-decoration: none !important;
        margin-top: 16px;
    }
    img#van-pill-image {
        padding: 0px !important;
        width: 100%;
        margin-top: 15px;
    }
    .well {
        border: 1px solid #fafafa;
        box-shadow: 0 1px 1px #fafafa;
        background: #fafafa;
        padding: 0px;
    }
    .row.identity_image_preview div:hover > a {
        display: block;
    }
    .removeLocal::before {
        content: "\f014";
        font-family: FontAwesome;
        font-size: 40px;
        color: white;
    }
    p.identityp {
        margin-left: 15px;
    }
    img.preview {
        width: 175px;
        height: 200px;
        margin-top: 15px;
        margin-left: 15px;
    }
    .removeEmergency {
        position: absolute;
        padding-top: 50%;
        bottom: 0;
        vertical-align: middle;
        height: 87%;
        width: 96%;
        left: 25px !important;
        right: 0px;
        text-align: center;
        background-color: rgba(0,0,0,0.6);
        top: 0;
        display: none;
        cursor: pointer;
        text-decoration: none !important;
    }
    .disable_sec {
        z-index: -10000 !important;
    }
</style>

