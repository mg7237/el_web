<?php
if (empty($expenseData) && empty($incomeData)) {
    exit;
}
?>
<?php

use yii\widgets\ActiveForm;
?>
<style>
    td {
        font-size: 10px !important;
    }
</style>

<div class="col-lg-12 col-sm-12">
    <div id="csv-list-box" class="grid-view">
<?php $form = ActiveForm::begin(['action' => '#', 'id' => 'pnl-trans-list-form', 'options' => ['name' => 'pnl-trans-list-form', 'class' => 'form-inline', 'enctype' => 'multipart/form-data']]); ?>
        <input type="hidden" name="property_id" value="<?= $property_id ?>" />
        <table class="table table-bordered tableheadingrow">
            <thead>
                <tr>
                    <?php foreach ($headers as $header) { ?>
                        <th style="font-size: 10px;"><?= $header ?></th>
<?php } ?>
                </tr>
            </thead>
            <tbody id="expense-table-body">
                <?php
                $flag = 0;
                ?>
<?php if (!empty($expenseData)) { ?>
                <div class="col-12">
                    <button id="export-pnl-rows-expense" class="btn-2 btn-primary" style="height: 15px; padding: 1px 10px !important; font-size: 10px; margin-bottom: 10px;">Export</button>
                </div>
    <?php foreach ($expenseData as $data) { ?>
                    <tr id="data-row-id-<?= $flag ?>">
                        <td>
                            <input class="row-item row-item-<?= $flag ?>" type="hidden" name="PropertyExpense[<?= $flag ?>][0]" value="<?= $data['expense_type'] ?>" />
                            <input class="row-item row-item-<?= $flag ?>" readonly="readonly" type="text" name="PropertyExpenseView[<?= $flag ?>][0]" value="<?= Yii::$app->userdata->getExpenseNameByCode($data['expense_type']) ?>" />
                        </td>
                        <td>
                            <input class="row-item row-item-<?= $flag ?>" readonly="readonly" type="text" name="PropertyExpense[<?= $flag ?>][1]" value="<?= $data['total_expense_amount'] ?>" />
                        </td>
                        <td>
                            <input class="row-item row-item-<?= $flag ?>" readonly="readonly" type="text" name="PropertyExpense[<?= $flag ?>][2]" value="<?= date('d-M-Y', strtotime($data['paid_on'])) ?>" />
                        </td>
                        <td>
                            <input class="row-item row-item-<?= $flag ?>" readonly="readonly" type="text" name="PropertyExpense[<?= $flag ?>][3]" value="<?= $data['paid_by'] ?>" />
                        </td>
                        <td>
                            <input class="row-item row-item-<?= $flag ?>" readonly="readonly" type="text" name="PropertyExpense[<?= $flag ?>][4]" value="<?= $data['vendor'] ?>" />
                        </td>
                        <td>
                            <input class="row-item row-item-<?= $flag ?>" readonly="readonly" type="text" name="PropertyExpense[<?= $flag ?>][5]" value="<?= (!empty($data['invoice_date'])) ? date('d-M-Y', strtotime($data['invoice_date'])) : '' ?>" />
                        </td>
                        <td>
                            <input class="row-item row-item-<?= $flag ?>" readonly="readonly" type="text" name="PropertyExpense[<?= $flag ?>][6]" value="<?= $data['gst_invoice'] ?>" />
                        </td>
                        <td>
                            <input class="row-item row-item-<?= $flag ?>" readonly="readonly" type="text" name="PropertyExpense[<?= $flag ?>][7]" value="<?= $data['vendor_gst'] ?>" />
                        </td>
                        <td>
                            <input class="row-item row-item-<?= $flag ?>" readonly="readonly" type="text" name="PropertyExpense[<?= $flag ?>][8]" value="<?= $data['total_gst'] ?>" />
                        </td>
                        <td>
                            <input class="row-item row-item-<?= $flag ?>" readonly="readonly" type="text" name="PropertyExpense[<?= $flag ?>][9]" value="<?= (!empty($data['month'])) ? date('M-Y', strtotime($data['month'])) : '' ?>" />
                        </td>
                        <td>
                            <input class="row-item row-item-<?= $flag ?>" readonly="readonly" type="text" name="PropertyExpense[<?= $flag ?>][10]" value="<?= Yii::$app->userdata->getUserNameById($data['approved_by']) ?>" />
                        </td>
                        <td>
                            <input class="row-item row-item-<?= $flag ?>" readonly="readonly" type="text" name="PropertyExpense[<?= $flag ?>][11]" value="<?= $data['remarks'] ?>" />
                        </td>
                    </tr>
                    <?php $flag++;
                }
                ?>
<?php } ?>

            <?php if (!empty($incomeData)) { ?>
                <div class="col-12">
                    <button id="export-pnl-rows-income" class="btn-2 btn-primary" style="height: 15px; padding: 1px 10px !important; font-size: 10px; margin-bottom: 10px;">Export</button>
                </div>
    <?php foreach ($incomeData as $data) { ?>
                    <tr id="data-row-id-<?= $flag ?>">
                        <td>
                            <input class="row-item row-item-<?= $flag ?>" readonly="readonly" type="text" name="PropertyIncome[<?= $flag ?>][0]" value="<?= $data['customer_id'] ?>" />
                        </td>
                        <td>
                            <input class="row-item row-item-<?= $flag ?>" readonly="readonly" type="text" name="PropertyIncome[<?= $flag ?>][1]" value="<?= $data['customer_name'] ?>" />
                        </td>
                        <td>
                            <input class="row-item row-item-<?= $flag ?>" type="hidden" name="PropertyIncome[<?= $flag ?>][2]" value="<?= $data['income_type'] ?>" />
                            <input class="row-item row-item-<?= $flag ?>" readonly="readonly" type="text" name="PropertyIncomeView[<?= $flag ?>][2]" value="<?= Yii::$app->userdata->getIncomeNameByCode($data['income_type']) ?>" />
                        </td>
                        <td>
                            <input class="row-item row-item-<?= $flag ?>" readonly="readonly" type="text" name="PropertyIncome[<?= $flag ?>][3]" value="<?= $data['total_income_amount'] ?>" />
                        </td>
                        <td>
                            <input class="row-item row-item-<?= $flag ?>" readonly="readonly" type="text" name="PropertyIncome[<?= $flag ?>][4]" value="<?= $data['transaction_num'] ?>" />
                        </td>
                        <td>
                            <input class="row-item row-item-<?= $flag ?>" readonly="readonly" type="text" name="PropertyIncome[<?= $flag ?>][5]" value="<?= Yii::$app->userdata->getPaymentModeType($data['transaction_mode']) ?>" />
                        </td>
                        <td>
                            <input class="row-item row-item-<?= $flag ?>" readonly="readonly" type="text" name="PropertyIncome[<?= $flag ?>][6]" value="<?= date('d-M-Y', strtotime($data['paid_on'])) ?>" />
                        </td>
                        <td>
                            <input class="row-item row-item-<?= $flag ?>" readonly="readonly" type="text" name="PropertyIncome[<?= $flag ?>][7]" value="<?= $data['received_by_person'] ?>" />
                        </td>
                        <td>
                            <input class="row-item row-item-<?= $flag ?>" readonly="readonly" type="text" name="PropertyIncome[<?= $flag ?>][8]" value="<?= $data['received_by_entity'] ?>" />
                        </td>
                        <td>
                            <input class="row-item row-item-<?= $flag ?>" readonly="readonly" type="text" name="PropertyIncome[<?= $flag ?>][9]" value="<?= (!empty($data['month'])) ? date('M-Y', strtotime($data['month'])) : '' ?>" />
                        </td>
                        <td>
                            <input class="row-item row-item-<?= $flag ?>" readonly="readonly" type="text" name="PropertyIncome[<?= $flag ?>][10]" value="<?= Yii::$app->userdata->getUserNameById($data['approved_by']) ?>" />
                        </td>
                        <td>
                            <input class="row-item row-item-<?= $flag ?>" readonly="readonly" type="text" name="PropertyIncome[<?= $flag ?>][11]" value="<?= $data['remarks'] ?>" />
                        </td>
                    </tr>
        <?php $flag++;
    }
    ?>
<?php } ?>
            </tbody>
        </table>
<?php ActiveForm::end(); ?>
    </div>
</div>