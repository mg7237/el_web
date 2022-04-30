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
            <p style="text-align: center; font-size: 20px; letter-spacing: 1px;">Payment Done.</p>
            <?php if (!empty(Yii::$app->request->get('dr'))) { ?>
                <p style="text-align: center;">
                    <small>Please download you payment receipt <br />available under "Past Payments" tab.</small>
                <form style="display: none;" action="<?php echo \yii\helpers\Url::home(true) ?>site/createnotidownload" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="noti-download" value="<?php echo Yii::$app->request->get('dr'); ?>">
                    <input type="hidden" name="noti-download-user" value="<?php echo Yii::$app->request->get('i'); ?>">
                    <button type="submit" style="width: 250px;padding: 10px;margin: auto;display: block;background: #5BC0DE;color: white;font-size: 16px;">
                        Download Payment Receipt
                    </button>
                </form>
            </p>
        <?php } ?>
    </div>
</body>
</html>