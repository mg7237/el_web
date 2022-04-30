<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\db\Query;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Payments';
$this->params['breadcrumbs'][] = $this->title;
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> 
<style>
    .form-control input{
        margin-left: 9%;
        border: 1px solid #337ab7;
        border-radius: 1%;
        border-radius: 25px;
        box-shadow: 4px 3px #d9edf7;

    }
    .alert{
        margin-bottom: 7px;
        margin-top: 5px;
    }
    .alert-danger {
        padding: 7px;
    }
    .btn{
        border-radius: 2px;
    }

    alert {
        padding: 8px;
        margin-bottom: 0px;
    }

    .Title
    {
        display: table-caption;
        text-align: center;
        font-weight: bold;
        font-size: larger;
    }

    .table_td_style{
        width: 20%;
        font-size: 12px;
        font-weight: 600;
    } 
    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
        padding: 8px;
        line-height: 1.42857143;
        vertical-align: top;
        border-top: 1px solid #fff !important;
        font-size: 12px;
    } 
    .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
        border: inherit;
    }
    .tbl_style{
        width: 100%;
        /*margin-left: 20px;*/
        border: 2px solid #ddd !important;
        box-shadow: 0px 2px 2px black;
        -moz-box-shadow: 0px 2px 2px black;
        -webkit-box-shadow: 1px 1px 1px 1px #ddd;
    }
    .input-group
    {
        width:100% !important;
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
    .modal-dialog {
        min-height: 240px;
    }
    .modal-title{
        text-transform: uppercase;
    }

    .modal-body .form-check {
        margin-left: 0px;
    }

    .feed-details-tbl tr th
    {			

        font-size: 12px;
        font-weight: 600;
    }
    .feed-details-tbl tr td
    {
        font-size: 12px;

    }
    #content {
        width: 80%;   
    }
    @media screen and (max-width: 767px){

        .paynw {
            font-weight: 600;
            letter-spacing: 1px;
            margin-right: 40px;
        }
    }
    .tab_row_color{
        background-color: #e8e8e8;
    }
    .table #table-conveniance>tbody>tr>td {
        padding: 0px;
        line-height: 0 ; 
        vertical-align: middle;
        border-top: 0px !important; 
        font-size: 12px;
    }
    #table-conveniance td, th {
        border: 0px ; 
        text-align: center;
        padding: 0px;
        height: 35px !important;
    }
    #table-conveniance  th {
        color: white;
        text-align: center;
    }
    #table-conveniance  td {
        height: 30px !important;
    }
    #table-conveniance td {
        font-weight: bold;
    }
    .table-striped>tbody>tr:nth-of-type(odd) {
        background-color: #ffffff !important;
    }
