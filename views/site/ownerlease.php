<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'PMS - Agreements';
$this->params['breadcrumbs'][] = $this->title;
//echo "<pre>";print_r($agreement);echo "</pre>";die;
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> 
<div id="content">
    <button type="button" id="sidebarCollapse" class="btn btn-info navbar-btn">
        <i class="glyphicon glyphicon-align-left"></i>
        <span></span>
    </button>
    <div class="row" style="margin-top: -40px;">
        <div class="col-md-8 col-sm-8 cust-servicelevelfont">
            <h4><?= strtoupper(Html::encode(Yii::$app->userdata->getPropertyNameById($agreement->property_id))) ?> - SERVICE LEVEL AGREEMENT</h4>
        </div>   
        <div class="col-md-4 col-sm-4">
            <!--button id="first_button" class="btn btn-info btn-lg pull-right">Save </button-->
                                     <!--a id="" class="btn btn-info btn-lg pull-right cust_btn1" href="<?php echo Url::home(true); ?>site/createpdfagreement?id=<?= $agreement->id; ?>">Download PDF</a-->
            <a id="" class="btn btn-info btn-lg pull-right cust_btn1 cust-downloadwidht" href="<?php echo Url::home(true) . $agreement->agreement_url; ?>" download>Download PDF</a>
        </div>             
    </div>
    <hr>
    <div class="row">
        <div class="col-md-7">
            <h5>AGREEMENT TYPE : <?= strtoupper(Html::encode(Yii::$app->userdata->getAgreementTypeName($agreement->agreement_type))) ?></h5>
        </div>
        <div class="col-md-8">


        </div>
    </div>
    <div class="row identity_bg">
        <div>&nbsp;</div>
        <div class="col-md-12" style="margin: 5px;">
            <?php if ($agreement->agreement_type != 1) { ?>
                <div class="row">
                    <div class="col-md-4">
                        <b id="bold">Rent</b>
                        <div class="input-group colorpicker colorpicker-element">
                            <div class="form-line">
                                <input type="text" class="form-control" value="Rs. <?= $agreement->rent; ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <b id="bold">Maintenance</b>
                        <div class="input-group colorpicker colorpicker-element">
                            <div class="form-line focused">
                                <input type="text" class="form-control" value="Rs. <?= $agreement->manteinance; ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <b id="bold">Deposit</b>
                        <div class="input-group colorpicker colorpicker-element">
                            <div class="form-line focused">
                                <input type="text" class="form-control" value="Rs.  <?= $agreement->deposit; ?>" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <br/>
            <?php } ?>

            <div class="row">
                <div class="col-md-4 col-sm-4">
                    <b id="bold">Contract Start date</b>
                    <div class="input-group colorpicker colorpicker-element cust-group">
                        <div class="form-line focused">
                            <input type="text" class="form-control" value="<?= date('d-M-Y', strtotime($agreement->contract_start_date)); ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                    <b id="bold">Contract End date</b>
                    <div class="input-group colorpicker colorpicker-element cust-group">
                        <div class="form-line focused">
                            <input type="text" class="form-control" value="<?= date('d-M-Y', strtotime($agreement->contract_end_date)); ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                    <b id="bold">PMS Fee %</b>
                    <div class="input-group colorpicker colorpicker-element cust-group">
                        <div class="form-line">
                            <input type="text" class="form-control" value="<?= $agreement->pms_commission; ?>%" readonly>
                        </div>
                    </div>
                </div>  
            </div>


            <br/>
            <?php
            if ($agreement->furniture_rent == '') {
                $furniture_rent = 0;
            } else {
                $furniture_rent = $agreement->furniture_rent;
            }
            ?>
            <div class="row cust-b2">
                <div class="col-md-4 col-sm-4">
                    <b id="bold">Furniture Charges</b>
                    <div class="input-group colorpicker colorpicker-element cust-group">
                        <div class="form-line focused">
                            <input type="text" class="form-control" value="Rs. <?= $furniture_rent; ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                    <b id="bold">Notice Period (months)</b>
                    <div class="input-group colorpicker colorpicker-element cust-group">
                        <div class="form-line">
                            <input type="text" class="form-control" value="<?= $agreement->notice_peroid; ?> Month" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                    <b id="bold">Minimum Contract Period (months)</b>
                    <div class="input-group colorpicker colorpicker-element cust-group">
                        <div class="form-line focused">
                            <input type="text" class="form-control" value="<?= $agreement->min_contract_peroid; ?> Month" readonly>
                        </div>
                    </div>
                </div>

            </div>
            <div>&nbsp;</div>
        </div>

    </div>
    <br/>


    <div class="col-md-7 col-md-offset-5 cust-nopad">
        <!--div class="col-md-4">
            <a id="" class="btn btn-primary btn-lg pull-right" href="<?php echo Url::home(true); ?>site/createpdfagreement?id=<?= $agreement->id; ?>">Download PDF </a>
        </div-->
        <div class="col-md-8 col-sm-6 cust-nopad">
            <button id="g1" class="btn cust_btn_gr btn-lg pull-right cust-pullright1 cust-pullright" onclick="requestextension()"> Request SLA Extension </button>
        </div>
        <div class="col-md-4 col-sm-6 cust-nopad" style="padding-right: 0px;">
            <button id="g2" class="btn  btn-lg pull-right cust_btn cust-pullright" onclick="requesttermination()">Request SLA Termination</button>
        </div>

    </div>



