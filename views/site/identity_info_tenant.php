
<?php
$this->title = 'PMS- My profile';

use yii\helpers\Url;
use yii\widgets\ActiveForm;

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
            <h4 class="profile_heading">PROFILE</h4>
        </div>
        <div class="col-md-6" style="margin-left: 20px;">
            <button id="first_button" class="btn btn-info btn-lg pull-right editbutton cust-edit" onclick="makeeditable()">Edit</button>
            <button id="first_button" class="btn btn-info btn-lg pull-right savebutton cus_hide cust-save" onclick="saveform()" >Save</button>
            <button id="first_button" class="btn btn-info btn-lg pull-right cancelbutton cus_hide cust-save" style="margin-right:5px !important;background-color: #aea1a0 !important;border-color: #aea1a0 !important;" onclick="cancelform()">Cancel</button>
        </div>
    </div>
    <hr>
    <form method="post" enctype="multipart/form-data" action="<?= \Yii::$app->getUrlManager()->createUrl('site/editidentityinfotenant') ?>" name="identity_info" id="identity_info">
        <div class="container">
            <div class="container-fluid">
                <h4 id="personal_info" style="margin-bottom: 22px !important;">IDENTITY AND ADDRESS PROOF DOCUMENTS</h4>
            </div>
            <div class="col-md-10 bgstyle disable_sec" id="disable_sec_div">
                <input type="hidden" id="_csrf1" class="form-control name1" value="<?= Yii::$app->request->getCsrfToken() ?>" name="_csrf">

                <div class="col-md-12 bgstyle">
                    <div class="row">
                        <div class="uploadbox" id="editbox">
                            <div class="col-md-6 col-sm-6">
                                <select class="selectpicker IDproof cus_hide" disabled id="id_proof">
                                    <option value="">Select ID Proof</option>
                                    <?php foreach ($proofType as $type) {
                                        ?>
                                        <option value="<?= $type->id ?>"><?= $type->name ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12 cust-idproof" id="IDproof">
                            </div>
                            <div class="clearfix"></div>
                            <div class="row identity_image_preview cust-idselection">
                                <?php
                                foreach ($address_proofs as $key => $value) {
                                    ?>
                                    <div class="col-md-3 col-sm-3">
                                        <img class= "preview" alt="" src="../<?= $value->proof_document_url ?>"><p class="identityp"><?php echo Yii::$app->userdata->getProofType($value->proof_type); ?></p><a class="removeIdentity_t" data-id="<?= $value->id ?>"></a>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="col-md-12 bgstyle">
                <div class="col-md-4">
                    <div class="well">
                                            <!-- <input type="file" name="fileupload[]" value="fileupload" id="fileupload" disabled class="inputfile inputfile-4" data-multiple-caption="{count} files selected" multiple=""> -->
                                            <div id="imgdiv"><!--img src="<?php echo Url::home(true); ?>images/img/adar.png" class="img-responsive" id="van-pill-image"--></div></div>

                </div>

            </div>


        </div>
        <input type="hidden" id="idtype" class="form-control name1" name="idtype">
    </form> </div>
</div>

<script>

    function makeeditable()
    {

        //$("#fileupload").prop("disabled", false);
        $("#id_proof").prop("disabled", false);
        jQuery(".editbutton").hide();
        $("#id_proof").addClass("cus_show").removeClass("cus_hide");
        //$("#fileupload").addClass("cus_show").removeClass("cus_hide");
        $(".savebutton").addClass("cus_show").removeClass("cus_hide");
        $(".cancelbutton").addClass("cus_show").removeClass("cus_hide");
        $("#disable_sec_div").removeClass("disable_sec");

    }


    function saveform()
    {
        debugger
        var sessiondata = sessionStorage.getItem("set_id_t");
        if (sessiondata) {
            debugger
            deletedatat();
        }
        $("#identity_info").submit();

    }

    function cancelform()
    {
        location.reload();
    }

    var idArrayt = [];
    $(".removeIdentity_t").click(function () {
        debugger
        // alert("hi");
        var element = $(this);
        var id = $(this).attr('data-id');
        //alert(id);
        idArrayt.push({
            id
        });
        sessionStorage.setItem("set_id_t", JSON.stringify(idArrayt));
        element.closest('div').remove();
    });

    function deletedatat() {

        debugger
        var sessiondata = sessionStorage.getItem("set_id_t");
        //alert("fdsfdsf");
        var sessiondata_id = JSON.parse(sessiondata);
        //alert(sessiondata_id);
        // conole.log(sessiondata_id);
        if (sessiondata != null) {
            for (var i = 0; i < sessiondata_id.length; i++) {

                console.log(sessiondata_id);
                console.log(sessiondata_id[i].id);
                var sessiondata_data_id = sessiondata_id[i].id;
                var csrfToken1 = $("#_csrf1").val();
                //alert("hello");
                $.ajax({
                    url: "../site/removeidentitytenant",
                    type: 'POST',
                    data: {'id': sessiondata_data_id, _csrf: csrfToken1},
                    success: function (data) {
                        if (data) {
                            element.closest('div').remove();
                        }
                    },
                    error: function (data) {
                        //    console.log(data.responseText);
                        alert(data.responseText);
                    }
                });
            }
        }
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
        //alert(count);
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







    window.onload = function () {
        $('#fileupload').addClass('cus_hide');
        jQuery("#fileupload").hide();
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

    var idArray2 = [];
    $(".removeIdentity").click(function () {
        debugger
        debugger
        // alert("hi");
        var element = $(this);
        var id = $(this).attr('data-id');
        idArray2.push({
            id
        });
        sessionStorage.setItem("set_id", JSON.stringify(idArray2));
        element.closest('div').remove();
    });
    $(document).on('click', '.removeLocal', function () {
        $('.' + $(this).attr('id')).remove();
        $(this).closest('div').remove();
    })


</script>
<style>
    .field-applicantprofile-emer_contact_address label, .field-applicantprofile-emer_contact_indentity label{
        display:none;
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
    .row.identity_image_preview div:hover > a {
        display: block;
    }
    .removeLocal::before {
        content: "\f014";
        font-family: FontAwesome;
        font-size: 40px;
        color: white;
    }
</style>
<script type="text/javascript">

    $("#imgInp").change(function () {
        readURL(this);
    });

    $(document).on("click", ".cancel_confirm_2", function (e) {
        e.preventDefault();
        var element = $(this);
        var confirm_var = confirm('Are you sure you want to cancel ?');
        if (confirm_var == true) {
            window.location.href = (element.attr('href'));

        }
    });
</script>
<style>

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
    select#id_proof {
        background: #fafafa;
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
    img.preview {
        width: auto;
        height: 200px;
    }
    p.identityp {
        margin-left: 15px;
    }
    img.preview {
        width: 215px;
        height: 200px;
        margin-top: 15px;
        margin-left: 15px;
    }
    .disable_sec {
        z-index: -10000 !important;
    }

    .removeIdentity_t {
        position: absolute;
        padding-top: 35%;
        bottom: 0;
        vertical-align: middle;
        height: 87%;
        width: 96%;
        left: 25px !important;
        right: 0px;
        text-align: center;
        background-color: rgba(0,0,0,0.6);
        top: 5;
        display: none;
        cursor: pointer;
        text-decoration: none !important;
    }
    .removeIdentity_t::before {
        content: "\f014";
        font-family: FontAwesome;
        font-size: 40px;
        color: white;
        margin-left: 75px;
    }

    @media screen and (max-width: 767px){
        #content{
            width: 100%;
        }
        .cust-idselection{
            width: 100%;
        }
        .cus_show{
            width: 100%;
        }
        .preview{
            width: 100% !important;
        }
        .bgstyle{
            margin-left: -8px !important;
        }
        option{
            width: 10% !important;
        }
    }

    @media screen and (max-width: 376px){
        .cust-save{
            margin-right: -40px !important;
        }
    }
</style>
