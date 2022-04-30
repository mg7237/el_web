<!DOCTYPE html>
<html>
    <head>
        <title>Payment Done</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <div>   
            <img src="<?= yii\helpers\Url::home(true) ?>images/icons/tick-blue.png" alt="Transaction Successful" style="display: block;margin: auto;width: 200px;margin-top: 10%;" />
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p style="text-align: center; font-size: 20px; letter-spacing: 1px;">Thanks for making payment via NEFT, we will update the payment status to 'Paid' once we have verified the payment data against our bank records. The verification normally takes 2-3 business days. In case of any mismatch, our operations team will reach out to you for any further clarifications.</p>
        </div>
    </body>
</html>