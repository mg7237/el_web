<?php
$this->title = 'PMS- Payment Receipt';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\grid\GridView;

/* echo "<pre>";
  print_r($model);
  die; */

if (isset($_GET['LeadsTenant']['sort'])) {
    $sort = $_GET['LeadsTenant']['sort'];
} else {
    $sort = '';
}
?>
<style>
    .filters{
        display:none ;	
    }

    td {
        vertical-align: middle !important;
    }
</style>
<div id="page-wrapper">
    <div class="row">

        <div class="col-lg-12">
            <?php if (Yii::$app->session->hasFlash('success')) { ?>
                <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                    <?= Yii::$app->session->getFlash('success'); ?>
                </div>   
            <?php } ?>	

            <div class="col-lg-12 selectleft">
                <form class="navbar-form navbar-search" action="" method="GET" role="search">
                    <div class="input-group">
                        <?php
                        $search = '';
                        if (isset($_REQUEST['search'])) {
                            $search = $_REQUEST['search'];
                        }
                        ?>

                        <input type="text" name="search" class="form-control" value="<?= $search; ?>" placeholder="Search By Property Name, Tenant Name and Description">
                        <div class="input-group-btn" style="width: 50px; vertical-align: top;">
                            <button type="submit" style = "height: 32px; width : 32px; align-vertical: top;" class="btn btn-search btn-danger selecttext"> <span class="glyphicon glyphicon-search"></span> <span class="label-icon"></span> </button>
                            
                                                   
                        </div>
                     <div style="margin: auto;width: 400px; font-size: 12; display: table-cell; vertical-align: bottom;" >Note: Enter "ALL" to get unpaid dues for all tenants.</div>
                    </div>

                </form>
            </div>
        </div>
        <div class="col-lg-12">



            <div id="w0" class="grid-view">
                <table class="table table-bordered tableheadingrow">
                    <thead>
                        <tr>
                            <th style="width: 10%;">Customer Name</th>
                            <th style="width: 10%;">Property Name</th>
                            <th style="width: 10%;">Description</th>
                            <th style="width: 7%;">Due Date</th>
                            <th style="width: 7%;">Payable Amount</th>
                            <th style="width: 5%;">Penalty Amount</th>
                            <th style="width: 9%;">Total Amount</th>
                            <th style="width: 8%;">Payment Mode</th>
                            <th style="width: 8%;">Payment Date</th>
                            <th style="width: ">Amount Paid</th>
                            <th style="width: ">NEFT Reference</th>
                            <th style="width: 13%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $paymentModes = \app\models\PaymentModeType::find()->all();
                        ?>
                        <?php foreach ($rowSet as $key => $row) { ?>
                                <!--<tr style="cursor: pointer; height: 10%" onclick="alert('<?php echo $key; ?>')">-->
                            <tr style="cursor: pointer; height: 10%" id="row-<?= $key; ?>">
                                <td>
                                    <?= Yii::$app->userdata->getFullNameById($row['tenant_id']); ?>
                                </td>
                                <td>
                                    <?= Yii::$app->userdata->getPropertyNameById($row['parent_id']); ?>
                                </td>
                                <td>
                                    <?= $row['payment_des']; ?>
                                </td>
                                <td>
                                    <?= $row['due_date']; ?>
                                </td>
                                <td>
                                    <?= round(($row['original_amount'] + $row['maintenance']), 2); ?>
                                    <input id="payable_amount-<?= $key; ?>" type="hidden" name="payable_amount-<?= $key; ?>" value="<?= round(($row['original_amount'] + $row['maintenance']), 2); ?>" />
                                </td>
                                <td>
                                    <?php
                                    $currentDate = Date('Y-m-d');
                                    $totalPenalty = 0;
                                    $penalty = 0;
                                    $dateDiff = date_diff(date_create($row['due_date']), date_create($currentDate))->days + 1;
                                    if (!empty($row['penalty_amount']) && $row['penalty_amount'] > 0) {
                                        $totalPenalty = $row['penalty_amount'];
                                    } else {
                                        if (empty($row['payment_date']) && ($dateDiff > 0 && $row['due_date'] < date('Y-m-d'))) {
                                            $penalty = round(($dateDiff / date("z", (mktime(0, 0, 0, 12, 31, date('Y'))) + 1)) * ($row['late_penalty_percent'] / 100) * $row['total_amount']);
                                            $totalPenalty = ( $penalty < $row['min_penalty'] ) ? $row['min_penalty'] : $penalty;
                                        }
                                    }
                                    ?>
                                    <input class="" style="width: 100%;" id="penalty_amount-<?= $key; ?>" disabled type="text" name="penalty_amount-<?= $key; ?>" value="<?= (empty($row['payment_date'])) ? $totalPenalty : $row['penalty_amount']; ?>" />
                                </td>
                                <td>
                                    <span id="net_amount_text-<?= $key; ?>"><?php echo ($totalPenalty > 0) ? (round($row['total_amount'], 2) + $totalPenalty) : round($row['total_amount'], 2); ?></span>
                                    <input id="net_amount-<?= $key; ?>" type="hidden" name="net_amount-<?= $key; ?>" value="<?= $row['total_amount'] ?>" />
                                </td>
                                <td>
                                    <select disabled="" id="payment_mode-<?= $key; ?>" onchange="doChangePaymentMode(<?= $key; ?>);">
                                    <!--<select disabled="" id="payment_mode-<?= $key; ?>" class="">-->
                                        <?php foreach ($paymentModes as $paymentMode) { ?>
                                            <?php if ($paymentMode->id != 2 && $paymentMode->id != 3) { ?>
                                                <option <?php if ($paymentMode->name == Yii::$app->userdata->getPaymentModeType($row['payment_mode'])) {/* echo 'selected'; */
                                    }
                                                ?> value="<?= $paymentMode->id ?>"><?= $paymentMode->name ?></option>
                                                <?php } ?>
    <?php } ?>
                                    </select>
                                </td>
                                <td>
                                    <input data-row-id="<?= $row['id']; ?>" data-key="<?= $key; ?>" class="datepicker" style="width: 100%;" id="payment_date-<?= $key; ?>" disabled type="text" name="payment_date-<?= $key; ?>" value="<?= (empty($row['payment_date'])) ? date('d-M-Y') : date('d-M-Y', strtotime($row['payment_date'])); ?>" />
                                </td>
                                <td>
                                    <input autocomplete="off" style="width: 100%;" id="amount_paid-<?= $key; ?>" disabled type="text" name="amount_paid-<?= $key; ?>" value="<?= round($row['amount_paid'], 2) ?>" />
                                </td>
                                <td>
                                    <input autocomplete="off" style="width: 100%;" id="neft_reference-<?= $key; ?>" disabled type="text" name="neft_reference-<?= $key; ?>" value="<?= (!empty($row['neft_reference'])) ? $row['neft_reference'] : 'N/A'; ?>" />
                                </td>

                                <td>
                                    <button id="edit_moderation-<?= $key; ?>" onclick="doModeration(<?= $key; ?>)">Edit</button>
                                    <button class="btn btn-primary btn-xs" id="save_moderation-<?= $key; ?>" onclick="saveModeration(<?= $key; ?>, <?= $row['id']; ?>)" style="display: none; float: left; margin-right: 3px;">Confirm</button>
    <?php $form = ActiveForm::begin(['action' => 'unpaynow', 'id' => 'unpayform-' . $key]); ?>
                                    <input type="hidden" name="etc" value="<?php echo $row['id'] ?>" />
                                    <input type="hidden" name="serial" value="<?= $key; ?>" />
                                    <input type="hidden" name="unpay" value="1" />
                                    <?php if (!empty(Yii::$app->request->get('search'))) { ?>
                                        <input type="hidden" name="app_redirect_url" value="<?php echo Url::home(true) ?>external/paymentreceipt?search=<?= Yii::$app->request->get('search') ?>" />
                                    <?php } else { ?>
                                        <input type="hidden" name="app_redirect_url" value="<?php echo Url::home(true) ?>external/paymentreceipt" />
                                    <?php } ?>
                                    <?php if (!empty($row['neft_reference'])) { ?>
                                        <button id="unpay-<?= $key; ?>" onclick="unPay(<?= $key; ?>, <?= $row['id']; ?>)" style="display: none;" type="submit" class="btn btn-primary btn-xs" >Decline</button>
                                    <?php } ?>
    <?php ActiveForm::end(); ?>
                                </td>
                            </tr>
                        <?php } ?>
