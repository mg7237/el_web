<!DOCTYPE html>
<html>
    <head>
        <title>Payment</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js">
        <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js">
        <style>
            .topmsg{
                align-items: center;
                margin-top: 24px;
                text-align: center;
            }

            #top-msg{
                text-transform: uppercase;
                margin: 0;
                line-height: 1.42857143;
                text-align: center;
            }

            #neft-continue, #paytm-continue, #paytm-conv-charges, #neft-reference-no, #neft-info-box, #in-case, #paytm-netbanking-continue {
                display: none;
            }

            #neft-reference-no {

            }

            .tab_row_color{
                background-color: #e8e8e8;
            }

            #table-conveniance td {
                padding: 5px;
            }

            #table-conveniance tr th {
                padding: 3px 3px;
                font-size: 11px;
            }
        </style>
    </head>
    <body>
        <div class="container-fluid">   
            <div class="row">
                <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="topmsg">
                        <h4 id="top-msg">HOW WOULD YOU LIKE TO MAKE THE PAYMENT?</h4>
                    </div>
                </div>
            </div>
            <hr />
            <div class="row">
                <div class="col-12 col-xs-12 col-sm-7 col-md-5 col-lg-6">
                    <div class="radio">
                        <label>
                            <input type="radio" name="optradio" onclick="hideNeftRefBox(); hidePaytmcharges(); showPaytmNetBankingContinue();" name="exampleRadios" id="paytmnetbanking" value="option1" >
                            <!--<img src="<?= yii\helpers\Url::home(true); ?>images/icons/paytm.png" style=" width: 80px; height: 70px; margin-top: -35px; margin-bottom: -30px; ">-->
                            NetBanking (zero convenience charges)
                        </label>
                    </div>
                </div>
                <div class="col-12 col-xs-12 col-sm-2 col-md-5 col-lg-4" >
                    <!--<span style="font-size: 12px;">NetBanking (zero convenience charges)</span>-->
                </div>
                <div class="col-12 col-xs-12 col-sm-3 col-md-2 col-lg-2" >
                    <form method="post" action="<?= yii\helpers\Url::home(true) ?>paytm/payrentfrompaytmnetbankingapp">
                        <input type="hidden" name="x-api-key" value="<?= $token ?>" />
                        <input type="hidden" name="tpRowId" value="<?= $tpRowId ?>" />
                        <button id="paytm-netbanking-continue" style="width: 100%;" type="submit" class="btn btn-primary btn">Continue</button>
                    </form>
                </div>
            </div>
            <hr />
            <div class="row">
                <div class="col-12 col-xs-12 col-sm-9 col-md-7 col-lg-6" >
                    <div class="radio">
                        <label>
                            <input type="radio" name="optradio" onclick="hideNeftRefBox(); showPaytmcharges(); hidePaytmNetBankingContinue();" name="exampleRadios" id="paytm" value="option1" >
                            <!--<img src="<?= yii\helpers\Url::home(true); ?>images/icons/paytm.png" style=" width: 80px; height: 70px; margin-top: -35px; margin-bottom: -30px; ">-->
                            Wallet, UPI, Credit & Debit Cards (convenience charges apply)
                        </label>
                    </div>
                </div>
                <div class="col-12 col-xs-12 col-sm-0 col-md-3 col-lg-4" >
                    <!--<span style="font-size: 12px;">Wallet, UPI, Credit & Debit Cards</span>-->
                </div>
                <div class="col-12 col-xs-12 col-sm-3 col-md-2 col-lg-2" >
                    <form method="post" action="<?= yii\helpers\Url::home(true) ?>paytm/payrentfromapp">
                        <input type="hidden" name="x-api-key" value="<?= $token ?>" />
                        <input type="hidden" name="tpRowId" value="<?= $tpRowId ?>" />
                        <button id="paytm-continue" style="width: 100%;" type="submit" class="btn btn-primary btn">Continue</button>
                    </form>
                </div>
                <div id="paytm-conv-charges" class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12" >
                    <div class="row">
                        <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12" >
                            <p style="margin-bottom: 0px;height: 5px;">&nbsp;</p>
                            <div id="conveniance-charges-box" style="text-align: center; font-size: 17px;"><small style="font-weight: bolder;">Convenience Charges (excludes GST)</small></div>
                            <p style="margin-bottom: 0px;height: 5px;">&nbsp;</p>
                            <table id="table-conveniance" style="table-layout: fixed;width: 100%; text-align: center; font-size: 10px;" class="">
                                <thead style='background: black; color: white;'>
                                    <tr>
                                        <th>Instrument</th>
                                        <th>Range</th>
                                        <th>Charges</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Credit Card</td>
                                        <td></td>
                                        <td>1.1%</td>
                                    </tr>      
                                    <tr class="tab_row_color">
                                        <td rowspan="2">Debit Card</td>
                                        <td>Amount <= <i style="" class="fa">&#xf156;</i> 2,000</td>
                                        <td>0.85%</td>
                                    </tr>
                                    <tr class="tab_row_color">
                                        <!--<td>Debit Card</td>-->
                                        <td class="tab_row_color">Amount > <i style="" class="fa">&#xf156;</i> 2,000</td>
                                        <td class="tab_row_color">1%</td>
                                    </tr>
                                    <tr>
                                        <td rowspan="2" >UPI</td>
                                        <td>Amount <= <i style="" class="fa">&#xf156;</i> 2,000</td>
                                        <td>0.40%</td>
                                    </tr>
                                    <tr>
                                        <!--<td>UPI</td>-->
                                        <td>Amount > <i style="" class="fa">&#xf156;</i> 2,000</td>
                                        <td>0.65%</td>
                                    </tr>
                                    <tr class="tab_row_color">
                                        <td>Paytm Wallet</td>
                                        <td></td>
                                        <td>1%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <hr />
            <div class="row">
                <div class="col-12 col-xs-12 col-sm-3 col-md-4 col-lg-4" >
                    <div class="radio">
                        <label>
                            <input type="radio" name="optradio" onclick="showNeftRefBox(); hidePaytmcharges(); hidePaytmNetBankingContinue();" name="exampleRadios" id="neft-reference-input" value="option2" >
                            NEFT / IMPS
                        </label>
                    </div>
                </div>
                <div class="col-12 col-xs-12 col-sm-6 col-md-6 col-lg-6" style="font-size: 12px;">
                    <div id="in-case">
                        In case you have already paid through <strong>NEFT </strong>or<strong> IMPS</strong>
                        then please enter reference number.
                        <p style="margin-bottom: 0px;">&nbsp;</p>
                    </div>
                    <form method="post" action="<?= yii\helpers\Url::home(true) ?>site/payrentneftapp">
                        <input type="text" name="neft-reference-no" class="form-control" id="neft-reference-no" value="" placeholder=" NEFT/IMPS reference number" required="required">
                        <p style="margin-bottom: 0px;">&nbsp;</p>
                </div>
                <div class="col-12 col-xs-12 col-sm-3 col-md-2 col-lg-2" >
                    <input type="hidden" name="x-api-key" value="<?= $token ?>" />
                    <input type="hidden" name="tpRowId" value="<?= $tpRowId ?>" />
                    <button id="neft-continue" style="width: 100%" type="submit" class="btn btn-primary btn"  disabled="disabled">Continue</button>
                    </form>
                </div>
                <div id="neft-info-box" style="font-size: 12px;" class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12" >
                    <div class="row">
                        <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12" >
                            <div class="" style="margin-top: 10px;">
                                * Note that the payment status will be marked as paid only once the payment has been reconciled at our bank account.
                                For instant payment status update & receipt we recommend you  utilize payment gateway options.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </body>
