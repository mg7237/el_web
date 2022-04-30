<?php
$this->title = 'PMS - Refer Owner';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    select
    {
        /*text-indent:-1.5px;*/
        margin-left: -4px;
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
        .cust-control{
            margin: -8px 0px;
        }
        .cust-line{
            margin-top: -40px !important;
        }
        .bgstyle{
            margin-top: -15px !important;
            padding: 0;
        }
        .cust-state{
            margin: -10px 0;
        }
        b#bold {
            line-height: 2em !important;
        }
    }

</style>
<div id="content">
    <button type="button" id="sidebarCollapse" class="btn btn-info navbar-btn">
        <i class="glyphicon glyphicon-align-left"></i>
        <span></span>
    </button>
    <?php $form = ActiveForm::begin(array('id' => 'ownerform')); ?>
    <div class="container" style="margin-top: -42px;">
        <?php if (Yii::$app->session->hasFlash('success')) { ?>
            <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                <?= Yii::$app->session->getFlash('success'); ?>
            </div>
        <?php } ?> 
        <div class="col-md-5 col-sm-4">
            <h4>REFER PROPERTY OWNER</h4>
        </div>
        <div class="col-md-7 col-sm-8 cust-owner">

            <?= Html::submitButton('Submit', ['class' => 'btn btn-danger btn-lg pull-right cust-saveowner savebutton buttonSubmit', 'onclick' => 'saveform()']) ?>
            <button id="first_button" class="btn btn-info btn-lg pull-right cancelbutton cust-cancelowner" style="margin-right:20px !important;background-color: #aea1a0 !important;border-color: #aea1a0 !important;" onclick="cancelform()">Cancel</button>


            <!--button id="first_button" class="btn btn-info btn-lg pull-right">Save </button-->



        </div>


    </div>
    <hr>
    <div class="container">



        <form>
            <div class="col-sm-12 bgstyle" style="margin-top: 25px;">
                <div class="col-md-12">
                    <b id="bold">Property Owner Name *</b>
                    <div class="form-group cust-control">
                        <!--input type="text"  class="form-control" placeholder="Property Owner Name"-->
                        <?= $form->field($model, 'full_name')->textInput(['maxlength' => true, 'placeholder' => '', 'id' => 'full_name'])->label(false); ?>
                    </div>
                </div>
                <div class="col-md-12">
                    <b id="bold"> Property Owner Email *</b>

                    <div class="form-group cust-control">
                        <!--input type="text"  class="form-control" placeholder="Property Owner Email"-->
                        <?= $form->field($model, 'email_id')->textInput(['maxlength' => true, 'placeholder' => '', 'id' => 'email_id'])->label(false); ?>
                    </div>
                </div>

                <div class="col-md-12">
                    <b id="bold">Property Owner Contact Number *</b>
                    <!--select class="form-control">
<option  value="" class="" selected="selected">Tenant Contact No</option>
<option  value="string:9">9876543210</option>
<option  value="string:5">9876543211</option>
<option  value="string:4">9876543212</option>