<?php if (count($rowSet) == 0) { ?>
                            <tr>
                                <td colspan="12">
                                    <div class="empty">No results found.</div>
                                </td>
                            </tr>
<?php } ?>
                    </tbody>
                </table>
            </div>



        </div>

    </div>
</div>

<div class="modal fade" id="myModal2" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">


        </div>

    </div>
</div>

<script>

    $(function () {
        var today = new Date();
        var day = today.getDate() + 4;
        var month = today.getMonth() + 1;
        var year = today.getYear() - 100;

        $(".datepicker").datepicker({
            //minDate: new Date(),
            maxDate: today,
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: 'dd-M-yy',
            onSelect: function () {
                var serial = $(this).attr('data-key');
                var tpId = $(this).attr('data-row-id');
                var _csrf = "<?= Yii::$app->request->getCsrfToken() ?>";
                var penaltyAmount = $('#penalty_amount-' + serial).val();
                var paymentDate = $('#payment_date-' + serial).val();
                var postData = {'_csrf': _csrf, 'id': tpId, 'penalty_amount': penaltyAmount, 'payment_date': paymentDate};
                $.ajax({
                    url: 'alterpenaltyamount',
                    type: 'post',
                    data: postData,
                    success: function (res) {
                        $('#penalty_amount-' + serial).val(res);
                        var netAmount = $('#net_amount-' + serial).val();
                        var totalAmount = parseInt(netAmount) + parseInt(res);
                        $('#net_amount_text-' + serial).text(totalAmount);
                    },
                    error: function (data) {
                        alert('Something went wrong, please try after some time.');
                    }
                });
            }
        });
    });

    function doChangePaymentMode(eleId) {
        var mode = $('#payment_mode-' + eleId).val();
        if (mode != 1) {
            $('#neft_reference-' + eleId).attr('disabled', 'disabled');
            $('#neft_reference-' + eleId).val('N/A');
        } else {
            $('#neft_reference-' + eleId).removeAttr('disabled');
            $('#neft_reference-' + eleId).val('');
        }
    }

    function doModeration(eleId) {
        $('#payment_mode-' + eleId).removeAttr('disabled');
        $('#amount_paid-' + eleId).removeAttr('disabled');
        $('#neft_reference-' + eleId).removeAttr('disabled');
        if ($('#neft_reference-' + eleId).val() == '' || $('#neft_reference-' + eleId).val() == 'N/A') {
            $('#neft_reference-' + eleId).val('')
        }
        $('#payment_date-' + eleId).removeAttr('disabled');
        //$('#penalty_amount-'+eleId).removeAttr('disabled');
        $('#edit_moderation-' + eleId).css('display', 'none');
        $('#save_moderation-' + eleId).css('display', 'block');
        $('#unpay-' + eleId).css('display', 'block');
    }

    function saveModeration(eleId, tpId) {
        var penaltyAmount = $('#penalty_amount-' + eleId).val(); // Math.round()
        var payableAmount = $('#payable_amount-' + eleId).val();
        var netAmount = $('#net_amount-' + eleId).val();
        var totalAmount = Math.round((parseInt(netAmount) + parseInt(penaltyAmount)));
        var paymentMode = $('#payment_mode-' + eleId).val();
        var paymentDate = $('#payment_date-' + eleId).val();
        var amountPaid = Math.round($('#amount_paid-' + eleId).val());
        var neftRefer = $('#neft_reference-' + eleId).val();
        var _csrf = "<?= Yii::$app->request->getCsrfToken() ?>";
        var postData = {'_csrf': _csrf, 'payable_amount': payableAmount, 'penalty_amount': penaltyAmount, 'id': tpId, 'payment_mode': paymentMode, 'payment_date': paymentDate, 'amount_paid': amountPaid, 'neft_reference': neftRefer};
        var valError = false;
        if (penaltyAmount == '') {
            alert('Please enter a valid penalty amount');
            valError = true;
        } else if (amountPaid == 0) {
            alert('The amount paid must be greater than or equal to Payable Amount and less or equal to Total Amount');
            valError = true;
        } else if (amountPaid < payableAmount) {
            alert('The amount paid must be greater than or equal to Payable Amount');
            valError = true;
        } else if (amountPaid > totalAmount) {
            alert('The amount paid must not be greater than total amount');
            valError = true;
        } else if ((neftRefer == '' || neftRefer == 'N/A') && paymentMode == 1) {
            alert('Please enter a valid NEFT number');
            valError = true;
        }
        if (!valError) {
            if (!confirm('Are you sure you want confirm this payment?')) {
                return false;
            }
            $('#payment_mode-' + eleId).attr('disabled', 'disabled');
            $('#amount_paid-' + eleId).attr('disabled', 'disabled');
            $('#neft_reference-' + eleId).attr('disabled', 'disabled');
            $('#payment_date-' + eleId).attr('disabled', 'disabled');
            $('#penalty_amount-' + eleId).attr('disabled', 'disabled');
            $('#edit_moderation-' + eleId).css('display', 'block');
            $('#save_moderation-' + eleId).css('display', 'none');
            $('#unpay-' + eleId).css('display', 'none');
            $.ajax({
                url: 'savemoderation',
                type: 'post',
                data: postData,
                success: function (res) {
                    alert('Payment Confirmed');
                    $('#row-' + eleId).remove();
                },
                error: function (data) {
                    alert('Something went wrong, please try after some time.');
                }
            });
        }
    }
</script>