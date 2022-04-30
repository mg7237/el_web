<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\db\Query;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Payments';
$this->params['breadcrumbs'][] = $this->title;
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
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
        -moz-box-shadow: 0px 2px 2px black,;
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
        margin: 100px auto;
        width: 40%;
        height: auto;
        display: table !important;
        min-height: auto !important;
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
</style>
<div id="content">
    <button type="button" id="sidebarCollapse" class="btn btn-info navbar-btn">
        <i class="glyphicon glyphicon-align-left"></i>
        <span></span>
    </button>
    <div class="container" style="margin-top: -42px;">
        <div class="col-md-4">

            <h4 style="text-transform: uppercase;"> Payments - History<h4>

                    </div>
                    </div>
                    <hr>
                    <div class="container">
                        <div class="col-md-12">
                            <!--p id="table_heading"><a href="<?php echo Url::home(true); ?>site/mypaymentsdue">Payment Due</a> / <a href="<?php echo Url::home(true); ?>site/mypaymentsforcast">Payment Forecast</a> / <a href="<?php echo Url::home(true); ?>site/mypayments">Payment History</a></p-->
                            <table class="table table-bordered tbl_style hide-mobile">
                                <thead>
                                    <tr>
                                        <th>Payment Type</th>
                                        <th>Description</th>
                                        <th>Payment Date</th>
                                        <th>Original Amount</th>
                                        <th>Penalty Amount</th>
                                        <th>Amount Received</th>
                                        <th>Receipt</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($pastPayment) {

                                        $i = 1;
                                        foreach ($pastPayment as $key => $wish) {
                                            if ($i % 2 != 0) {
                                                $dclass = "dclass";
                                            } else {
                                                $dclass = "";
                                            }
                                            ?>
                                            <tr class="<?php echo $dclass; ?>">
                                                <td width="10%"><?= $wish['payment_for'] ?></td>
                                                <td width="20%"><?= $wish['payment_des'] ?></td>
                                                <td width="15%"><?= Date('d-M-Y', strtotime($wish['payment_date'])); ?></td>
                                                <td width="15%">&#8377 <?= Yii::$app->userdata->getformat((int) $wish['total_amount'] - (int) $wish['penalty_amount']); ?></td>
                                                <td width="15%">
                                                    <?php
                                                    $currentDate = Date('Y-m-d');
                                                    // MG: Commented as penalty calculation should not be done for payment history UI
//                                    $totalPenalty = 0;
//                                    $penalty = 0;
//                                    $dateDiff = date_diff(date_create($wish['due_date']), date_create($currentDate))->days;
//                                    if ($dateDiff > 0 && $wish['due_date'] < $wish['payment_date']) {
//                                        $penalty = round(($dateDiff / date("z", (mktime(0, 0, 0, 12, 31, date('Y'))) + 1)) * ($wish['late_penalty_percent'] / 100) * $wish['total_amount']);
//                                        $totalPenalty = ( $penalty < $wish['min_penalty'] ) ? $wish['min_penalty'] : $penalty;
//                                    }
                                                    $totalPenalty = $wish['penalty_amount'];
                                                    if ($totalPenalty > 0) {
                                                        ?>
                                                        <a href="penaltycalculate?id=<?= $wish['id'] ?>&for=<?= 2 ?>" type="button" class="btn btn-lg-lg pull-center" data-toggle="modal" data-target="#myModal"><i aria-hidden="true" class="fa fa-calculator">&nbsp;&nbsp;&#8377 <?= Yii::$app->userdata->getformat($totalPenalty) ?></i></a>
                                                    <?php } else { ?>
                                                        &nbsp;&nbsp;&nbsp;&nbsp; &#8377 0
                                                    <?php } ?>
                                                </td>
                                                <!--<td width="15%">Rs. <?= ($wish['total_amount'] + $totalPenalty); ?></td>-->
                                                <td width="15%">&#8377 <?= Yii::$app->userdata->getformat($wish['amount_paid']); ?></td>
                                                <td width="15%"><a href="createpaymentpdf?id=<?= $wish['id'] ?>&for=0" class="btn btn-lg-lg pull-center"><i aria-hidden="true" class="fa fa-file-pdf-o"></i></a></td>
                                            </tr>
                                            <?php
                                            $i++;
                                        }
                                    } else {
                                        ?>
                                        <tr> <td colspan="8">no record found</td> </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>

                            <!-- mobile view -->
                            <section class="show-mobile mar1">
                                <div class="continer">
                                    <?php
                                    if ($pastPayment) {

                                        $i = 1;
                                        foreach ($pastPayment as $key => $wish) {
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
                                                                <p class="crd-style"><?= $wish['payment_for'] ?></p>
                                                                <!-- <input type="text" class="form-control cust-formcontrol" id="inputEmail4" placeholder="<?= $wish['payment_for'] ?>"> -->
                                                            </div>
                                                            <div class="form-group col-md-12">
                                                                <label for="inputPassword4">Description : </label>
                                                                <p class="crd-style"><?= $wish['payment_des'] ?></p>
                                                                <!-- <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="<?= $wish['payment_des'] ?>"> -->
                                                            </div>
                                                            <div class="form-group col-md-12">
                                                                <label for="inputPassword4">Due Date : </label>
                                                                <p class="crd-style"><?= Date('d-M-Y', strtotime($wish['payment_date'])); ?></p>
                                                                <!-- <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="<?= Date('d-M-Y', strtotime($wish['payment_date'])); ?>"> -->
                                                            </div>

                                                            <div class="form-group col-md-12">
                                                                <label for="inputPassword4">Original Amount : </label>
                                                                <p class="crd-style">₹ <?= Yii::$app->userdata->getformat((int) $wish['total_amount'] - (int) $wish['penalty_amount']); ?></p>
                                                                <!-- <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="₹ <?= Yii::$app->userdata->getformat((int) $wish['total_amount'] - (int) $wish['penalty_amount']); ?>"> -->
                                                            </div>
                                                            <div class="form-group col-md-12">
                                                                <label for="inputPassword4">Penalty Amount : </label>
                                                                <?php
                                                                $currentDate = Date('Y-m-d');
                                                                $totalPenalty = 0;
                                                                $penalty = 0;
                                                                $dateDiff = date_diff(date_create($wish['due_date']), date_create($currentDate))->days;
                                                                if ($dateDiff > 0 && $wish['due_date'] < $wish['payment_date']) {
                                                                    $penalty = round(($dateDiff / date("z", (mktime(0, 0, 0, 12, 31, date('Y'))) + 1)) * ($wish['late_penalty_percent'] / 100) * $wish['total_amount']);
                                                                    $totalPenalty = ( $penalty < $wish['min_penalty'] ) ? $wish['min_penalty'] : $penalty;
                                                                }

                                                                if ($totalPenalty > 0) {
                                                                    ?>
                                                                    <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="₹ <?= Yii::$app->userdata->getformat($totalPenalty) ?>">
                                                                <?php } else { ?>
                                                                    <p class="crd-style">₹ 0</p>
                                                                    <!-- <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="₹ 0"> -->
                                                                <?php } ?>
                                                            </div>
                                                            <div class="form-group col-md-12">
                                                                <label for="inputPassword4">Amount Received : </label>
                                                                <p class="crd-style">₹ <?= Yii::$app->userdata->getformat($wish['amount_paid']); ?></p>
                                                                <!-- <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="₹ <?= Yii::$app->userdata->getformat($wish['amount_paid']); ?>"> -->
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <div class="action-text">
                                                        <h5>Receipt</h5>
                                                    </div>
                                                    <div class="action">
                                                        <div class="action-img">
                                                            <a href="createpaymentpdf?id=<?= $wish['id'] ?>&for=0" class="btn btn-lg-lg pull-center"><i aria-hidden="true" class="fa fa-file-pdf-o"></i></a>
                                                        </div>
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
                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog modalbg modalbgpopup" role="document">
                            <div class="modal-content">
                            </div>
                        </div>


                    </div>
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
                        }

                    </style>