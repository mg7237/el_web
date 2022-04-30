<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<table style="background:#fff;border:1px solid #c0babe;width:100%; max-width:800px;margin:auto; overflow-x:auto;border-collapse: collapse;font-family: 'Nunito Sans', sans-serif;">
    <tbody>
        <tr>
            <td style="padding:25px 24px; width:100%;border-radius:3px;font-family: 'Nunito Sans', sans-serif;">
                <table style="width: 100%;">
                    <tbody>
                        <tr>
                            <td style="width:60%">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td style="width: 80%; color:#182233; font-size: 20px;vertical-align:middle;line-height: 29px;font-family: 'Nunito Sans', sans-serif; white-space: nowrap;text-align:left;padding:0 25px 0 0px;">
                                                <div><img style="height: 50px; width: 200px;" src="http://www.easyleases.in/images/newlogo1.png" alt="Easyleases" /></div>
                                                <br /> 
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 80%; color:#182233; font-size: 20px;vertical-align:middle;line-height: 29px;font-family: 'Nunito Sans', sans-serif; white-space: nowrap;text-align:left;padding:0 25px 0 0px;">
                                                <div>Easyleases Technologies Private Limited</div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td style="font-size:11px; line-height:17.5px; line-height:14.6px;color:#222; text-align:right;font-family: 'Nunito Sans', sans-serif;width: 40%;">
                                <table style="width:100%">
                                    <tbody>
                                        <tr>
                                            <td style="padding-bottom:12px;font-family: 'Nunito Sans', sans-serif; font-size:11px;line-height: 17.5px;text-align:right;"> Phone: <span><?= $model['tenantPhone'] ?></span> </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-bottom:12px;font-family: 'Nunito Sans', sans-serif; font-size:11px;line-height: 17.5px;text-align:right;"> Email: <span><?= $model['tenantEmail'] ?></span> </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-bottom:12px;font-family: 'Nunito Sans', sans-serif; font-size:11px;line-height: 17.5px;text-align:right;"> </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="padding-top: 25px">
                                <table style="width:100%;font-size:11px; line-height:17.5px; font-family: 'Nunito Sans', sans-serif;">
                                    <tbody>
                                        <tr>
                                            <td style="padding-bottom:17px; font-size:14px;font-family: 'Nunito Sans', sans-serif;"><b> INVOICE DETAILS </b></td>
                                        </tr>
                                        <tr>
                                            <td style="padding:12px 14px 17px 14px; border:1px solid #222; border-radius:3px;;font-size:11px; line-height:17.5px; font-family: 'Nunito Sans', sans-serif;">
                                                <table style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="font-size:11px; line-height:17.5px; font-family: 'Nunito Sans', sans-serif;line-height: 17.5px">
                                                                <b>Name</b> 
                                                                <div style="padding-top:5px"><?= $model['tenantName'] ?></div>
                                                            </td>
                                                            <?php if (!empty($model['penaltyAmount'])) { ?> 
                                                                <td style="font-size:11px; line-height:17.5px; font-family: 'Nunito Sans', sans-serif;line-height: 17.5px">
                                                                    <b>Original Amount</b> 
                                                                    <div style="padding-top:5px">Rs <?= Yii::$app->userdata->getFormattedMoney($model['originalAmount']) ?></div>
                                                                </td>
                                                                <td style="font-size:11px; line-height:17.5px; font-family: 'Nunito Sans', sans-serif;line-height: 17.5px">
                                                                    <b>Penalty</b> 
                                                                    <div style="padding-top:5px">Rs <?= Yii::$app->userdata->getFormattedMoney($model['penaltyAmount']) ?></div>
                                                                </td>
                                                            <?php } ?> 
                                                            <td style="text-align:right;font-size:11px; line-height:17.5px; font-family: 'Nunito Sans', sans-serif;">
                                                                <b>AMOUNT TO BE PAID</b> 
                                                                <div style="padding-top:5px;font-size: 11px">Rs <?= Yii::$app->userdata->getFormattedMoney($model['totalAmount']) ?></div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-size:11px; line-height:17.5px; font-family: 'Nunito Sans', sans-serif;padding-top:17px;">
                                                                <b>Description</b> 
                                                                <div style="padding-top:5px">Rent due for the month of <?= date('M Y', strtotime($model['dueDate'])) ?></div>
                                                            </td>
                                                            <td colspan="4" style="text-align:right;font-size:11px; line-height:17.5px; font-family: 'Nunito Sans', sans-serif;padding-top:17px;">
                                                                <b>Due Date*</b> 
                                                                <div style="padding-top:5px"><?= $model['dueDate'] ?></div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-right:10px;font-size:11px; line-height:17.5px; font-family: 'Nunito Sans', sans-serif;vertical-align:top;width:30%"> <br /> <a target="_blank" href="<?= \yii\helpers\Url::home(true) . 'paytm/payrentfromweb' ?>?d=<?= urlencode($model['data']) ?>"> <button style="background: #59ABE3;width: 40%;text-align: center;height: 45px;font-size: 16px;font-weight: bolder;color: white;">Pay Now</button> </a> </td>
                        </tr>
                        <!--<tr>
                            <td colspan="2" style="padding-top:23px;font-family: 'Nunito Sans', sans-serif;">
                                <table style="width:100%;font-size:11px">
                                    <tbody>
                                        <tr>
                                            <td style="padding-bottom:17px; font-size:14px;font-family: 'Nunito Sans', sans-serif;"><b> Convenience Charges (excludes GST) </b></td>
                                        </tr>
                                        <tr>
                                            <td style="padding:12px 14px 17px 14px; border:1px solid #222; border-radius:3px;font-family: 'Nunito Sans', sans-serif;">
                                                <table cellspacing="0" id="table-conveniance" style="table-layout: fixed;width: 100%;" class="table table-striped">
                                                    <thead style='font-size:11px; line-height:17.5px; font-family: 'Nunito Sans', sans-serif;'> 
                                                           <tr>
                                                            <th style="text-align: left;">Instrument</th>
                                                            <th style="text-align: right;">Range</th>
                                                            <th style="text-align: right;">Charges</th>
                                                        </tr>
                                                    </thead> 
                                                    <tbody style="font-size:11px; line-height:17.5px; font-family: 'Nunito Sans', sans-serif;">
                                                        <tr>
                                                            <td style="border-bottom: 1px solid black;">Credit Card</td>
                                                            <td style="text-align: right; border-bottom: 1px solid black;"></td>
                                                            <td style="text-align: right;border-bottom: 1px solid black;">1.1%</td>
                                                        </tr>
                                                        <tr class="tab_row_color">
                                                            <td style="border-bottom: 1px solid black;" rowspan="2">Debit Card</td>
                                                            <td style="text-align: right;">Amount <= Rs 2,000</td>
                                                            <td style="text-align: right;">0.85%</td>
                                                        </tr>
                                                        <tr class="tab_row_color">
                                                           <td>Debit Card</td> 
                                                            <td style="text-align: right; border-bottom: 1px solid black;" class="tab_row_color">Amount > Rs 2,000</td>
                                                            <td class="tab_row_color" style="text-align: right; border-bottom: 1px solid black;">1%</td>
                                                        </tr>
                                                        <tr>
                                                            <td rowspan="2" style="border-bottom: 1px solid black;">UPI</td>
                                                            <td style="text-align: right;">Amount <= Rs 2,000</td>
                                                            <td style="text-align: right;">0.40%</td>
                                                        </tr>
                                                        <tr>
                                                           <td>UPI</td> 
                                                            <td style="text-align: right; border-bottom: 1px solid black;">Amount > Rs 2,000</td>
                                                            <td style="text-align: right; border-bottom: 1px solid black;">0.65%</td>
                                                        </tr>
                                                        <tr class="tab_row_color">
                                                            <td style="border-bottom: 1px solid black;">Paytm Wallet</td>
                                                            <td style="text-align: right; border-bottom: 1px solid black;"></td>
                                                            <td style="text-align: right; border-bottom: 1px solid black;">1%</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Netbanking</td>
                                                            <td style="text-align: right;"></td>
                                                            <td style="text-align: right;">Rs 12</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>-->
                        <tr>
                            <td style="padding-right:10px;font-size:11px; line-height:17.5px; font-family: 'Nunito Sans', sans-serif;vertical-align:top;width:30%"> <br /> * Please remember to pay by due date to avoid penalty charges. </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>