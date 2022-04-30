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
    @media screen and (max-width: 767px){
        .crd-style{
            color: white;
            border-bottom: 1px solid white;
        }
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
        min-height: 240px;
    }

    .modal-body .form-check {
        margin-left: 3%;
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

            <h4 style="text-transform: uppercase;"> Payments - Forecast<h4>

                    </div>
                    </div>
                    <hr>
                    <div class="container">
                        <div class="col-md-12">
                            <!--p id="table_heading"><a href="<?php echo Url::home(true); ?>site/mypaymentsdue">Payment Due</a> / <a href="<?php echo Url::home(true); ?>site/mypaymentsforcast">Payment Forecast</a> / <a href="<?php echo Url::home(true); ?>site/mypayments">Payment History</a></p-->
                            <table class="table table-bordered tbl_style hide-mobile">
                                <thead>
                                    <tr>
                                        <th class="td_style">Payment Type</th>
                                        <th class="td_style">Description</th>
                                        <th class="td_style">Due Date</th>


                                        <th class="td_style">Total Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($forePayments) {
                                        // echo "<pre>";print_r($forePayments);echo "</pre>"; die;
                                        $i = 1;
                                        foreach ($forePayments as $key => $value) {
                                            if ($i % 2 == 0) {
                                                $dclass = "";
                                            } else {
                                                $dclass = "dclass";
                                            }
                                            ?>

                                            <?php
                                            // $agreementsone = Yii::$app->userdata->getAgreement($forePayment['property_id']);
                                            if ($value['payment_for'] == 'Rent') {
                                                $for = '0';
                                            } else {
                                                $for = '1';
                                            }
                                            ?>
                                            <tr class="<?php echo $dclass; ?>">
                                                <td width="20%" class="td_style"><?= $value['payment_for'] ?></td>
                                                <td width="40%" class="td_style"><?= $value['payment_des'] ?></td>
                                                <td width="25%" class="td_style"><?= date('d-M-Y', strtotime($value['due_date'])); ?></td>


                                                <td width="15%" class="td_style">&#8377 <?= Yii::$app->userdata->getformat($value['total_amount']) ?></td>
                                            </tr>
                                            <?php
                                            $i++;
                                        }
                                    } else {
                                        ?> 
                                        <tr> <td colspan="4">no record found</td> </tr>
                                        <?php
                                    }
                                    ?> 
                                </tbody>
                            </table>

                            <!-- mobile view -->
                            <section class="show-mobile mar1">
                                <div class="continer">
                                    <?php
                                    if ($forePayments) {
                                        // echo "<pre>";print_r($forePayments);echo "</pre>"; die;
                                        $i = 1;
                                        foreach ($forePayments as $key => $value) {
                                            if ($i % 2 == 0) {
                                                $dclass = "";
                                            } else {
                                                $dclass = "dclass";
                                            }
                                            ?>

                                            <?php
                                            // $agreementsone = Yii::$app->userdata->getAgreement($forePayment['property_id']);
                                            if ($value['payment_for'] == 'Rent') {
                                                $for = '0';
                                            } else {
                                                $for = '1';
                                            }
                                            ?>
                                            <div class="row card-bacolor">
                                                <div class="cust-card">
                                                    <form>
                                                        <div class="form-row cust-form">
                                                            <div class="form-group col-md-12">
                                                                <label for="inputEmail4">Payment Type : </label>
                                                                <p class="crd-style"><?= $value['payment_for'] ?></p>
                                                                <!-- <input type="text" class="form-control cust-formcontrol" id="inputEmail4" placeholder="<?= $value['payment_for'] ?>"> -->
                                                            </div>
                                                            <div class="form-group col-md-12">
                                                                <label for="inputPassword4">Description : </label>
                                                                <p class="crd-style"><?= $value['payment_des'] ?></p>
                                                                <!-- <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="<?= $value['payment_des'] ?>"> -->
                                                            </div>
                                                            <div class="form-group col-md-12">
                                                                <label for="inputPassword4">Due Date : </label>
                                                                <p class="crd-style"><?= date('d-M-Y', strtotime($value['due_date'])); ?></p>
                                                                <!-- <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="<?= date('d-M-Y', strtotime($value['due_date'])); ?>"> -->
                                                            </div>
                                                            <div class="form-group col-md-12">
                                                                <label for="inputPassword4">Total Amount : </label>
                                                                <p class="crd-style">₹ <?= Yii::$app->userdata->getformat($value['total_amount']) ?></p>
                                                                <!-- <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="₹ <?= Yii::$app->userdata->getformat($value['total_amount']) ?>"> -->
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div> 
                                            <br />
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