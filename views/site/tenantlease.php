<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'PMS - Agreements';
$this->params['breadcrumbs'][] = $this->title;
//echo "<pre>";print_r($myAgreements);echo "</pre>";die;
?>
<link href="https://fonts.googleapis.com/css?family=Roboto:700" rel="stylesheet">
<div id="content">
    <button type="button" id="sidebarCollapse" class="btn btn-info navbar-btn">
        <i class="glyphicon glyphicon-align-left"></i>
        <span></span>
    </button>
    <div class="row">
        <div class="col-md-10 col-sm-8">
            <h4><?= strtoupper(Html::encode(Yii::$app->userdata->getPropertyNameById($_GET['prop_id']))) ?>  <?php
                if (!empty($myAgreements->bed_id)) {
                    echo " - (" . Yii::$app->userdata->getRoomName($myAgreements->bed_id) . ")";
                } else if (!empty($myAgreements->room_id) && empty($myAgreements->bed_id)) {
                    echo " - (" . Yii::$app->userdata->getRoomName($myAgreements->room_id) . ")";
                }
                ?> - TENANT DETAILS</h4>
        </div>  
        <div class="col-md-2 col-sm-4">
            <!--a id="" class="btn btn-primary btn-lg pull-right cust_btn" href="<?php echo Url::home(true); ?>site/createpdfagreement?id=<?= $myAgreements->id; ?>">Download Lease Agreement</a-->
            <a id="" class="btn btn-primary btn-lg pull-right cust_btn cust-leaseagreement" href="<?php echo Url::home(true) . $myAgreements->agreement_url; ?>" download>Download Lease Agreement</a>
        </div>              
    </div>
    <hr/>
    <div class="row">
        <div class="col-md-7">
            <h4 id="personal_info">LEASE DETAILS</h4>
        </div>

    </div>
<?php //echo "<pre>";print_r($myAgreements);echo "</pre>";die;  ?>
    <div class="row identity_bg">
        <div>&nbsp;</div>
        <div class="col-md-12" style="margin: 5px;">
            <div class="row">
                <div class="col-md-4">
                    <b id="bold">Rent</b>
                    <div class="input-group cust-group colorpicker colorpicker-element">
                        <div class="form-line">
                            <input type="text" class="form-control" value="Rs. <?= $myAgreements->rent ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <b id="bold">Maintenance</b>
                    <div class="input-group cust-group colorpicker colorpicker-element">
                        <div class="form-line focused">
                            <input type="text" class="form-control" value="Rs. <?= $myAgreements->maintainance ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <b id="bold">Deposit</b>
                    <div class="input-group cust-group colorpicker colorpicker-element">
                        <div class="form-line focused">
                            <input type="text" class="form-control" value="Rs.  <?= $myAgreements->deposit ?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <br/>
            <div class="row cust-mrt2">
                <div class="col-md-4">
                    <b id="bold">Lease Start Date</b>
                    <div class="input-group cust-group colorpicker colorpicker-element">
                        <div class="form-line">
                            <input type="text" class="form-control" value="<?= date('d-M-Y', strtotime($myAgreements->lease_start_date)); ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <b id="bold">Lease End Date</b>
                    <div class="input-group cust-group colorpicker colorpicker-element">
                        <div class="form-line focused">
                            <input type="text" class="form-control" value="<?= date('d-M-Y', strtotime($myAgreements->lease_end_date)); ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <b id="bold">Notice Period</b>
                    <div class="input-group cust-group colorpicker colorpicker-element">
                        <div class="form-line focused">
                            <input type="text" class="form-control" value="<?= $myAgreements->notice_period; ?> <?php if ($myAgreements->notice_period > 1) { ?>Months<?php } else { ?>Month<?php } ?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <br/>
            <div>&nbsp;</div>
        </div>

    </div>
    <br/>


    <div class="col-md-7 col-md-offset-5">
        <div class="col-md-4">
            <!-- <a id="" class="btn btn-primary btn-lg pull-right" href="<?php echo Url::home(true); ?>site/createpdfagreement?id=<?= $myAgreements->id; ?>">Download PDF </a> -->
        </div>
        <!--div class="col-md-4">
            <button id="" class="btn btn-danger btn-lg pull-right">Renew contract</button>
        </div>
                        <div class="col-md-4" style="padding-right: 0px;">
            <button id="" class="btn btn-primary btn-lg pull-right">&nbsp;&nbsp;&nbsp;&nbsp; Exit contract &nbsp;&nbsp;&nbsp;&nbsp;</button>
        </div-->
    </div>



</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
        });
    });
</script>	
<style>
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

    .cust_btn {
        /* background: #e24c3f !important; */
        /* height: 40px; */
        /* padding-top: 7px; */
        border: 1px solid #e24c3f;
        /* width: 212px; */
        height: 40px;
        border-radius: 3px;
        background-color: #e24c3f;
        /* width: 185px; */
        /* height: 16px; */
        font-family: Lato-Regular;
        font-size: 14px;
        font-weight: bold;
        font-style: normal;
        font-stretch: normal;
        line-height: 1.23;
        letter-spacing: 0.9px;
        text-align: left;
        color: #ffffff;
    }
    @media screen and (max-width: 767px){
        .cust-group{
            padding: 0;
        }
        .identity_bg{
            margin: 0;
        }
        .cust-mrt2{
            margin-top: -15px;
        }
    }

</style>		
