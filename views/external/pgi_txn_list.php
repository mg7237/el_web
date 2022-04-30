<?php
$this->title = 'PMS- Payment Receipt';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\grid\GridView;

$response_time_from = '';
$response_time_to = '';
$amount_from = '';
$amount_to = '';
$sort = '';
$search = '';

if (isset($_REQUEST['response_time_from'])) {
    $response_time_from = $_REQUEST['response_time_from'];
}

if (isset($_REQUEST['response_time_to'])) {
    $response_time_to = $_REQUEST['response_time_to'];
}

if (isset($_REQUEST['amount_from'])) {
    $amount_from = $_REQUEST['amount_from'];
}

if (isset($_REQUEST['amount_to'])) {
    $amount_to = $_REQUEST['amount_to'];
}

if (isset($_REQUEST['sort_by'])) {
    $sort = $_REQUEST['sort_by'];
}

if (isset($_REQUEST['search'])) {
    $search = $_REQUEST['search'];
}
?>
<style>
    .filters{
        display:none ;	
    }

    td {
        vertical-align: middle !important;
    }

    .input-group-addon, .input-group-btn {
        vertical-align: bottom;
    }
</style>
<div id="page-wrapper" style="<?php echo (Yii::$app->userdata->usertype == 8) ? 'margin: -26px 0 0 0px;' : ''; ?>">
    <div class="row">
        <div class="col-lg-12">
            <?php if (Yii::$app->session->hasFlash('success')) { ?>
                <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                    <?= Yii::$app->session->getFlash('success'); ?>
                </div>   
            <?php } ?>	
        </div>
    </div>
    <div class="row" style="margin-top: 1%; margin-bottom: 1%;">
        <form class="navbar-form navbar-search" action="" method="GET">
            <div class="col-lg-4">
                <label>Search:</label>
                <div class="input-group">
                    <input type="text" name="search" class="form-control" value="<?= $search; ?>" placeholder="Search By Customer ID, Txn Reference, Bank Reference, Bank ID and Auth Status">
                </div>
            </div>

            <div class="col-lg-3" style="margin-left: 1%;">
                <label>Response Time:</label>
                <div class="input-group">
                    <input autocomplete="off" style="width: 49%;" class="form-control datetimepicker_res_time" type="text" name="response_time_from" value="<?= $response_time_from; ?>" placeholder='Enter "From" Response Time' />
                    <input autocomplete="off" style="width: 49%; margin-left: 1%;" class="form-control datetimepicker_res_time" type="text" name="response_time_to" value="<?= $response_time_to; ?>" placeholder='Enter "To" Response Time' />
                </div>
            </div>

            <div class="col-lg-2" style="margin-left: 1%;">
                <label>Amount:</label>
                <div class="input-group">
                    <input autocomplete="off" style="width: 49%;" class="form-control" type="text" name="amount_from" value="<?= $amount_from; ?>" placeholder='Amount "From"' />
                    <input autocomplete="off" style="width: 49%; margin-left: 1%;" class="form-control" type="text" name="amount_to" value="<?= $amount_to; ?>" placeholder='Amount "To"' />
                </div>
            </div>

            <?php if (count($rowSet) > 0) { ?>
                <div class="col-lg-1" style="margin-left: 1%;">
                    <label>Sort By:</label>
                    <div class="input-group">
                        <select name="sort_by" id="sort_by" class="form-control">
                            <option value="">Sort By</option>
                            <option value="amount" <?= ($sort == 'amount') ? 'selected="selected"' : ""; ?>>Amount</option>
                            <option value="response_time" <?= ($sort == 'response_time') ? 'selected="selected"' : ""; ?>>Response Time</option>
                        </select>
                    </div>
                </div>
            <?php } ?>

            <div class="col-lg-1" style="margin-left: 1%;">
                <label>&nbsp;</label>
                <div class="input-group-btn">
                    <button style="width: 100%;" type="submit" class="btn btn-search btn-danger selecttext"> GO </button>
                </div>
            </div>

            <input type="hidden" name="filter" value="1" />

        </form>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div id="w0" class="grid-view">
                <table class="table table-bordered tableheadingrow">
                    <thead>
                        <tr>
                            <th style="width: 5%;">ID</th>
                            <th style="width: 15%;">Name</th>
                            <th style="width: 10%;">Txn Reference</th>
                            <th style="width: 7%;">Amount</th>
                            <th style="width: 5%;">Bank ID</th>
                            <th style="width: 10%;">Bank Reference</th>
                            <th style="width: 5%;">Txn Type</th>
                            <th style="width: 6%;">Auth Status</th>
                            <th style="width: 14%;">Description</th>
                            <th style="width: 11%;">Request Time</th>
                            <th style="width: 12%;">Response Time</th>
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
                                    <?= $row['customer_id']; ?>
                                </td>
                                <td>
                                    <?= $row['full_name']; ?>
                                </td>
                                <td>
                                    <?= $row['txn_reference_no']; ?>
                                </td>
                                <td>
                                    <?= $row['amount']; ?>
                                </td>
                                <td>
                                    <?= $row['bank_id']; ?>
                                </td>
                                <td>
                                    <?= $row['bank_reference_no']; ?>
                                </td>
                                <td>
                                    <?= $row['txn_type']; ?>
                                </td>
                                <td>
                                    <?= $row['auth_status']; ?>
                                </td>
                                <td>
                                    <?= $row['description']; ?>
                                </td>
                                <td>
                                    <?= $row['request_time']; ?>
                                </td>
                                <td>
                                    <?= $row['response_time']; ?>
                                </td>
                            </tr>
                        <?php } ?>
                        <?php if (count($rowSet) == 0) { ?>
                            <tr>
                                <td colspan="12">
                                    <div class="empty">No result found.</div>
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
        $(".datetimepicker_res_time").datetimepicker({
            useCurrent: true,
            format: 'YYYY-MM-D HH:mm:ss',
            sideBySide: true,
        });
    });
</script>