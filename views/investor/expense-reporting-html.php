<?php

use yii\widgets\ActiveForm;

$totalExpense = 0;
foreach ($expenseData as $data) {
    if (!empty($data)) {
        $totalExpense = $data['total_expense_amount'] + $totalExpense;
    }
}
?>
<?php if ($totalExpense > 0) { ?>
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <span>
                Total Expense: <?= $totalExpense ?>
            </span>
            <span>
                &nbsp;&nbsp;&nbsp;
            </span>
        </div>
    </div>
    <br />
<?php } ?>
<div class="row">
    <div class="col-lg-12 col-sm-12">
        <div id="csv-list-box" class="grid-view">
            <?php $form = ActiveForm::begin(['action' => '', 'id' => 'expense-list-form', 'options' => ['name' => 'expense-list-form', 'class' => 'form-inline', 'enctype' => 'multipart/form-data']]); ?>
            <input type="hidden" name="property_id" value="<?= $property_id ?>" />
            <table class="table table-striped tableheadingrow" id="expense-table">
                <thead>
                    <tr>
                        <?php foreach ($headers as $header) { ?>
                            <th><?= $header ?></th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody id="expense-table-body">
                    <?php
                    $flag = 0;
                    ?>
                    <?php foreach ($expenseData as $data) { ?>
                        <?php if (!empty($data)) { ?>
                            <tr id="data-row-id-<?= $flag ?>">
                                <?php
                                $expType = \app\models\ExpenseType::find()->all();
                                ?>
                                <td>
                                    <?php foreach ($expType as $type) { ?>
                                        <?php
                                        if ($data['expense_type'] == $type->expense_code) {
                                            echo $type->expense_name;
                                        }
                                        ?>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?= $data['total_expense_amount'] ?>
                                </td>
                                <td>
                                    <?php if (!empty($data['paid_on'])) { ?>
                                        <?= date('d-M-Y', strtotime($data['paid_on'])) ?>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?= $data['paid_by'] ?>
                                </td>
                                <td>
                                    <?= $data['approved_by'] ?>
                                </td>
                                <td>
                                    <?= $data['vendor'] ?>
                                </td>
                                <td>
                                    <?php if (!empty($data['invoice_date'])) { ?>
                                        <?= date('d-M-Y', strtotime($data['invoice_date'])) ?>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?= $data['gst_invoice'] ?>
                                </td>
                                <td>
                                    <?= $data['vendor_gst'] ?>
                                </td>
                                <td>
                                    <?= $data['total_gst'] ?>
                                </td>
                                <td>
                                    <?= $data['remarks'] ?>
                                </td>
                                <td>
                                    <?php if (!empty($data['attachment'])) { ?>
                                        <a href="<?= \yii\helpers\Url::home(true) ?><?= $data['attachment'] ?>"> View </a>
                                    <?php } else { ?>
                                        No Attachment
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php
                            $flag++;
                        }
                    }
                    ?>
                </tbody>
            </table>
<?php ActiveForm::end(); ?>
        </div>
    </div>
</div>