</html>
<script>
    function showNeftRefBox() {
        $('#neft-reference-no').css('display', 'block');
        $('#in-case').css('display', 'block');
        $('#neft-info-box').css('display', 'block');
        $('#neft-continue').css('display', 'block');
    }

    function hideNeftRefBox() {
        $('#neft-reference-no').css('display', 'none');
        $('#in-case').css('display', 'none');
        $('#neft-info-box').css('display', 'none');
        $('#neft-continue').css('display', 'none');

    }

    function showPaytmcharges() {
        $('#paytm-conv-charges').css('display', 'block');
        $('#paytm-continue').css('display', 'block');
    }

    function hidePaytmcharges() {
        $('#paytm-conv-charges').css('display', 'none');
        $('#paytm-continue').css('display', 'none');
    }

    function hidePaytmNetBankingContinue() {
        $('#paytm-netbanking-continue').hide();
    }

    function showPaytmNetBankingContinue() {
        $('#paytm-netbanking-continue').show();
    }

    $(document).ready(function () {
        $('#neft-reference-no').keyup(function () {
            var val = this.value;
            if (val != '' && val.length > 5) {
                $('#neft-continue').removeAttr('disabled');
            } else {
                $('#neft-continue').attr('disabled', 'disabled');
            }
        });
    })
</script>