</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
        });
    });

    function requestextension()
    {
        var pid = "<?php echo $agreement->property_id; ?>";
        var end_date = "<?php echo $agreement->contract_end_date; ?>";
        var csrfToken = "<?= Yii::$app->request->getCsrfToken() ?>";
        $.ajax({
            url: "requestextension",
            type: "POST",
            data: {'pid': pid, '_csrf': csrfToken, 'end_date': end_date},
            success: function (data) {
                alert(data);
            }

        });
    }

    function requesttermination()
    {
        var pid = "<?php echo $agreement->property_id; ?>";
        var end_date = "<?php echo $agreement->contract_end_date; ?>";
        var csrfToken = "<?= Yii::$app->request->getCsrfToken() ?>";
        $.ajax({
            url: "requesttermination",
            type: "POST",
            data: {'pid': pid, '_csrf': csrfToken, 'end_date': end_date},
            success: function (data) {
                alert(data);
            }

        });
    }
</script>	
<style>

    @media screen and (min-width: 768px) and (max-width: 912px){
        #g2{
            float: left !important;
        }
    }
    @media screen and (min-width: 993px) and (max-width: 1249px){
        #g1{
            margin-right: 70px !important;
        }
    }
    @media screen and (max-width: 767px){
        .cust-group{
            padding-top: 0 !important;
        }
        .cust-b2{
            margin-top: -15px;
        }
    }

    select.form-control {
        width: 89% !important;
        border-top: none;
        border-right: none;
        background: #f5f5f5;
        box-shadow: none;
        border-left: none;
        padding-left: 0;
        border: 1px solid #f5f5f5;
        border-bottom: 1px solid rgba(50, 58, 71, 0.2);
    }

    .bgstyle {
        background-color: #fafafa !important;
        background-color: var(--white);
    }

    .box+.box {
        margin-top: 2.5rem;
    }

    .well {
        min-height: 20px;
        padding-top: 19px;
        margin-bottom: 20px;
        background-color: transparent !important;
        border: none !important;
        border-radius: 0px !important;
        -webkit-box-shadow: none !important;
        box-shadow: none !important;
        padding-left: 0px;
    }

    .box {
        background-color: #e9e9e9;
        padding: 6.25rem 1.25rem;
    }

    .inputfile {
        width: 0.1px;
        height: 0.1px;
        opacity: 0;
        overflow: hidden;
        position: absolute;
        z-index: -1;
    }

    .input-group {
        position: relative;
        display: table;
        border-collapse: separate;
        width: 75%;
        padding-top: 15px;
    }

    input.form-control {
        border-top: none;
        border-right: navajowhite;
        background: #fafafa;
        box-shadow: none;
        border-left: navajowhite;
        padding-left: 0;
    }

    button#first_button {
        background-color: #F44336;
        border-color: #f44336;
        width: 39%;
        /* margin-top: -15px; */
        /* margin-top: -42px; */
        width: 140px;
        height: 40px;
        border-radius: 3px;
        font-size: 13px;
        font-weight: bold;
        font-style: normal;
        font-stretch: normal;
    }

    .identity_bg {
        /* background-color: #ffffff !important; */
        background-color: #f5f5f5;
        margin-top: 15px;
        margin-left: 2px;
        width: 99%;
    }

    .adar_img {
        width: 286px;
        height: 288px;
    }

    #content {
        width: 100%;
    }

    input.form-control {
        background: whitesmoke;
    }
    .form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
        background-color: #f5f5f5 !important;
        opacity: 1;
    }
    .cust_btn{
        background: #e24c3f;
        height: 40px;
        padding-top: 6px;
        border: 1px solid #e24c3f;
        color: white;
        border-radius: 2px;
        font-size: 13px;
        font-weight: bold;
        /* width: 152px; */
        /* height: 16px; */
        /* font-family: Lato; */
        font-size: 13px;
        font-weight: bold;
        font-style: normal;
        font-stretch: normal;
        /* line-height: 1.23; */
        letter-spacing: 0.9px;
        /* text-align: left; */
        /* color: #ffffff; */
        margin-right: -10px !important;
        width:212px !important;
    }
    .cust_btn_gr {
        background: #59abe3;
        height: 40px;
        padding-top: 6px;
        border: 1px solid #59abe3;
        color: white;
        border-radius: 2px;
        font-size: 13px;
        font-weight: bold;
        font-size: 13px;
        font-weight: bold;
        font-style: normal;
        font-stretch: normal;
        letter-spacing: 0.9px;
        margin-right: 15px !important;
        width:212px !important;
    }
    .cust_btn1 {
        width: 180px !important;
        background: #e24c3f;
        height: 40px;
        /* padding-top: 6px; */
        border: 1px solid #e24c3f;
        border-radius: 2px;
        color: white;
        font-size: 13px;
        font-weight: bold;
        font-style: normal;
        font-stretch: normal;
        line-height: 1.23;
        letter-spacing: 0.9px;
        /* text-align: left; */
        color: #ffffff;
        margin-right: 5px;
    }
    @media screen and (max-width: 767px){
        .cust_btn1{
            width: 100% !important;
            float: left !important;
            height: 40px !important;
            padding: 11px !important;
        }
        .cust-nopad{
            padding: 0;
        }
        #g1{
            width: 100% !important;
        }
        #g2{
            width: 100% !important;
        }
    }
</style>		
