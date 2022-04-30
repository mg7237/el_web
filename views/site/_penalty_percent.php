 
<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>
<style>
    .state {
        float: left;
        width: 33%;
    }
    tr:first-child th, tr:first-child td {
        border-top: none !important;
    }
</style>

<div class="modal-header">
    <button type="button" class="close closetext" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <br/>
    <h4 class="modal-title modaltitletextnew" style="text-transform: uppercase;font-weight: 600;">Penalty Calculation</h4>
    <br>
</div>

<div class="modal-body modaltextpargh" style="float:left;width:100%;background:#FFFFFF;height: 63%;padding-left: 0px;padding-right: 0px;">
    <?php
//    $currentDate = date_create(Date('Y-m-d'));
//    $dueDate = date_create($payments['due_date']);
//    $diff = date_diff($currentDate, $dueDate);
//    $days = $diff->y * 365.25 + $diff->m * 30 + $diff->d + $diff->h / 24 + $diff->i / 60;
//    $penaltys = ceil(((int) ($days) / 365) * ($penalty->late_penalty_percent / 100) * $payments['total_amount']);
    if ($for == 2) {
        $currentDate = $payments['payment_date'];
    } else {
        $currentDate = Date('Y-m-d');
    }
    $dateDiff = date_diff(date_create($payments['due_date']), date_create($currentDate))->days;
    $penalty = 0;
    $penaltys = 0;
    if ($dateDiff > 0) {
        $penalty = round(($dateDiff / date("z", (mktime(0, 0, 0, 12, 31, date('Y'))) + 1)) * ($payments['late_penalty_percent'] / 100) * $payments['total_amount']);
        $penaltys = ( $penalty < $payments['min_penalty'] ) ? $payments['min_penalty'] : $penalty;
    }
    ?>
    <div class="table-responsive">
        <table  style="text-align: left;text-align: left;border: 1px solid #ffffff;">
            <tr>
                <th style="padding-left: 15px;color: #5a5a5a;font-size: 12px;">Original Amount</th>
                <td style="float: right;padding-top: 25px;margin-right: 17px;font-size: 12px;font-weight: 600;color: #5a5a5a;">&#8377 <?= Yii::$app->userdata->getformat((int) $payments['total_amount'] - (int) $payments['penalty_amount']) ?></td>
            </tr>
            <tr class="dclass">
                <th style="padding-left: 15px;color: #5a5a5a;font-size: 12px;">Payment Due Date</th>
                <td style="float: right;padding-top: 25px;margin-right: 17px;font-size: 12px;font-weight: 600;color: #5a5a5a;"><?php echo Date('d-M-Y', strtotime($payments['due_date'])) ?></td>
            </tr>
            <tr>
                <th style="padding-left: 15px;color: #5a5a5a;font-size: 12px;">Days Penalty Applicable For</th>
                <td style="float: right;padding-top: 25px;margin-right: 17px;font-size: 12px;font-weight: 600;color: #5a5a5a;"><?= $dateDiff ?> Days</td>
            </tr>
            <tr class="dclass">
                <th style="padding-left: 15px;color: #5a5a5a;font-size: 12px;">Penalty @<?= $payments['late_penalty_percent'] ?> %</th>
                <td style="float: right;padding-top: 25px;margin-right: 17px;font-size: 12px;font-weight: 600;color: #5a5a5a;">&#8377 <?= Yii::$app->userdata->getformat($penalty) ?></td>
            </tr>
            <tr>
                <th style="padding-left: 15px;color: #5a5a5a;font-size: 12px;">Minimum Penalty Applicable</th>
                <td style="float: right;padding-top: 25px;margin-right: 17px;font-size: 12px;font-weight: 600;color: #5a5a5a;">&#8377 <?= Yii::$app->userdata->getformat($payments['min_penalty']) ?></td>
            </tr>
            <tr class="dclass">
                <th style="padding-left: 15px;color: #5a5a5a;font-size: 12px;">Penalty Applied</th>
                <td style="float: right;padding-top: 25px;margin-right: 17px;font-size: 12px;font-weight: 600;color: red;">&#8377 <?= Yii::$app->userdata->getformat($penaltys) ?></td>
            </tr>
        </table>
    </div>

</div>

</div>
<style>
    .modal-header {
        text-align: center;
        background: #fafafa;
        border-bottom: 1px solid #fafafa;
    }
</style>
