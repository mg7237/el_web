<?php

use yii\widgets\ActiveForm;

$showApproveBtn = true;
$disabledBtn = 'disabled';
$approvedByValue = '';
?>
<div class="col-lg-12 col-sm-12">
    <div id="csv-list-box" class="grid-view">
        <?php $form = ActiveForm::begin(['action' => 'processincomecsv', 'id' => 'income-list-form', 'options' => ['name' => 'income-list-form', 'class' => 'form-inline', 'enctype' => 'multipart/form-data']]); ?>
        <input type="hidden" name="property_id" value="<?= $property_id ?>" />
        <input id="delete-property-income" type="hidden" name="delete_property_income" value="" />
        <input id="approve-property-income-rows" type="hidden" name="approve_property_income_rows" value="" />
        <table class="table table-striped tableheadingrow" id="income-table">
            <thead>
                <tr>
                    <th><?= (!empty($headers)) ? '<input type="checkbox" onclick="checkAll()" name="select_all" value="" />' : '' ?></th>
                    <?php foreach ($headers as $header) { ?>
                        <th><?= $header ?></th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody id="income-table-body">
                <?php
                $flag = 0;
                ?>
                <?php foreach ($fileContent as $data) { ?>
                    <?php $approvedByValue = $data[10]; ?>
                    <?php if (!empty($data)) { ?>
                        <tr id="data-row-id-<?= $flag ?>" class="<?= (!empty($approvedByValue)) ? 'approved-row' : ''; ?>">
                            <?php
                            if (!empty($data)) {
                                $count = count($data) + 0;
                                ?>
                                <?php
                                $userType = Yii::$app->user->identity->user_type;
                                if ($userType == 7) {
                                    $opUser = \app\models\OperationsProfile::findOne(['operations_id' => Yii::$app->user->identity->id]);
                                    if ($opUser->role_code == 'OPEXE' && $opUser->role_value != 'ELHQ') {
                                        $showApproveBtn = false;
                                    }
                                } else if ($userType == 6) {
                                    $salesUser = \app\models\SalesProfile::findOne(['sale_id' => Yii::$app->user->identity->id]);
                                    if ($salesUser->role_code == 'SLEXE') {
                                        $showApproveBtn = false;
                                    }
                                }
                                ?>
                                <?php if (!empty($data)) { ?>
                                    <td>
                                        <input id="row-id-<?= $flag ?>" class="ss-created" type="hidden" name="row_id[<?= $flag ?>]" value="<?= $rowIds[$flag] ?>" />
                                        <input <?= (!$showApproveBtn && !empty($approvedByValue)) ? $disabledBtn : ''; ?> class="select-item" type="checkbox" onclick="selectSerial(this, <?= $flag ?>)" name="RowId[<?= $flag ?>]" value="<?= $flag ?>" />
                                    </td>
                                <?php } ?>
                                <?php for ($i = 0; $i < $count; $i++) { ?>
                                    <?php if ($i == 2) { ?>
                                        <?php
                                        $expType = \app\models\IncomeType::find()->all();
                                        ?>
                                        <td>
                                            <select <?= (!$showApproveBtn && !empty($approvedByValue)) ? $disabledBtn : ''; ?> class="row-item row-item-<?= $flag ?> csv-inputs" name="PropertyIncome[<?= $flag ?>][<?= $i ?>]">
                                                <option value="">None</option>
                                                <?php foreach ($expType as $type) { ?>
                                                    <option <?= ($data[$i] == $type->income_code) ? 'selected' : '' ?> value="<?= $type->income_code ?>"><?= $type->income_name ?></option>
                                                <?php } ?>
                                            </select>
                                            <?php if (!$showApproveBtn && !empty($approvedByValue)) { ?>
                                                <input type="hidden" name="PropertyIncome[<?= $flag ?>][<?= $i ?>]" value="<?= $type->income_code ?>" />
                                            <?php } ?>
                                        </td>
                                    <?php } else if ($i == 0) { ?>
                                        <td>
                                            <input <?= (!$showApproveBtn && !empty($approvedByValue)) ? $disabledBtn : ''; ?> style="width: 65px;" class="row-item row-item-<?= $flag ?> csv-inputs" type="text" name="PropertyIncome[<?= $flag ?>][<?= $i ?>]" value="<?= $data[$i] ?>" />
                                            <?php if (!$showApproveBtn && !empty($approvedByValue)) { ?>
                                                <input type="hidden" name="PropertyIncome[<?= $flag ?>][<?= $i ?>]" value="<?= $data[$i] ?>" />
                                            <?php } ?>
                                        </td>
                                    <?php } else if ($i == 1) { ?>
                                        <td>
                                            <input <?= (!$showApproveBtn && !empty($approvedByValue)) ? $disabledBtn : ''; ?> pattern="[0-9]+(\.[0-9][0-9]?)?" style="width: 85px; text-align: center;" class="row-item row-item-<?= $flag ?> csv-inputs" type="text" name="PropertyIncome[<?= $flag ?>][<?= $i ?>]" value="<?= $data[$i] ?>" />
                                            <?php if (!$showApproveBtn && !empty($approvedByValue)) { ?>
                                                <input type="hidden" name="PropertyIncome[<?= $flag ?>][<?= $i ?>]" value="<?= $data[$i] ?>" />
                                            <?php } ?>
                                        </td>
                                    <?php } else if ($i == 3) { ?>
                                        <td>
                                            <input <?= (!$showApproveBtn && !empty($approvedByValue)) ? $disabledBtn : ''; ?> style="width: 85px;" class="row-item row-item-<?= $flag ?> total-income-amount total-grouped-income-amount-<?= $data[2] ?> csv-inputs" type="text" name="PropertyIncome[<?= $flag ?>][<?= $i ?>]" value="<?= $data[$i] ?>" />
                                            <?php if (!$showApproveBtn && !empty($approvedByValue)) { ?>
                                                <input type="hidden" name="PropertyIncome[<?= $flag ?>][<?= $i ?>]" value="<?= $data[$i] ?>" />
                                            <?php } ?>
                                        </td>
                                    <?php } else if ($i == 4) { ?>
                                        <td>
                                            <input <?= (!$showApproveBtn && !empty($approvedByValue)) ? $disabledBtn : ''; ?> style="width: 90px;" class="row-item row-item-<?= $flag ?> csv-inputs" type="text" name="PropertyIncome[<?= $flag ?>][<?= $i ?>]" value="<?= $data[$i] ?>" />
                                            <?php if (!$showApproveBtn && !empty($approvedByValue)) { ?>
                                                <input type="hidden" name="PropertyIncome[<?= $flag ?>][<?= $i ?>]" value="<?= $data[$i] ?>" />
                                            <?php } ?>
                                        </td>
                                    <?php } else if ($i == 5) { ?>
                                        <td>
                                            <input <?= (!$showApproveBtn && !empty($approvedByValue)) ? $disabledBtn : ''; ?> style="width: 90px;" onkeydown="getIncomeTransMode(this, this.value)" class="row-item income-transaction-mode-autocomp row-item-<?= $flag ?> csv-inputs" type="text" name="PropertyIncome[<?= $flag ?>][<?= $i ?>]" value="<?= $data[$i] ?>" />
                                            <?php if (!$showApproveBtn && !empty($approvedByValue)) { ?>
                                                <input type="hidden" name="PropertyIncome[<?= $flag ?>][<?= $i ?>]" value="<?= $data[$i] ?>" />
                                            <?php } ?>
                                        </td>
                                    <?php } else if ($i == 6) { ?>
                                        <td>
                                            <input <?= (!$showApproveBtn && !empty($approvedByValue)) ? $disabledBtn : ''; ?> style="width: 90px;" class="row-item row-item-<?= $flag ?> income-query-date csv-inputs" type="text" name="PropertyIncome[<?= $flag ?>][<?= $i ?>]" value="<?= $data[$i] ?>" />
                                            <?php if (!$showApproveBtn && !empty($approvedByValue)) { ?>
                                                <input type="hidden" name="PropertyIncome[<?= $flag ?>][<?= $i ?>]" value="<?= $data[$i] ?>" />
                                            <?php } ?>
                                        </td>
                                    <?php } else if ($i == 7) { ?>
                                        <td>
                                            <input <?= (!$showApproveBtn && !empty($approvedByValue)) ? $disabledBtn : ''; ?> style="width: auto;" class="row-item row-item-<?= $flag ?> csv-inputs" type="text" name="PropertyIncome[<?= $flag ?>][<?= $i ?>]" value="<?= $data[$i] ?>" />
                                            <?php if (!$showApproveBtn && !empty($approvedByValue)) { ?>
                                                <input type="hidden" name="PropertyIncome[<?= $flag ?>][<?= $i ?>]" value="<?= $data[$i] ?>" />
                                            <?php } ?>
                                        </td>
                                    <?php } else if ($i == 8) { ?>
                                        <td>
                                            <input <?= (!$showApproveBtn && !empty($approvedByValue)) ? $disabledBtn : ''; ?> style="width: auto;" onkeydown="getIncomeEntityType(this, this.value)" class="row-item row-item-<?= $flag ?> csv-inputs" type="text" name="PropertyIncome[<?= $flag ?>][<?= $i ?>]" value="<?= $data[$i] ?>" />
                                            <?php if (!$showApproveBtn && !empty($approvedByValue)) { ?>
                                                <input type="hidden" name="PropertyIncome[<?= $flag ?>][<?= $i ?>]" value="<?= $data[$i] ?>" />
                                            <?php } ?>
                                        </td>
                                    <?php } else if ($i == 9) { ?>
                                        <td>
                                            <input <?= (!$showApproveBtn && !empty($approvedByValue)) ? $disabledBtn : ''; ?> style="width: 90px;" class="row-item row-item-<?= $flag ?> income-query-month csv-inputs" type="text" name="PropertyIncome[<?= $flag ?>][<?= $i ?>]" value="<?= $data[$i] ?>" />
                                            <?php if (!$showApproveBtn && !empty($approvedByValue)) { ?>
                                                <input type="hidden" name="PropertyIncome[<?= $flag ?>][<?= $i ?>]" value="<?= $data[$i] ?>" />
                                            <?php } ?>
                                        </td>
                                    <?php } else if ($i == ($totalColumns - 1)) { ?>
                                        <td>
                                            <p style="width: 250px;">
                                                <?php if (!empty($data[$i])) { ?>
                                                    <a id="attachment-view-link-<?= $flag ?>" style="float: left;<?= (!empty($approvedByValue)) ? 'color: white;' : 'color: black;'; ?>" target="_blank" href="<?= \yii\helpers\Url::home(true) ?><?= $data[$i] ?>"><i class="glyphicon glyphicon-file" aria-hidden="true"></i> View attachment</a>
                                                    <?php if (!$showApproveBtn && !empty($approvedByValue)) { ?>
                                                        <!-- Kept blank intensionally  -->
                                                    <?php } else { ?>
                                                        <span onclick="removeAttachment(this, <?= $flag ?>)" style="cursor: pointer;<?= (!empty($approvedByValue)) ? 'color: white;' : 'color: black;'; ?>"><i class="glyphicon glyphicon-remove" aria-hidden="true"></i></span>
                                                        <input id="attach-file-<?= $flag ?>" style="display: none;" required="required" class="row-item row-item-<?= $flag ?> csv-inputs" type="file" name="IncomeAttachment[<?= $flag ?>]" />
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <?php if (!$showApproveBtn && !empty($approvedByValue)) { ?>
                                                        No Attachment
                                                    <?php } else { ?>
                                                        <input <?= (!$showApproveBtn && !empty($approvedByValue)) ? $disabledBtn : ''; ?> required="required" class="row-item row-item-<?= $flag ?> csv-inputs" type="file" name="IncomeAttachment[<?= $flag ?>]" />
                                                    <?php } ?>
                                                <?php } ?>
                                            </p>
                                        </td>
                                    <?php } else { ?>
                                        <td>
                                            <input <?= (!$showApproveBtn && !empty($approvedByValue)) ? $disabledBtn : ''; ?> style="width: auto;" class="row-item row-item-<?= $flag ?> csv-inputs" type="text" name="PropertyIncome[<?= $flag ?>][<?= $i ?>]" value="<?= $data[$i] ?>" />
                                            <?php if (!$showApproveBtn && !empty($approvedByValue)) { ?>
                                                <input type="hidden" name="PropertyIncome[<?= $flag ?>][<?= $i ?>]" value="<?= $data[$i] ?>" />
                                            <?php } ?>
                                        </td>
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
<script>
    $(".income-query-date").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd-M-yy'
    });

    $('.income-query-month').MonthPicker({
        Button: false,
        MonthFormat: 'M-yy',
    });

    $(document).on('change keyup click mousedown paste', '.month-year-input', function () {
        enableSaveBtn();
        enableUndoBtn();
        enableIncomeSaveBtn();
        enableIncomeUndoBtn();
    });

//    $(".income-query-month").datepicker({
//        changeMonth: true,
//        changeYear: true,
//        showButtonPanel: true,
//        onClose: function(dateText, inst) {
//            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
//            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
//            $(this).val($.datepicker.formatDate('M-yy', new Date(year, month, 1)));
//        }
//    });
//
//    $(".income-query-month").focus(function () {
//        $(".ui-datepicker-calendar").hide();
//        $(".ui-datepicker-current").hide();
//        $("#ui-datepicker-div").position({
//            my: "center top",
//            at: "center bottom",
//            of: $(this)
//        });
//    });
</script>