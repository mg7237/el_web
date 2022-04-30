<?php

use yii\widgets\ActiveForm;
?>
<div class="col-lg-12 col-sm-12">
    <div id="csv-list-box" class="grid-view">
        <?php $form = ActiveForm::begin(['action' => 'processexpensecsv', 'id' => 'expense-list-form', 'options' => ['name' => 'expense-list-form', 'class' => 'form-inline', 'enctype' => 'multipart/form-data']]); ?>
        <input type="hidden" name="property_id" value="<?= $property_id ?>" />
        <input id="delete-property-expense" type="hidden" name="delete_property_expense" value="" />
        <table class="table table-bordered tableheadingrow">
            <thead>
                <tr>
                    <th><?= (!empty($headers)) ? '<input type="checkbox" onclick="checkAll()" name="select_all" value="" />' : '' ?></th>
                    <?php foreach ($headers as $header) { ?>
                        <th><?= $header ?></th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody id="expense-table-body">
                <?php
                $flag = 0;
                ?>
                <?php foreach ($fileContent as $data) { ?>
                    <?php if (!empty($data)) { ?>
                        <tr id="data-row-id-<?= $flag ?>">
                            <?php if (!empty($data)) { ?>
                                <td>
                                    <input id="row-id-<?= $flag ?>" class="ss-created" type="hidden" name="row_id[<?= $flag ?>]" value="<?= $rowIds[$flag] ?>" />
                                    <input class="select-item" type="checkbox" onclick="selectSerial(this, <?= $flag ?>)" name="RowId[<?= $flag ?>]" value="<?= $flag ?>" />
                                </td>
                            <?php } ?>
                            <?php
                            if (!empty($data)) {
                                $count = count($data) + 1;
                                ?>
                                <?php for ($i = 0; $i < $count; $i++) { ?>
                                    <?php if ($i == 1) { ?>
                                        <?php
                                        $expType = \app\models\ExpenseType::find()->all();
                                        ?>
                                        <td>
                                            <select class="row-item row-item-<?= $flag ?>" readonly="readonly" name="PropertyExpense[<?= $flag ?>][<?= $i ?>]">
                                                <?php foreach ($expType as $type) { ?>
                                                    <option <?= ($data[$i] == $type->expense_name) ? 'selected' : '' ?> value="<?= $type->expense_code ?>"><?= $type->expense_name ?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                    <?php } else if ($i == ($totalColumns - 1)) { ?>
                                        <td>
                                            <input required="required" class="row-item row-item-<?= $flag ?>" type="file" name="ExpenseAttachment[<?= $flag ?>]" />
                                        </td>
                                    <?php } else { ?>
                                        <td><input class="row-item row-item-<?= $flag ?>" readonly="readonly" type="text" name="PropertyExpense[<?= $flag ?>][<?= $i ?>]" value="<?= $data[$i] ?>" /></td>
                                    <?php } ?>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                        <?php
                        $flag++;
                    }
                }
                ?>
            </tbody>
        </table>
        <input type="hidden" id="last-start-row" name="last_start_row" value="<?= $flag ?>" />
        <input type="hidden" id="total-columns" name="total_columns" value="<?= $totalColumns - 1 ?>" />
<?php ActiveForm::end(); ?>
    </div>
</div>