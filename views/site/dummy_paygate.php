<?php
$this->title = 'Payment Gateway';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>

<!-- Modal signup -->
<div id="page-wrapper">
    <div class="row" style="text-align: center;margin-top: 100px;">
        <p style="color: red; font-size: 20px; font-weight: bolder;">Transaction Amount: Rs <?= $data['PropertyVisits']['txn_amount'] ?></p>
        <form id="payment-gateway-form" method="POST" action="" enctype="multipart/form-data" style="margin-right: 10px; display: inline;">
            <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
            <input type="hidden" name="dummy_pgi_request" value="1" />
            <?php if ($data['PropertyVisits']['type'] != 3) { ?>
                <input type="hidden" name="bedroom_info" value='<?= @addslashes($data['bedroom_info']) ?>' />
            <?php } ?>
            <!-- If 1 = Success and If 2 = Cancel Payment -->
            <input id="payment-status" type="hidden" name="payment_status" value="1" />
            <?php if (!empty($_POST['payment_action']) && $_POST['payment_action'] === 'TENANT_PAYMENT') { ?>
                <input type="hidden" name="app_redirect_url" value="<?= $data['app_redirect_url'] ?>">
                <input type="hidden" name="payment_action" value="TENANT_PAYMENT">
                <input type="hidden" name="serial" value="<?= $_POST['serial'] ?>">

                <input type="hidden" name="TenantPayments[id]" value="<?= $_POST['TenantPayments']['id'] ?>">
                <input type="hidden" name="TenantPayments[property_id]" value="<?= $_POST['TenantPayments']['property_id'] ?>">
                <input type="hidden" name="TenantPayments[type]" value="<?= $_POST['TenantPayments']['type'] ?>">
                <input type="hidden" name="TenantPayments[penalty_amount]" value="<?= $_POST['TenantPayments']['penalty_amount'] ?>">
                <input type="hidden" name="TenantPayments[totalamount]" value="<?= $_POST['TenantPayments']['totalamount'] ?>">
            <?php } else { ?>
                <input type="hidden" name="app_redirect_url" value="<?php echo Url::home(true) ?>site/view?id=<?= $data['PropertyVisits']['property_id'] ?>">
            <?php } ?>
        <!--<input type="hidden" name="dummy_data" value="<?= addslashes(json_encode($data['PropertyVisits'])); ?>" />-->
            <input type="hidden" name="PropertyVisits[txn_amount]" value="<?= $data['PropertyVisits']['txn_amount']; ?>" />
            <input type="hidden" name="PropertyVisits[property_id]" value="<?= $data['PropertyVisits']['property_id']; ?>" />
            <input style="width: 300px; height: 59px; color: darkgreen; font-weight: bolder; font-size: 20px;" type="submit" name="submit_button" value="Payment Successfull" />
        </form>
        <a onclick="cancelPayment()" href="javascript:void(0);" ><button style="width: 300px; height: 59px; color: darkgreen; font-weight: bolder; font-size: 20px;">Payment Cancel</button></a>
    </div>
</div>
<script>
    function cancelPayment() {
        document.getElementById("payment-status").value = 2;
        document.getElementById("payment-gateway-form").submit();
    }
</script>
<?php exit; ?>