</select-->
                    <div class="form-group cust-control">
                        <?= $form->field($model, 'contact_number')->textInput(['maxlength' => 15, 'placeholder' => '', 'class' => 'form-control', 'id' => 'contact_number'])->label(false); ?>
                    </div>
                </div>



                <br><br>
                <div class="clearfix"></div>
                <div class="col-md-12 adrs-line cust-line">
                    <b id="bold">Property Owner Address Line 1 *</b>
                    <div class="form-group cust-control">
                        <!--input type="text" class="form-control" id="address1" placeholder="Address Line 1"-->
                        <?= $form->field($model, 'address')->textInput(['maxlength' => true, 'placeholder' => '', 'id' => 'address'])->label(false); ?>
                    </div>
                </div>
                <div class="col-md-12">
                    <b id="bold">Property Owner Address Line 2 </b>
                    <div class="form-group cust-control">
                        <!--input type="text" class="form-control" id="address2" placeholder="Address Line 2"-->
                        <?= $form->field($model, 'address_line_2')->textInput(['maxlength' => true, 'placeholder' => ''])->label(false); ?>
                    </div>
                </div>

                <div class="col-md-12">
                    <b id="bold">Owner's City * </b>
                    <div class="form-group cust-control">
                        <!--input type="text" class="form-control" id="address2" placeholder="Address Line 2"-->
                        <?= $form->field($modelProfile, 'city_name')->textInput(['maxlength' => true, 'placeholder' => ''])->label(false); ?>
                    </div>
                </div>

                <?php
                $states = \app\models\States::find()->all();
                $stateData = \yii\helpers\ArrayHelper::map($states, 'id', 'name');
                ?>
                <div class="col-md-12 cust-state"> 
                    <b id="bold">State *</b>
                    <?php //echo $form->field($model, 'state')->dropDownList($stateData, ['prompt' => 'Select state', 'onchange' => 'getcity(this.value)', 'id' => 'state'])->label(false); ?>
                    <?= $form->field($model, 'lead_state')->dropDownList($stateData, ['prompt' => 'Select state', 'id' => 'state'])->label(false); ?>
                </div>

                <div class="col-md-12 adrs-line">
                    <b id="bold">Pincode *</b>
                    <div class="form-group cust-control">
                        <!--input type="text" class="form-control" id="pincode" placeholder="Pincode"-->
                        <?= $form->field($model, 'pincode')->textInput(['maxlength' => 15, 'placeholder' => '', 'class' => 'form-control', 'id' => 'pincode'])->label(false); ?>
                    </div>
                </div>

                <?php
                $cities = \app\models\Cities::find()->where(['status' => 1])->all();
                $cityData = \yii\helpers\ArrayHelper::map($cities, 'id', 'city_name');
                ?>
                <div class="col-md-12 adrs-line city">
                    <b id="bold">City Where Property is Located *</b>
                    <!--select class="form-control">
                    <option value="" class="" selected="selected">Select city</option>
                    <option label="Hyderabad" value="string:9">Hyderabad</option>
                    <option label="Banglore" value="string:9">Banglore</option>
                    <option label="Mysore" value="string:5">Mysore</option>
                    <option label="mandya" value="string:4">mandya</option>
                    <option label="Manglore" value="string:3">Manglore</option>
                </select-->
                    <?= $form->field($model, 'lead_city')->dropDownList($cityData, ['prompt' => 'Select city where property is located', 'id' => 'city2'])->label(false); ?>
                </div>

                <div class="col-md-12">
                    <b id="bold">Advisor Communication with Property Owner *</b>
                    <div class="form-group">

                        <!--textarea class="form-control"  id="comment"></textarea-->
                        <?= $form->field($model, 'communication')->textarea(['id' => 'communication'])->label(false); ?>
                    </div>

                </div>



            </div>
        </form>


    </div>
    <?php ActiveForm::end(); ?>
</div>

<script>
    function cancel_owner()
    {
        var r = confirm("Are you sure you want to cancel?");
        if (r == true) {
            $("#ownerform").trigger("reset");
        }

    }
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
            url: '<?php echo Url::home(true); ?>users/getcityp',
            type: 'post',
            data: {'state': state, '_csrf': _csrf},
            success: function (res) {

                $(".city").html(res);
            },
        });
    }

    function saveform()
    {
        var full_name = $("#full_name").val();
        var email_id = $("#email_id").val();
        var contact_number = $("#contact_number").val();
        var address = $("#address").val();
        var state = $("#state").val();
        var city = $("#city").val();
        var city2 = $("#city2").val();
        var pincode = $("#pincode").val();
        var communication = $("#communication").val();
        if (full_name == '')
        {
            $("#full_name").focus();
        } else if (email_id == '')
        {
            $("#email_id").focus();
        } else if (contact_number == '')
        {
            $("#contact_number").focus();
        } else if (address == '')
        {
            $("#address").focus();
        } else if (state == '')
        {
            $("#state").focus();
        } else if (city == '')
        {
            $("#city").focus();
        } else if (city2 == '')
        {
            $("#city2").focus();
        } else if (pincode == '')
        {
            $("#pincode").focus();
        } else if (communication == '')
        {
            $("#communication").focus();
        }
    }
</script>