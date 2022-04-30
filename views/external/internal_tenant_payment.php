<?php
$this->title = 'PMS - Payments';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\grid\GridView;

$data = [];
$showApproveBtn = true;
?>
<style>
    input#upload-income {
        padding: 3px 5px 4px 5px;
        border: 1px solid lightgrey;
    }

    input#search-property-income {
        width: 160px;
        margin-right: 6px;
        height: 28px;
    }

    input:-moz-read-only {
        background-color: lightgrey;
    }

    input:read-only {
        background-color: lightgrey;
    }

    .ui-autocomplete {
        position:relative;
        cursor:default;
        z-index:9999 !important;
    }

    .toggle-side-nav {
        display: none;
    }

    .toggle-page-wrapper {
        margin: -27px 0 0 0px !important;
    }

    .approved-row {
        background-color: #03AC13 !important;
    }

    #summarized-income {
        padding: 10px 15px;
        border: 1px solid black;
        display: none;
    }
</style>
<div id="page-wrapper">
    <div class="row">
        <div class="col-12">
            <h4><b>Tenant Payments</b></h4>
        </div>
    </div>
    <p></p>
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="row">
                <div class="col-lg-6 col-sm-6">
                    <?php $form = ActiveForm::begin(['id' => 'tenant-payment-record-form', 'options' => ['name' => 'property-income-form', 'class' => 'form-inline', 'enctype' => 'multipart/form-data']]); ?>
                    <label class="control-label" for="search-property-income">Select Tenant &nbsp;&nbsp;</label>
                    <div class="input-group">
                        <input value="<?= (!empty($subData['tenant_list'])) ? $subData['tenant_list'] : '' ?>" type="text" id="tenant-list-inner" class="form-control ui-autocomplete-input" name="tenant_list" placeholder="Search Tenant*" autocomplete="off">
                    </div>
                    <input type="hidden" id="tenant-id-inner" name="tenant_id" value="<?= (!empty($subData['tenant_id'])) ? $subData['tenant_id'] : '' ?>">
                    <div class="input-group">
                        <select <?= (!empty($subData['record_type'])) ? 'style="display: block;"' : 'style="display: none;"' ?> id="record_type" name="record_type" class="form-control">
                            <option value="">Select Record Type</option>
                            <option <?= (!empty($subData['record_type']) && $subData['record_type'] == 1) ? 'selected' : '' ?> value="1">Due</option>
                            <option <?= (!empty($subData['record_type']) && $subData['record_type'] == 2) ? 'selected' : '' ?> value="2">Forecast</option>
                            <option <?= (!empty($subData['record_type']) && $subData['record_type'] == 3) ? 'selected' : '' ?> value="3">History</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <button <?= (!empty($subData['record_type'])) ? 'style="display: block;"' : 'style="display: none;"' ?> type="submit" style="width: 95%;" id="search-tenant-record" class="btn-2 btn-primary">Search</button>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
                <div class="col-lg-1 col-sm-1">

                </div>
            </div>
        </div>
        <p></p>
        <?php if (!empty($subData)) { ?>
            <div class="col-lg-12 col-sm-12 adminusermanag" style="overflow-x: auto;margin-top: 10px;">
                <div class="">
                    <?php if ($subData['record_type'] == 1) { ?>
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
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($paymentData) {
                                    $pastPayment = $paymentData;
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
                                                    <i aria-hidden="true" class="" style="color:red;">&nbsp;&nbsp;&#8377 <?= Yii::$app->userdata->getformat($totalPenalty) ?></i>
                                                <?php } else { ?>
                                                    &nbsp;&nbsp;&nbsp;&nbsp; &#8377 0
                                                <?php } ?>
                                            </td>
                                            <td width="10%">&#8377 <?= Yii::$app->userdata->getformat($forePayment['total_amount'] + $totalPenalty); ?></td>
                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                } else {
                                    ?> 
                                    <tr> <td colspan="7">No payment due</td> </tr>
                                    <?php
                                }
                                ?> 
                            </tbody>
                        </table>
                    <?php } ?>
                    <?php if ($subData['record_type'] == 2) { ?>
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
                                if ($paymentData) {
                                    $forePayments = $paymentData;
                                    $i = 1;
                                    foreach ($forePayments as $key => $value) {
                                        if ($i % 2 == 0) {
                                            $dclass = "";
                                        } else {
                                            $dclass = "dclass";
                                        }
                                        ?>

                                        <?php
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
                                    <tr> <td colspan="4">No record found</td></tr>
                                    <?php
                                }
                                ?> 
                            </tbody>
                        </table>
                    <?php } ?>
                    <?php if ($subData['record_type'] == 3) { ?>
                        <table class="table table-bordered">
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
                                if ($paymentData) {
                                    $pastPayment = $paymentData;
                                    $i = 1;
                                    foreach ($pastPayment as $key => $wish) {
                                        ?>
                                        <tr class="">
                                            <td width="10%"><?= $wish['payment_for'] ?></td>
                                            <td width="20%"><?= $wish['payment_des'] ?></td>
                                            <td width="15%"><?= Date('d-M-Y', strtotime($wish['payment_date'])); ?></td>
                                            <td width="15%">&#8377 <?= Yii::$app->userdata->getformat((int) $wish['total_amount'] - (int) $wish['penalty_amount']); ?></td>
                                            <td width="15%">
                                                <?php
                                                $currentDate = Date('Y-m-d');
                                                $totalPenalty = $wish['penalty_amount'];
                                                $penalty = 0;
                                                $dateDiff = date_diff(date_create($wish['due_date']), date_create($currentDate))->days;
//                                        if ($dateDiff > 0 && $wish['due_date'] < $wish['payment_date']) {
//                                            $penalty = round(($dateDiff / date("z", (mktime(0, 0, 0, 12, 31, date('Y'))) + 1)) * ($wish['late_penalty_percent'] / 100) * $wish['total_amount']);
//                                            $totalPenalty = ( $penalty < $wish['min_penalty'] ) ? $wish['min_penalty'] : $penalty;
//                                        }

                                                if ($totalPenalty > 0) {
                                                    ?>
                                                    <i aria-hidden="true" style="color: red;">&nbsp;&nbsp;&#8377 <?= Yii::$app->userdata->getformat($totalPenalty) ?></i>
                                                <?php } else { ?>
                                                    &nbsp;&nbsp;&nbsp;&nbsp; &#8377 0
                                                <?php } ?>
                                            </td>
                                            <td width="15%">&#8377 <?= Yii::$app->userdata->getformat($wish['amount_paid']); ?></td>
                                            <td width="15%"><a style="padding: 3px !important;" href="<?= Url::home(TRUE) ?>site/createpaymentpdf?id=<?= $wish['id'] ?>&for=0" class="btn btn-lg-lg pull-center"><i style="font-size: 20px;" aria-hidden="true" class="glyphicon glyphicon-download"></i></a></td>
                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                } else {
                                    ?>
                                    <tr> <td colspan="8">No record found</td> </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div> 
</div>
<script>
    $(document).ready(function () {
        $('#record_type').change(function () {
            var selectVal = $(this).val();
            if (selectVal != '') {
                $('#search-tenant-record').css('display', 'block');
            } else {
                $('#search-tenant-record').css('display', 'none');
            }
        });
    });
</script>