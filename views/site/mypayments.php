<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Payments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="page-wrapper" class= "autoheight">
    <div class="row">
        <div class="col-lg-12 wallettop wallettop">

            <?php if (Yii::$app->session->hasFlash('success')) { ?>
                <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                    <?= Yii::$app->session->getFlash('success'); ?>
                </div>   
            <?php } ?>
            <div class="col-lg-4 text-right"><a href="<?= Url::home(true) ?>site/addmoney">Add money To wallet</a></div>

            <p class="transactiontext">Payment History <span class="pull-right">  <b>My Money: ₹ <?= $walletAmount ? $walletAmount->amount : 0 ?> </b>  </span></p>


            <table id="favlist" class="table table-bordered mt" cellpadding="1"> 
                <thead>
                    <tr> 
                        <th>Date</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Payment Mode</th>
                        <th>Receipt</th>
                        <th>Penalty Amount</th>
                        <th>Penalty Calculator</th>
                    </tr>
                </thead> 
                <tbody>

                    <?php
                    if ($tenantPayments) {

                        $i = 1;
                        foreach ($tenantPayments as $key => $wish) {
                            ?>
                            <tr> 
                                <td><?= date('M j,Y', strtotime($wish->create_time)); ?></td>
                                <td><?php
                                    if ($wish->payment_type == '2') {
                                        echo "Rent";
                                    } elseif ($wish->payment_type == '1') {
                                        echo "Adding money to wallet";
                                    } elseif ($wish->payment_type == '3') {
                                        echo "Refund";
                                    } else {
                                        echo 'Depost Token Amount';
                                    }
                                    ?></td> 
                                <td><?= $wish->payment_amount; ?></td>
                                <td><?php
                                    if ($wish->payment_mode == '0') {
                                        echo "Offline";
                                    } elseif ($wish->payment_mode == '1') {
                                        echo "OnLine";
                                    } elseif ($wish->payment_mode == '2') {
                                        echo "Wallet";
                                    }
                                    ?></td>
                                <td></td> 
                                <td><?= $wish->penalty_amount; ?></td>
                                <td></td>
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

            <br /> <br />

            <p class="transactiontext">Payment Due</p>


            <table id="favlist" class="table table-bordered mt" cellpadding="1"> 
                <thead>
                    <tr> 
                        <th>Type</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Pay now</th>
                        <th>Remarks</th>
                        <th>Penalty Amount</th>
                        <th>Penalty Calculator</th>
                    </tr>
                </thead> 
                <tbody>

                    <?php
                    if ($amount > 0) {
                        ?> 
                        <tr> 
                            <td>Rent</td> 
                            <td>Rent for <?= date('F'); ?>  Month is pending </td>
                            <td><?= $amount; ?></td>
                            <td> due </td>
                            <td></td>
                            <td> Pending </td> 
                            <td><?= $penaltyAmount; ?></td>
                            <td></td>
                        </tr>
                        <?php
                    } else {
                        ?> 
                        <tr> <td colspan="4">no record found</td> </tr>
                        <?php
                    }
                    ?> 
                </tbody> 
            </table>

            <br /> <br />

            <p class="transactiontext">Payment Forcast</p>


            <table id="favlist" class="table table-bordered mt" cellpadding="1"> 
                <thead>
                    <tr> 
                        <th>Due Date</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Pay now</th>
                        <th>Remarks</th>
                        <th>Status</th>

                    </tr>
                </thead> 
                <tbody>

                    <?php
                    if ($agreements) {
                        ?>

                        <tr> 
                            <td><?= $agreements->rent_date . ' ' . date('F', strtotime(date('d-M-Y', strtotime('+1 month')))); ?></td>
                            <td>Rent</td> 
                            <td> Rent- <?= date('M-Y', strtotime(date('d-M-Y', strtotime('+1 month')))); ?> </td>
                            <td><?= $agreements->rent + $agreements->manteinace; ?></td>
                            <td></td>
                            <td></td>
                            <td>Pending</td> 

                        </tr>
                        <?php
                    } else {
                        ?> 
                        <tr> <td colspan="4">no record found</td> </tr>
                        <?php
                    }
                    ?> 
                </tbody> 
            </table>

        </div>

        <!-- /.col-lg-12 -->
    </div>

    <!-- /.col-lg-12 -->
</div>
</div>

