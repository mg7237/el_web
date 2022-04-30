<?php
$this->title = 'PMS - Refer Tenant';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $form yii\widgets\ActiveForm */
?>

<link rel="stylesheet" href="<?php echo Yii::$app->userdata->autoVersion(Url::home(true) . 'css/css/newstyle_admin/mystyle.css'); ?>">
<style>
    .container
    {
        width:100%;
    }
    select
    {
        /*text-indent:-1.5px;*/
        margin-left: -4px;
    }
    b#bold
    {
        line-height: 3em !important;
    }
    .adrs-line
    {
        margin-top: 0px !important;
    }
    select.form-control,input.form-control
    {
        /*border: 1px solid #ccc;*/
        background-color:transparent !important;
    }
    input.form-control {
        /*border-top: none;
        border-right: navajowhite;*/
        background: #fafafa;
        box-shadow: none;
        /*border-left: navajowhite;*/
        padding-left: 0;
    }

    button#first_button {
        background-color: #f0f0f0;
        border-color: #f0f0f0;
        width: 39%;
        /* margin-top: -15px; */
        /* margin-top: -42px; */
        width: 140px;
        height: 40px;
        border-radius: 3px;
        font-size: 13px;
        font-weight:500;
        font-style: normal;
        font-stretch: normal;
    }

    .bgstyle {
        background-color: #fafafa !important;
        background-color: var(--white);
        margin-left: 0px !important;
        /*padding-top: 15px;*/
    }

    .form-control {
        border-radius: 0px !important;
    }

    select.form-control {
        width: 100% !important;
        /*border-top: none;
        border-right: none;*/
        background: #fafafa;
        box-shadow: none;
        /*border-left: none;*/
        padding-left: 0;
    }

    #name1 {
        margin-top: -5px !important;
    }

    .buttonSubmit,.buttonSubmit:hover
    {
        background-color: #F44336;
        border-color: #f44336;
        width: 39%;

        width: 140px;
        height: 40px;
        border-radius: 3px;
        font-size: 13px;
        font-weight: 500;
        font-style: normal;
        font-stretch: normal;
    }	
    @media screen and (max-width: 475px){
        .cust-cancelowner{
            width: 42% !important;
        }
        .cust-saveowner{
            width: 42% !important;
        }
    }
    @media screen and (max-width: 767px){
        .cust-owner{
            margin-left: -25px;
        }
        .cust-group{
            margin: -8px 0;
        }
        .cust-line{
            margin-top: -50px !important;
        }
    }
    @media screen and (min-width: 768px) and (max-width: 990px){
        .cust-ownermar{
            margin-top: -37px;
            margin-left: 70%;
            width: 100%;
        }
    }
</style>
<div id="content">
    <div class="container">
        <div class="row">
            <button type="button" id="sidebarCollapse" class="btn btn-info navbar-btn">
                <i class="glyphicon glyphicon-align-left"></i>
                <span></span>
            </button>
            <?php $form = ActiveForm::begin(array('id' => 'applicantform')); ?>
            <div class="container" style="margin-top: -42px;">
                <?php if (Yii::$app->session->hasFlash('success')) { ?>
                    <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                        <?= Yii::$app->session->getFlash('success'); ?>
                    </div>   
                <?php } ?>
                <div class="col-md-6">
                    <h4>REFER TENANT</h4>
                </div>
                <div class="col-md-6 cust-owner">
                    <div class="cust-ownermar">
                        <?= Html::submitButton('Submit', ['class' => 'btn btn-danger btn-lg pull-right savebutton buttonSubmit cust-saverefer cust-saveowner', 'onclick' => 'saveform()']) ?>
                        <button id="first_button" class="btn btn-info btn-lg pull-right cancelbutton cust-cancelrefer cust-cancelowner" style="margin-right:20px !important;background-color: #aea1a0 !important;border-color: #aea1a0 !important;" onclick="cancelform()">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr>
    <div class="container">

        <div class="col-sm-12 bgstyle" style="margin-top: 25px;">
            <div class="col-md-12">
                <b id="bold">Tenant Name *</b>
                <div class="form-group cust-group">
                    <!--input type="text"  class="form-control" value="Tenant Name"-->
                    <?= $form->field($model, 'full_name')->textInput(['maxlength' => true, 'placeholder' => '', 'id' => 'tenant_name'])->label(false); ?>
                </div>

            </div>
            <div class="col-md-12">
                <b id="bold"> Tenant Email *</b>

                <div class="form-group cust-group">
                    <!--input type="text"  class="form-control" value="Tenant Email"-->
                    <?= $form->field($model, 'email_id')->textInput(['maxlength' => true, 'placeholder' => '', 'id' => 'tenant_email'])->label(false); ?>
                </div>
            </div>

            <div class="col-md-12">
                <b id="bold">Tenant Contact Number *</b>
                <!--select class="form-control">
<option  value="" class="" selected="selected">Tenant Contact No</option>
<option  value="string:9">9876543210</option>
<option  value="string:5">9876543211</option>
<option  value="string:4">9876543212</option>