</style>
<div id="content">
    <button type="button" id="sidebarCollapse" class="btn btn-info navbar-btn">
        <i class="glyphicon glyphicon-align-left"></i>
        <span></span>
    </button>

    <div class="container" style="margin-top: -42px;">
        <div class="col-md-4">
            <h4 style="text-transform: uppercase;"> Payments - due<h4>
                    </div>
                    </div>
                    <hr>
                    <div class="container">
                        <div class="col-lg-12">
                            <?php if (Yii::$app->session->hasFlash('success')) { ?>
                                <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                                    <?= Yii::$app->session->getFlash('success'); ?>
                                </div>   
                            <?php } ?>
                        </div>
                    </div>
                    <div class="container">
                        <div class="col-md-12">
                            <!--p id="table_heading"><a href="<?php echo Url::home(true); ?>site/mypaymentsdue">Payment Due</a> / <a href="<?php echo Url::home(true); ?>site/mypaymentsforcast">Payment Forecast</a> / <a href="<?php echo Url::home(true); ?>site/mypayments">Payment History</a></p-->
                            <table class="table table-bordered tbl_style hide-mobile">
                                <thead>
                                    <tr>
                                        <th>Payment Type</th>
                                        <th>Description</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                        <th>Original Amount</th>
                                        <th>Penalty Amount</th>
                                        <th>Total Amount</th>
                                        <th>Pay Now</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    //echo "<pre>";print_r($pastPayment);echo "</pre>";die;
                                    if ($pastPayment) {

                                        $i = 1;
                                        foreach ($pastPayment as $key => $forePayment) {
                                            if ($forePayment['payment_for'] == 2) {
                                                $for = '0';
                                            } else {
                                                $for = '1';
                                            }
                                            if ($forePayment['payment_for'] == 1) {
                                                $payment_type = 'Adhoc';
                                            } else {
                                                $payment_type = 'Rent';
                                            }
                                            if ($i % 2 != 0) {
                                                $dclass = "dclass";
                                            } else {
                                                $dclass = "";
                                            }
                                            ?>


                                            <?php
                                            if ($forePayment['payment_status'] == 0) {
                                                $paystatus = "Unpaid";
                                            } else if ($forePayment['payment_status'] == 1) {
                                                $paystatus = "Paid";
                                            } else {
                                                $paystatus = "Under Moderation";
                                            }
                                            ?>
                                            <tr  class="<?php echo $dclass; ?>">
                                                <td width="10%"><?= $payment_type ?></td>
                                                <td width="25%"><?= $forePayment['payment_des'] ?></td>
                                                <td width="10%"><?= date('d-M-Y', strtotime($forePayment['due_date'])); ?></td>
                                                <td width="5%">
                                                    <?php echo $paystatus; ?>   
                                                </td>

                                                <td width="12%">&#8377 <?= Yii::$app->userdata->getformat((double) $forePayment['total_amount']) ?></td>
                                                <td width="12%">
                                                    <?php
                                                    $totalPenalty = Yii::$app->userdata->calculatePenalty($forePayment['id']);
                                                    if ($totalPenalty > 0) {
                                                        ?>
                                                        <a href="penaltycalculate?id=<?= $forePayment['id'] ?>&for=<?= $for ?>" type="button" class="btn btn-lg-lg pull-center" data-toggle="modal" data-target="#myModal1-<?= $i; ?>"><i aria-hidden="true" class="fa fa-calculator" style="color:red;">&nbsp;&nbsp;&#8377 <?= Yii::$app->userdata->getformat($totalPenalty) ?></i></a>
                                                    <?php } else { ?>
                                                        &nbsp;&nbsp;&nbsp;&nbsp; &#8377 0
                                                    <?php } ?></td>
                                    <!--<td width="10%">&#8377 <?= Yii::$app->userdata->getformat($forePayment['total_amount'] + $totalPenalty); ?></td>-->
                                                <td width="10%">&#8377 <?= Yii::$app->userdata->getformat($forePayment['total_amount']); ?></td>
                                                <td width="10%"><?php if ($forePayment['payment_status'] == '0') { ?>
                                                        <?php $form = ActiveForm::begin(['action' => 'javascript:void(0)', 'id' => 'payrentform-' . $i, 'options' => ['enctype' => 'multipart/form-data']]); ?>
                                                        <input type="hidden" name="TenantPayments[id]" value="<?php echo base64_encode(Yii::$app->getSecurity()->encryptByKey($forePayment['id'], Yii::$app->params['hash_key'])) ?>" /> 
                                                        <input type="hidden" name="TenantPayments[property_id]" value="<?php echo $forePayment['parent_id'] ?>" /> 
                                                        <input type="hidden" name="payment_action" value="TENANT_PAYMENT" /> 
                                                        <input type="hidden" name="neft-referer-<?= $i; ?>" id="neft-referer-<?= $i; ?>" value="" /> 
                                                        <input type="hidden" name="serial" value="<?= $i; ?>" />
                                                        <input type="hidden" name="TenantPayments[type]" value="<?= Yii::$app->userdata->getPropertyNum($forePayment['parent_id']); ?>" />
                                                        <input type="hidden" name="app_redirect_url" value="<?php echo Url::home(true) ?>site/mypaymentsdue" />
                                                        <input type="hidden" name="TenantPayments[penalty_amount]" value="<?= $totalPenalty ?>" />
                                                        <!--<input type="hidden" name="TenantPayments[totalamount]" value="<?php echo ($forePayment['total_amount'] + $totalPenalty) ?>" />--> 
                                                        <input type="hidden" name="TenantPayments[totalamount]" value="<?php echo ($forePayment['total_amount']) ?>" /> 
                                                        <button class= "btn btn-info" data-toggle="modal" data-target="#myModal-<?= $i; ?>" style="background-color: white;border: none;" onclick="checkpayment(<?= $i; ?>)">
                                                            <img src="<?php echo Url::home(true); ?>images/icons/ic-payment.svg" alt="Pay Now">

                                                        </button>
                                                        <?php ActiveForm::end(); ?>
                                                        <?php
                                                    } else if ($forePayment['payment_status'] == '2') {
                                                        echo '<label>Under Moderation</label>';
                                                        ?>
                                                        <?php $form = ActiveForm::begin(['action' => 'unpaynow', 'id' => 'unpayrentform-' . $i, 'options' => ['enctype' => 'multipart/form-data']]); ?>
                                                        <input type="hidden" name="etc" value="<?php echo $forePayment['id'] ?>" />
                                                        <input type="hidden" name="serial" value="<?= $i; ?>" />
                                                        <input type="hidden" name="unpay" value="1" />
                                                        <input type="hidden" name="app_redirect_url" value="<?php echo Url::home(true) ?>site/mypaymentsdue" />
                                                        <button type="submit" class="btn btn-primary btn-xs" >Undo Payment</button>
                                                        <?php ActiveForm::end(); ?>
                                                        <?php
                                                    } else {
                                                        echo 'Paid';
                                                    }
                                                    ?>


                                                    <div id="myModal-<?= $i; ?>" class="modal fade " role="dialog" tabindex="-1" >
                                                        <div class="modal-dialog cust-mdl" style="margin-left: 10%; margin-top: -5%; width:980px; text-align: center;">
                                                            <!-- Modal content-->
                                                            <div class="modal-content" style="width: 78%; margin-top: 80px;">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                    <h4 class="modal-title">How would you like to make the payment?</h4>
                                                                </div>
                                                                <div class="modal-body" style="height: 500px;">
                                                                    <div class="form-check" style=" text-align: left;">
                                                                        <input class="form-check-input" type="radio"  onclick="hideNeftRefBox(<?= $i; ?>); hidePaytmcharges(<?= $i; ?>);showpaytmnetbanking(<?= $i; ?>);" name="exampleRadios" id="paytmnetbanking-<?= $i; ?>" value="option1" checked>
                                                                        <label class="form-check-label" for="paytmnetbanking-<?= $i; ?>">
                                                                            <!--<img src="<?php echo Url::home(true); ?>images/paytm.png" style=" width: 80px; height: 70px; margin-top: -46px; margin-bottom: -35px; ">-->
                                                                            <span style="font-size: 20px;margin-left: 5px;">NetBanking (zero convenience charges)</span>
                                                                            <button onclick="submitPaytmNetBankingForm(<?= $i; ?>)" type="button" id='Paytmnetbankingconformbtn-<?= $i; ?>' class="btn btn-primary btn pull-right" style="margin-left:243px; margin-top: -2px; display: none;">Continue</button><br>
                                                                        </label><hr>
                                                                    </div>
                                                                    <div class="form-check" style=" text-align: left;">
                                                                        <input class="form-check-input" type="radio"  onclick="hideNeftRefBox(<?= $i; ?>); showPaytmcharges(<?= $i; ?>);hidepaytmnetbanking(<?= $i; ?>);" name="exampleRadios" id="paytm-<?= $i; ?>" value="option1">
                                                                        <label class="form-check-label" for="paytm-<?= $i; ?>">
                                                                            <!--<img src="<?php echo Url::home(true); ?>images/paytm.png" style=" width: 80px; height: 70px; margin-top: -46px; margin-bottom: -35px; ">-->
                                                                            <span style="font-size: 20px;margin-left: 5px;">Wallet, UPI, Credit & Debit Cards</span> 
                                                                            <button onclick="submitPaymentForm(<?= $i; ?>)" type="button" id='PaytmConformbtn-<?= $i; ?>' class="btn btn-primary btn pull-right" style="margin-left:314px; margin-top: -2px; display: none;">Continue</button><br>
                                                                        </label>
                                                                        <div id="conveniance-charges-box" style="display: none;text-align: center; font-size: 17px;"><small style="font-weight: bolder; font-size: 16px;">Convenience Charges (excludes GST) </small></div>
                                                                        <div class="" style="display: none;" id="paytmChargesInfo-<?= $i; ?>">
                                                                            <table id="table-conveniance" style="table-layout: fixed;width: 100%;" class="table table-striped">
                                                                                <thead style='background: black; color: white;'>
                                                                                    <tr>
                                                                                        <th>Instrument</th>
                                                                                        <th>Range</th>
                                                                                        <th>Charges</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td>Credit Card</td>
                                                                                        <td></td>
                                                                                        <td>1.1%</td>
                                                                                    </tr>      
                                                                                    <tr class="tab_row_color">
                                                                                        <td rowspan="2">Debit Card</td>
                                                                                        <td>Amount <= <i style="" class="fa">&#xf156;</i> 2,000</td>
                                                                                        <td>0.85%</td>
                                                                                    </tr>
                                                                                    <tr class="tab_row_color">
                                                                                        <!--<td>Debit Card</td>-->
                                                                                        <td class="tab_row_color">Amount > <i style="" class="fa">&#xf156;</i> 2,000</td>
                                                                                        <td class="tab_row_color">1%</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td rowspan="2" >UPI</td>
                                                                                        <td>Amount <= <i style="" class="fa">&#xf156;</i> 2,000</td>
                                                                                        <td>0.40%</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <!--<td>UPI</td>-->
                                                                                        <td>Amount > <i style="" class="fa">&#xf156;</i> 2,000</td>
                                                                                        <td>0.65%</td>
                                                                                    </tr>
                                                                                    <tr class="tab_row_color">
                                                                                        <td>Paytm Wallet</td>
                                                                                        <td></td>
                                                                                        <td>1%</td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div> 
                                                                    </div>

                                                                    <div class="form-check" style="margin-top: 2%; display: none;">
                                                                        <input class="form-check-input" type="radio"  onclick="hideNeftRefBox(<?= $i; ?>)" name="exampleRadios" id="pay-gate-<?= $i; ?>" value="option1" checked>
                                                                        <label class="form-check-label" for="pay-gate-<?= $i; ?>">
                                                                            Payment Gateway
                                                                        </label>  
                                                                    </div>
                                                                    <hr>
                                                                    <div class="form-check" style="margin-top: 2%; text-align: left;">
                                                                        <input onclick="showNeftRefBox(<?= $i; ?>); hidePaytmcharges(<?= $i; ?>); hidepaytmnetbanking(<?= $i; ?>);" class="form-check-input" type="radio" name="exampleRadios" id="neft-reference-input-<?= $i; ?>" value="">
                                                                        <label class="form-check-label" for="neft-reference-input-<?= $i; ?>" style="font-size: 22px;">
                                                                            NEFT / IMPS
                                                                        </label>
                                                                        <div class="form-inline" id="ref-input-box-<?= $i; ?>" style="display: none;">
                                                                            <div class="">
                                                                                In case you have already paid through <strong>NEFT </strong>or<strong> IMPS</strong>
                                                                                then please enter reference number. 
                                                                            </div>                                                                
                                                                            <div class="form-group mx-sm-3 mb-2">
                                                                                <label for="neft-reference-no-<?= $i; ?>" class="sr-only">Enter Reference Number</label>
                                                                                <input style="margin-left:0px; margin-top: 0px; width: 306px; border: 1px solid #337ab7; margin-top: 8px;" type="text" name="neft-reference-no-<?= $i; ?>" class="form-control" onkeyup="updateNeftRef(<?= $i; ?>);" onclick=""  id="neft-reference-no-<?= $i; ?>" value="<?= $forePayment['neft_reference'] ?>" placeholder=" NEFT/IMPS reference number">
                                                                            </div>
                                                                            <div>
                                                                                <button onclick="submitPaymentForm(<?= $i; ?>)" type="button" class="btn btn-primary btn" style="margin-left: 647px; margin-top: -35px;" disabled="disabled" id="displaybtn-<?= $i; ?>">Continue</button><br>
                                                                            </div>
                                                                        </div>
                                                                        <!--<button onclick="submitPaymentForm(<?= $i; ?>)" type="button" class="btn btn-primary btn pull-right" style="margin-left: 50px; margin-top: -42px;">Continue</button><br>-->

                                                                        <div class="form-inline" id="ref-input-box2-<?= $i; ?>" style="display: none;">
                                                                            <div style="margin-top: 10px;">
                                                                                * Note that the payment status will be marked as paid only once the payment has been reconciled at our bank account .
                                                                                For instant payment status update & receipt we recommend you  utilize <strong>Paytm option </strong>.
                                                                            </div>
                                                                        </div>
                                                                    </div><hr>



                                                                    <br />
                                                                    <center>
                                                                        <button type="button" class="btn btn-danger" style="position: absolute;bottom: 0; margin-bottom: 12px;margin-left: -5%;" data-dismiss="modal">Cancel</button>
                                                                    </center>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </td>

                                        <div class="modal fade" id="myModal1-<?= $i; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                            <div class="modal-dialog modalbg modalbgpopup" role="document">
                                                <div class="modal-content">
                                                </div>
                                            </div>


                                        </div>

                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                } else {
                                    ?> 
                                    <tr> <td colspan="6">No payment due</td> </tr>
                                    <?php
                                }
                                ?> 
                                </tbody>
                            </table>

                            <!-- mobile view -->
                            <section class="show-mobile mar1">
                                <div class="continer">
                                    <?php
                                    //echo "<pre>";print_r($pastPayment);echo "</pre>";die;
                                    if ($pastPayment) {

                                        $i = 1;
                                        foreach ($pastPayment as $key => $forePayment) {
                                            if ($forePayment['payment_for'] == 2) {
                                                $for = '0';
                                            } else {
                                                $for = '1';
                                            }
                                            if ($forePayment['payment_for'] == 1) {
                                                $payment_type = 'Adhoc';
                                            } else {
                                                $payment_type = 'Rent';
                                            }
                                            if ($i % 2 != 0) {
                                                $dclass = "dclass";
                                            } else {
                                                $dclass = "";
                                            }
                                            ?>
                                            <div class="row card-bacolor">
                                                <div class="cust-card">

                                                    <form>
                                                        <div class="form-row cust-form">
                                                            <div class="form-group col-md-12">
                                                                <label for="inputEmail4">Payment Type : </label>
                                                                <p class="crd-style"><?= $payment_type ?></p>
                                                                <!-- <input type="text" class="form-control cust-formcontrol" id="inputEmail4" placeholder="<?= $payment_type ?>"> -->
                                                            </div>
                                                            <div class="form-group col-md-12">
                                                                <label for="inputPassword4">Description : </label>
                                                                <p class="crd-style"><?= $forePayment['payment_des'] ?></p>
                                                                <!-- <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="<?= $forePayment['payment_des'] ?>"> -->
                                                            </div>
                                                            <div class="form-group col-md-12">
                                                                <label for="inputPassword4">Due Date : </label>
                                                                <p class="crd-style"><?= date('d-M-Y', strtotime($forePayment['due_date'])); ?></p>
                                                                <!-- <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="<?= date('d-M-Y', strtotime($forePayment['due_date'])); ?>"> -->
                                                            </div>
                                                            <?php
                                                            if ($forePayment['payment_status'] == 0) {
                                                                $paystatus1 = "Unpaid";
                                                            } else if ($forePayment['payment_status'] == 1) {
                                                                $paystatus1 = "Paid";
                                                            } else {
                                                                $paystatus1 = "Under Moderation";
                                                            }
                                                            ?>
                                                            <div class="form-group col-md-12">
                                                                <label for="inputPassword4">Status : </label>
                                                                <p class="crd-style"><?php echo $paystatus1; ?></p>
                                                                <!-- <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="<?php echo $paystatus1; ?>"> -->
                                                            </div>
                                                            <div class="form-group col-md-12">
                                                                <label for="inputPassword4">Original Amount : </label>
                                                                <p class="crd-style">₹ <?= Yii::$app->userdata->getformat((double) $forePayment['total_amount']) ?></p>
                                                                <!-- <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="₹ <?= Yii::$app->userdata->getformat((double) $forePayment['total_amount'] - (double) $forePayment['penalty_amount']) ?>"> -->
                                                            </div>
                                                            <div class="form-group col-md-12">
                                                                <label for="inputPassword4">Penalty Amount : </label>

                                                                <?php
                                                                $totalPenalty = Yii::$app->userdata->calculatePenalty($forePayment['id']);

                                                                if ($totalPenalty > 0) {
                                                                    ?>
                                                                    <p class="crd-style">₹ <?= Yii::$app->userdata->getformat($totalPenalty) ?></p>
                                                                    <!-- <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="₹ <?= Yii::$app->userdata->getformat($totalPenalty) ?>"> -->
                                                                <?php } else { ?>
                                                                    <p class="crd-style">₹ 0</p>
                                                                                                                <!-- <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="₹ 0"> -->
                                                                <?php } ?>
                                                            </div>
                                                            <div class="form-group col-md-12">
                                                                <label for="inputPassword4">Total Amount: </label>
                                                                <p class="crd-style">₹ <?= Yii::$app->userdata->getformat($forePayment['total_amount'] + $totalPenalty); ?></p>
                                                                <!-- <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="₹ <?= Yii::$app->userdata->getformat($forePayment['total_amount'] + $totalPenalty); ?>"> -->
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <div class="action-text">
                                                        <?php if ($forePayment['payment_status'] == '0') { ?>
                                                            <?php $form = ActiveForm::begin(['action' => 'javascript:void(0)', 'id' => 'payrentform-' . $i, 'options' => ['enctype' => 'multipart/form-data']]); ?>
                                                            <input type="hidden" name="TenantPayments[id]" value="<?php echo $forePayment['id'] ?>" /> 
                                                            <input type="hidden" name="TenantPayments[property_id]" value="<?php echo $forePayment['parent_id'] ?>" /> 
                                                            <input type="hidden" name="payment_action" value="TENANT_PAYMENT" /> 
                                                            <input type="hidden" name="neft-referer-<?= $i; ?>" id="neft-referer-<?= $i; ?>" value="" /> 
                                                            <input type="hidden" name="serial" value="<?= $i; ?>" />
                                                            <input type="hidden" name="app_redirect_url" value="<?php echo Url::home(true) ?>site/mypaymentsdue" />
                                                            <input type="hidden" name="TenantPayments[penalty_amount]" value="<?= $totalPenalty ?>" />
                                                            <!--<input type="hidden" name="TenantPayments[totalamount]" value="<?php echo ($forePayment['total_amount'] + $totalPenalty) ?>" />--> 
                                                            <input type="hidden" name="TenantPayments[totalamount]" value="<?php echo ($forePayment['total_amount']) ?>" /> 
                                                            <span class="paynw">Pay now</span> <button class= "btn btn-info" data-toggle="modal" data-target="#myModal-<?= $i; ?>" style="background-color: white;border: none;" onclick="checkpayment(<?= $i; ?>)">
                                                                <img src="<?php echo Url::home(true); ?>images/icons/ic-payment.svg" alt="Pay Now">    
                                                            </button>
                                                            <?php ActiveForm::end(); ?>
                                                            <?php
                                                        } else if ($forePayment['payment_status'] == '2') {
                                                            echo '<label>Under Moderation</label>';
                                                            ?>
                                                            <?php $form = ActiveForm::begin(['action' => 'unpaynow', 'id' => 'unpayrentform-' . $i, 'options' => ['enctype' => 'multipart/form-data']]); ?>
                                                            <input type="hidden" name="etc" value="<?php echo $forePayment['id'] ?>" />
                                                            <input type="hidden" name="serial" value="<?= $i; ?>" />
                                                            <input type="hidden" name="unpay" value="1" />
                                                            <input type="hidden" name="app_redirect_url" value="<?php echo Url::home(true) ?>site/mypaymentsdue" />
                                                            <button type="submit" class="btn btn-primary btn-xs" >Undo Payment</button>
                                                            <?php ActiveForm::end(); ?>
                                                            <?php
                                                        } else {
                                                            echo 'Paid';
                                                        }
                                                        ?>
                                                    </div>      
                                                </div>
                                            </div>
                                            <br />

                                            <div id="myModal-<?= $i; ?>" class="modal fade" role="dialog">
                                                <div class="modal-dialog" style="margin-left: 10%; margin-top: -5%;">
                                                    <!-- Modal content-->
                                                    <div class="modal-content" style="width: 78%;">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title">How would you like to make the payment?</h4>
                                                        </div>
                                                        <div class="modal-body" style="height: 550px;">

                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio"  onclick="hideNeftRefBox(<?= $i; ?>)" name="exampleRadios" id="pay-gate-<?= $i; ?>" value="option1" checked>
                                                                <label class="form-check-label" for="pay-gate-<?= $i; ?>">
                                                                    Payment Gateway
                                                                </label>
                                                            </div>

                                                            <div class="form-check" style="margin-top: 2%;">
                                                                <input onclick="showNeftRefBox(<?= $i; ?>)" class="form-check-input" type="radio" name="exampleRadios" id="neft-reference-input-<?= $i; ?>" value="">
                                                                <label class="form-check-label" for="neft-reference-input-<?= $i; ?>">
                                                                    NEFT
                                                                </label>
                                                            </div>

                                                            <div class="form-inline" id="ref-input-box-<?= $i; ?>" style="display: none;">
                                                                <div class="form-group mx-sm-3 mb-2">
                                                                    <label for="neft-reference-no-<?= $i; ?>" class="sr-only">Enter Reference Number</label>
                                                                    <input style="margin-left: 9%;" type="text" name="neft-reference-no-<?= $i; ?>" class="form-control" onkeyup="updateNeftRef(<?= $i; ?>)"  id="neft-reference-no-<?= $i; ?>" value="<?= $forePayment['neft_reference'] ?>" placeholder="NEFT Reference Number">
                                                                </div>
                                                            </div>
                                                            <br />
                                                            <center>
                                                                <button type="button" onclick="submitPaymentForm(<?= $i; ?>)" class="btn btn-primary mb-2">Pay Now</button>
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal" >Cancel</button>
                                                            </center>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>


                                            <div class="modal fade" id="myModal1-<?= $i; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                <div class="modal-dialog modalbg modalbgpopup" role="document">
                                                    <div class="modal-content">
                                                    </div>
                                                </div>


                                            </div>
                                            <?php
                                            $i++;
                                        }
                                    }
                                    ?> 
                                </div>
                            </section>
                            <!-- ./mobile view -->

                        </div>
                    </div>
                    </div>
                    </div>





                    <script>
                        function showpaytmnetbanking(serialNo) {
                            $('#Paytmnetbankingconformbtn-' + serialNo).css('display', 'block');
                        }
                        function hidepaytmnetbanking(serialNo) {
                            $('#Paytmnetbankingconformbtn-' + serialNo).css('display', 'none');
                        }
                        function updateNeftRef(serialNo) {
                            var neftNumber = $('#neft-reference-no-' + serialNo).val();
                            $('#neft-referer-' + serialNo).val($('#neft-reference-no-' + serialNo).val());
                            if (neftNumber != '') {
                                $('#displaybtn-' + serialNo).removeAttr('disabled');
                            } else {
                                $('#displaybtn-' + serialNo).attr('disabled', 'disabled');
                            }
                        }

                        function showNeftRefBox(serialNo) {
                            $('#ref-input-box-' + serialNo).css('display', 'block');
                            $('#ref-input-box2-' + serialNo).css('display', 'block');
                        }

                        function hideNeftRefBox(serialNo) {
                            $('#neft-referer-' + serialNo).val('');
                            $('#ref-input-box-' + serialNo).css('display', 'none');
                            $('#ref-input-box2-' + serialNo).css('display', 'none');
                        }

                        function showPaytmcharges(serialNo) {
                            $('#PaytmConformbtn-' + serialNo).css('display', 'block')
                            $('#paytmChargesInfo-' + serialNo).css('display', 'block');
                            $('#conveniance-charges-box').css('display', 'block');
                        }

                        function hidePaytmcharges(serialNo) {
                            $('#paytmChargesInfo-' + serialNo).val('');
                            $('#PaytmConformbtn-' + serialNo).val('');
                            $('#paytmChargesInfo-' + serialNo).css('display', 'none');
                            $('#PaytmConformbtn-' + serialNo).css('display', 'none');
                            $('#conveniance-charges-box').css('display', 'none');
                        }

                        function submitPaymentForm(serialNo) {
                            var paytmURL = "<?php echo Url::home(true) . 'paytm/payrent'; ?>";
                            var neftURL = "<?php echo Url::home(true) . 'site/payrentneft'; ?>";
                            if ($('#paytm-' + serialNo).is(':checked')) {
                                $('#payrentform-' + serialNo).attr('action', paytmURL);
                                $('#payrentform-' + serialNo).submit();
                            } else if ($('#neft-reference-input-' + serialNo).is(':checked')) {
                                if ($('#neft-reference-no-' + serialNo).val().trim() != '') {
                                    $('#payrentform-' + serialNo).attr('action', neftURL);
                                    $('#payrentform-' + serialNo).submit();
                                }
                            } else {
                                $('#payrentform-' + serialNo).submit();
                            }
                        }

                        function submitPaytmNetBankingForm(serialNo) {
                            var paytmNetBankingURL = "<?php echo Url::home(true) . 'paytm/payrentpaytmnetbanking'; ?>";
                            if ($('#paytmnetbanking-' + serialNo).is(':checked')) {
                                $('#payrentform-' + serialNo).attr('action', paytmNetBankingURL);
                                $('#payrentform-' + serialNo).submit();
                            }
                        }

                        function checkpayment(target)
                        {
                            document.getElementById("pay-gate-" + target).checked = true;
                        }

                    </script>

                    <style>
                        /* table {border: none;} */
                        /* th.td_style {
                            border-bottom: 1px solid white !important;
                        }
                        td.td_style {
                            border: 1px solid white;
                        } */
                        table {
                            font-family: arial, sans-serif;
                            border-collapse: collapse;
                            width: 100%;
                            border: 1px solid #dddddd; 
                        }

                        td, th {
                            /* border: 1px solid #dddddd; */
                            text-align: left;
                            padding: 8px;
                            height:64px;
                        }

                        .dclass {
                            background-color: #f9f9f9;
                        }
                        @media screen and (max-width: 767px){
                            #content{
                                width: 100%;
                            }
                            .cust-mdl{
                                margin-left: 10% !important;
                                margin-top: -155% !important;
                                width: 100% !important;
                            }
                        }

                        @media (min-width: 568px) and (max-width: 767.98px) { 
                            .cust-mdl{
                                margin-top: -40% !important;
                            }
                        }
                    </style>