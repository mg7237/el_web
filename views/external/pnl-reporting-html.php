<?php

use yii\widgets\ActiveForm;
?>

<!--<div class="row">
    <div class="col-lg-12 col-sm-12" style="padding-top: 30px;padding-left: 10px;">
<?php $form = ActiveForm::begin(['id' => 'get-pnl-transaction-rows', 'options' => ['name' => 'get_pnl_transaction_rows', 'class' => 'form-inline', 'enctype' => 'multipart/form-data']]); ?>
            <input type="hidden" name="property_id" value="<?= $property_id ?>">
            <input type="hidden" name="paidOnSQL" value="<?= $paidOnSQL ?>">
<?php ActiveForm::end(); ?>
        <p>
            Total Expense: &#8377; <?= (!empty($totalEasyExpense)) ? Yii::$app->userdata->getFormattedMoney($totalEasyExpense) : 0 ?> &nbsp;&nbsp;
            <button id="expense-view-transaction" class="btn-2 btn-primary" style="height: 15px; padding: 0px 10px !important; font-size: 10px;">View Transactions</button>
            <button id="clear-view-transaction" class="btn" style="height: 15px; padding: 0px 10px !important; font-size: 10px; float: right;">Clear Transactions</button>
        </p>
        <p>
            Total Income: &#8377; <?= (!empty($totalEasyIncome)) ? Yii::$app->userdata->getFormattedMoney($totalEasyIncome) : 0 ?> &nbsp;&nbsp;
            <button id="income-view-transaction" class="btn-2 btn-primary" style="height: 15px; padding: 0px 10px !important; font-size: 10px;">View Transactions</button>
        </p>
        <p>
            <b>Total Profit & Loss: &#8377; <?= Yii::$app->userdata->getFormattedMoney(($totalEasyIncome - $totalEasyExpense)) ?></b>
        </p>
    </div>
</div>-->





<div class="row">
    <div class="col-lg-12 col-sm-12">
        <table class="table table-bordered tableheadingrow">
            <tr>
                <th class="text-center">Entity</th>
                <th class="text-center">Income (A)</th>
                <th class="text-center">Expense (B)</th>
                <th class="text-center">Transfer (C)</th>
                <th class="text-center">Net Income (D = A - B + C)</th>
            </tr>
            <tr>
                <td>Easyleases:</td>
                <td>&#8377; <?= Yii::$app->userdata->getFormattedMoney($totalEasyIncome) ?></td>
                <td>&#8377; <?= Yii::$app->userdata->getFormattedMoney($totalEasyExpense) ?></td>
                <td>&#8377; <?= Yii::$app->userdata->getFormattedMoney($totalEasyTrans) ?></td>
                <td>&#8377; <?= Yii::$app->userdata->getFormattedMoney($totalEasyIncome - $totalEasyExpense + $totalEasyTrans) ?></td>
            </tr>
            <tr>
                <td>PG Operator:</td>
                <td>&#8377; <?= Yii::$app->userdata->getFormattedMoney($totalPGIncome) ?></td>
                <td>&#8377; <?= Yii::$app->userdata->getFormattedMoney($totalPGExpense) ?></td>
                <td>&#8377; <?= Yii::$app->userdata->getFormattedMoney($totalPGTrans) ?></td>
                <td>&#8377; <?= Yii::$app->userdata->getFormattedMoney($totalPGIncome - $totalPGExpense + $totalPGTrans) ?></td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Total:</td>
                <td style="font-weight: bold;">&#8377; <?= Yii::$app->userdata->getFormattedMoney($totalEasyIncome + $totalPGIncome) ?></td>
                <td style="font-weight: bold;">&#8377; <?= Yii::$app->userdata->getFormattedMoney($totalEasyExpense + $totalPGExpense) ?></td>
                <td style="font-weight: bold;">&#8377; <?= Yii::$app->userdata->getFormattedMoney($totalEasyTrans + $totalPGTrans) ?></td>
                <td style="font-weight: bold;">&#8377; <?= Yii::$app->userdata->getFormattedMoney(($totalEasyIncome - $totalEasyExpense + $totalEasyTrans) + ($totalPGIncome - $totalPGExpense + $totalPGTrans)) ?></td>
            </tr>
        </table>
    </div>
</div>
<?php
$easyleasesPnlLiteral = (((($totalEasyIncome - $totalEasyExpense + $totalEasyTrans) + ($totalPGIncome - $totalPGExpense + $totalPGTrans)) * $profitShareRatio) / 100);
$easyleasesPnl = Yii::$app->userdata->getFormattedMoney($easyleasesPnlLiteral);
$pgOperatorPnlLiteral = (((($totalEasyIncome - $totalEasyExpense + $totalEasyTrans) + ($totalPGIncome - $totalPGExpense + $totalPGTrans)) * (100 - $profitShareRatio)) / 100);
$pgOperatorPnl = Yii::$app->userdata->getFormattedMoney($pgOperatorPnlLiteral);
$payableToEasyleases = Yii::$app->userdata->getFormattedMoney((($easyleasesPnlLiteral) - ($totalEasyIncome - $totalEasyExpense + $totalEasyTrans)));
$payableToPGOperator = Yii::$app->userdata->getFormattedMoney((($pgOperatorPnlLiteral) - ($totalPGIncome - $totalPGExpense + $totalPGTrans)));
?>
<div class="row">
    <div class="col-lg-12 col-sm-12">
        <table class="table table-bordered tableheadingrow">
            <tr>
                <td colspan="2"><p style="font-weight: bold;">PnL Share ( Easyleases : PG Operator ) = <?= round($profitShareRatio) ?> : <?= 100 - $profitShareRatio ?></p></td>
            </tr>
            <tr style="background: white;">
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td><span style="font-weight: bold;">Easyleases PnL</span></td>
                <td><span style="font-weight: bold;">PG Operator PnL </span></td>
            </tr>
            <tr>
                <td>&#8377; <?= $easyleasesPnl ?></td>
                <td>&#8377; <?= $pgOperatorPnl ?></td>
            </tr>
            <tr style="background: white;">
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td><span style="font-weight: bold;">Payable to Easyleases</span></td>
                <td><span style="font-weight: bold;">Payable to PG Operator</span></td>
            </tr>
            <tr>
                <td>&#8377; <?= $payableToEasyleases ?></td>
                <td>&#8377; <?= $payableToPGOperator ?></td>
            </tr>
        </table>
    </div>
</div>