</select-->
                <div class="form-group cust-group">
                    <?= $form->field($model, 'contact_number')->textInput(['maxlength' => true, 'placeholder' => '', 'class' => 'form-control isPhone', 'id' => 'mobile1'])->label(false); ?>
                </div>
            </div>



            <br><br>
            <div class="clearfix"></div>
            <div class="col-md-12 adrs-line cust-line">
                <b id="bold">Address Line 1 *</b>
                <div class="form-group cust-group">
                    <!--input type="text" class="form-control" id="address1" placeholder="Address Line 1"-->
                    <?= $form->field($model, 'address')->textInput(['maxlength' => true, 'placeholder' => '', 'id' => 'address_line_1'])->label(false); ?>
                </div>
            </div>
            <div class="col-md-12">
                <b id="bold">Address Line 2 </b>
                <div class="form-group cust-group">
                    <!--input type="text" class="form-control" id="address2" placeholder="Address Line 2"-->
                    <?= $form->field($model, 'address_line_2')->textInput(['maxlength' => true, 'placeholder' => ''])->label(false); ?>
                </div>
            </div>

            <div class="col-md-12">
                <b id="bold">Tenant City *</b>
                <div class="form-group cust-group">
                    <?= $form->field($modelProfile, 'city_name')->textInput(['maxlength' => true, 'placeholder' => ''])->label(false); ?>
                </div>
            </div>




            <?php
            $states = \app\models\States::find()->all();
            $stateData = \yii\helpers\ArrayHelper::map($states, 'id', 'name');
            ?>
            <div class="col-md-12">
                <b id="bold">Tenant State *</b>
                <?= $form->field($model, 'lead_state')->dropDownList($stateData, ['prompt' => 'Select State', 'onchange' => 'return true; getcity(this.value)', 'id' => 'state'])->label(false); ?>
            </div>

            <div class="col-md-12 adrs-line">
                <b id="bold">Tenant Pincode *</b>
                <div class="form-group cust-group">
                    <!--input type="text" class="form-control" id="pincode" placeholder="Pincode"-->
                    <?= $form->field($model, 'pincode')->textInput(['maxlength' => 6, 'placeholder' => '', 'class' => 'form-control isNumber', 'id' => 'pincode'])->label(false); ?>
                </div>
            </div>

            <?php
            $cities = \app\models\Cities::find()->where(['status' => 1])->all();
            $cityData = \yii\helpers\ArrayHelper::map($cities, 'id', 'city_name');
            ?>

            <div class="col-md-12 adrs-line city">
                <b id="bold">City Where the Property is Located *</b>
                <?= $form->field($model, 'lead_city')->dropDownList($cityData, ['prompt' => 'Select City', 'class' => 'form-control', 'id' => 'city'])->label(false); ?>
            </div>

            <div class="col-md-12">
                <b id="bold">Advisor Communication with Tenant *</b>
                <div class="form-group cust-group">

                    <!--textarea class="form-control"  id="comment"></textarea-->
                    <?= $form->field($model, 'communication')->textarea(['id' => 'communication'])->label(false); ?>
                </div>

            </div>



        </div>



    </div>
    <?php ActiveForm::end(); ?>
</div>
<script>
    function cancel_applicant()
    {
        var r = confirm("Are you sure you want to cancel?");
        if (r == true) {
            $("#applicantform").trigger("reset");
        }

    }
    $('#mobile1').keypress(function (event) {

        if (event.which != 8 && isNaN(String.fromCharCode(event.which))) {
            event.preventDefault(); //stop character from entering input
        }

    });

    function cancelform()
    {
        var result = confirm("Are you sure you want to cancel?");
        if (result) {
            location.reload();
        }
    }

    function getcity(target)
    {
        var state = target;
        var _csrf = "<?= Yii::$app->request->getCsrfToken() ?>";
        $.ajax({
            url: '<?php echo Url::home(true); ?>users/getcityt',
            type: 'post',
            data: {'state': state, '_csrf': _csrf},
            success: function (res) {

                $(".city").html(res);
            },
        });
    }

    function saveform()
    {
        var tenantname = $("#tenant_name").val();
        var tenantemail = $("#tenant_email").val();
        var mobile1 = $("#mobile1").val();
        var address_line_1 = $("#address_line_1").val();
        var state = $("#state").val();
        var city = $("#city").val();
        var pincode = $("#pincode").val();
        var communication = $("#communication").val();
        if (tenantname == '')
        {
            $("#tenant_name").focus();
        } else if (tenantemail == '')
        {
            $("#tenant_email").focus();
        } else if (mobile1 == '')
        {
            $("#mobile1").focus();
        } else if (address_line_1 == '')
        {
            $("#address_line_1").focus();
        } else if (state == '')
        {
            $("#state").focus();
        } else if (city == '')
        {
            $("#city").focus();
        } else if (pincode == '')
        {
            $("#pincode").focus();
        } else if (communication == '')
        {
            $("#communication").focus();
        }
    }
</script>
