<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Payment Details Property - ' . Yii::$app->userdata->getPropertyNameById($id);
//echo "<pre>";print_r($paymentsummary);echo "</pre>";die;
$this->params['breadcrumbs'][] = $this->title;
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<div id="content">
    <button type="button" id="sidebarCollapse" class="btn btn-info navbar-btn">
        <i class="glyphicon glyphicon-align-left"></i>
        <span></span>
    </button>
    <div class="container" style="margin-top: -50px; width: 725px;">
        <div class="col-md-12">
            <h4><?= strtoupper(Html::encode(Yii::$app->userdata->getPropertyNameById($id))) ?> - PAYMENT STATEMENT</h4>
        </div>
    </div>
    <hr>
    <div class="container">
        <div class="container-fluid">
            <h4 id="personal_info" style="margin-bottom: 22px !important;">PAYMENT DETAILS</h4> 
        </div>

    </div>
    <!--<div class="container">
        <br/>
    </div>--> 
    <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => Url::to(['site/ownerpayments?id=' . base64_encode($id)])], ['options' => ['enctype' => 'multipart/form-data', 'class' => 'form-inline']]); ?>
    <div class="container">
        <!--<div class="col-md-7" style="margin-left: -15px;">-->
        <div class="col-md-5">
            <div class="dropdown">
                <!--button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" style=" height: 50px;">Dropdown Example
        <span class="caret"></span></button>
                <ul class="dropdown-menu">
                    <li><a href="#">HTML</a></li>
                    <li><a href="#">CSS</a></li>
                    <li><a href="#">JavaScript</a></li>
                </ul-->
                <label for="exampleInputName2">Select Year</label>
                <select name="year" class= "form-control custom_dropdown">
                    <?php
                    $cur_year = date('Y');
                    for ($year = ($cur_year - 10); $year <= ($cur_year); $year++) {
                        if ($year == $cur_year) {
                            echo '<option value="' . $year . '" selected="selected">' . $year . '</option>';
                        } else {
                            echo '<option value="' . $year . '">' . $year . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-5">
            <div class="dropdown">
                <!--button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" style=" height: 50px;">Dropdown Example
                <span class="caret"></span></button>
                <ul class="dropdown-menu">
                    <li><a href="#">HTML</a></li>
                    <li><a href="#">CSS</a></li>
                    <li><a href="#">JavaScript</a></li>
                </ul-->
                <label for="exampleInputName2">Select month</label>
                <input type="hidden" name="search_acc_date" value="1" />
                <select name="month" class= "form-control custom_dropdown">
                    <?php
                    $formattedMonthArray = array(
                        "1" => "January", "2" => "February", "3" => "March", "4" => "April",
                        "5" => "May", "6" => "June", "7" => "July", "8" => "August",
                        "9" => "September", "10" => "October", "11" => "November", "12" => "December",
                    );

                    if (Yii::$app->request->get('month')) {
                        $month = Yii::$app->request->get('month');
                    } else {
                        if (!empty($paymentsummary)) {
                            $month = $paymentsummary->payment_month;
                        } else {
                            $month = '';
                        }
                    }
                    foreach ($formattedMonthArray as $key => $mArray) {
                        echo '<option ' . ($month == $key ? 'Selected' : '') . ' value="' . $key . '">' . $mArray . '</option>';
                    }
                    ?>

                </select>
            </div>
        </div>
        <!--</div>-->
        <!--<div class="col-md-5">-->
        <div class="col-md-2">
            <!--button type="button" class="btn btn-info" style=" height: 50px;">Update</button-->
            <?= Html::submitButton('Update', ['class' => 'btn btn-primary btn-lg savebutton buttonSubmit']) ?>
        </div>
        <!--</div>-->
    </div>

    <?php ActiveForm::end(); ?>
    <div class="container">
        <br/>
    </div>
    <?php $pms = \app\models\PmsPropertyConfigurations::find()->one(); ?>
    <?php if ($paymentsummary) { ?>
        <div class="col-md-12">
            <div class="table-responsive" style="box-shadow: inset 0 0 10px #dedbdb;margin-left: 15px;">
                <table class="table table-borderless" style="margin: 0px;margin-left: 2px;">
                    <thead>
                        <tr>
                            <th style="padding-left: 20px;" class="table-padd">Description</th>
                            <th style="float: right; margin-right: 10px;" class="table-padd">Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="trbg">
                            <td style="padding-left: 20px;" class="table-padd">Rental Coverage Days</td>
                            <td style="float: right; margin-right: 10px;" class="table-padd"><?php
                                if (!empty($paymentsummary)) {
                                    echo $paymentsummary->coverage_days;
                                } else {
                                    echo 0;
                                }
                                ?></td>
                        </tr>
                        <tr class="trbg">
                            <td style="padding-left: 20px;" class="table-padd">Total Rent</td>
                            <td style="float: right; margin-right: 10px;" class="table-padd"><?php
                                if (!empty($paymentsummary)) {
                                    echo Yii::$app->userdata->getformat($paymentsummary->total_rent);
                                } else {
                                    echo 0;
                                }
                                ?></td>
                        </tr>
                        <tr class="trbg">
                            <td style="padding-left: 20px;" class="table-padd">PMS Fees (<?php echo $pms_percentage; ?>%)</td>
                            <td style="float: right; margin-right: 10px;" class="table-padd"> <?php if ($tenantpayments['pms_commission'] != 0) { ?>- <?php } ?><?= $tenantpayments['pms_commission']; ?></td>
                        </tr>
                        <tr class="trbg">
                            <td style="padding-left: 20px;" class="table-padd">Furniture Rent</td>
                            <td style="float: right; margin-right: 10px;" class="table-padd"> <?php if (isset($paymentsummary->furniture_rental)) {
                                    if ($paymentsummary->furniture_rental != 0) {
                                        ?>- <?php }
                    }
                    ?><?php
                    if (!empty($paymentsummary)) {
                        echo Yii::$app->userdata->getformat($paymentsummary->furniture_rental);
                    } else {
                        echo 0;
                    }
                                ?></td>
                        </tr>
                        <tr class="trbg">
                            <td style="padding-left: 20px;" class="table-padd">TDS</td>
                            <td style="float: right; margin-right: 10px;" class="table-padd"> <?php if (isset($paymentsummary->tds)) {
                                if ($paymentsummary->tds != 0) {
                                    ?>- <?php }
                            }
                            ?><?php
                            if (!empty($paymentsummary)) {
                                echo Yii::$app->userdata->getformat($paymentsummary->tds);
                            } else {
                                echo 0;
                            }
                                ?></td>
                        </tr>
                        <!--tr class="trbg">
    <td style="padding-left: 20px;" class="table-padd">service tax</td>
    <td style="float: right; margin-right: 10px;" class="table-padd"> - <?= $tenantpayments['service_tax']; ?></td>
    </tr-->
                        <tr class="trbg">
                            <td style="padding-left: 20px;" class="table-padd">GST</td>
                            <td style="float: right; margin-right: 10px;" class="table-padd"> <?php if (isset($paymentsummary->gst)) {
                                if ($paymentsummary->gst != 0) {
                                    ?>- <?php }
                            }
                            ?><?php
                            if (!empty($paymentsummary)) {
                                echo Yii::$app->userdata->getformat($paymentsummary->gst);
                            } else {
                                echo 0;
                            }
                            ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="container">
            <br/>
        </div>
        <div class="col-md-12 cust-t" style="margin-top: 15px;">
            <div class="table-responsive" style="box-shadow: inset 0 0 10px #dedbdb;margin-left: 15px;">
                <table class="table table-borderless">
                    <thead>
                        <tr style=" height: 45px;">
                            <th class="cust-trh" style="padding-left: 20px;padding-bottom: 3px;">Net Payment Due</th>
                            <th class="cust-trh1" style="float: right;margin-right: 10px;padding-top: 22px;height: 45px;">Rs. <?php
                            if (!empty($paymentsummary)) {
                                echo Yii::$app->userdata->getformat($paymentsummary->net_amount);
                            } else {
                                echo 0;
                            }
                            ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <div class="col-md-12" style="margin-top: 15px; display: none;" >
            <div class="" style="box-shadow: inset 0 0 10px #dedbdb;margin-left: 15px;" >
                <table class="table table-borderless hide-mobile" >
                    <thead>
                        <tr>
                            <th style="padding-left: 20px;height: 50px;">Payment Amount</th>
                            <th>Payment Mode</th>
                            <th>Payment Status</th>
                            <th>Payment Date</th>
                        </tr>
                    </thead>
                    <tbody>
                                <?php
                                //echo "<pre>";print_r($ownerpayments);echo "</pre>";die;
                                /* if ($ownerpayments) {
                                  foreach ($ownerpayments as $ownerpayment) { */
                                ?> 
                        <tr>
                            <td style="padding-left: 20px;"><?php if ($paymentsummary->net_amount > 0) { ?>Rs. <?php
                            if (!empty($paymentsummary)) {
                                echo Yii::$app->userdata->getformat($paymentsummary->net_amount);
                            } else {
                                echo 0;
                            }
                            ?> <?php } ?></td>
                            <td><?php if ($paymentsummary->net_amount > 0) { ?>NEFT<?php } ?></td>
                            <td><?php if ($paymentsummary->net_amount > 0) { ?><?php
                            if (!empty($paymentsummary)) {
                                if ($paymentsummary->payment_status == 1) {
                                    echo "Paid";
                                } else {
                                    echo "Unpaid";
                                }
                            } else {
                                echo "Unpaid";
                            }
                            ?><?php } ?></td>
                            <td><?php if ($paymentsummary->net_amount > 0) { ?><?php
                            if (!empty($paymentsummary->payment_date)) {
                                echo date('d-M-Y', strtotime($paymentsummary->payment_date));
                            }
                            ?><?php } ?></td>
                        </tr>
    <?php
    /* }
      } else { */
    ?>  
                        <!--tr> 
                        <table>
                            <tr>
                                <div style="text-align: center;height: 70px;font-size: 16px;font-weight: normal;font-style: normal;font-stretch: normal;line-height: 4;letter-spacing: 1.1px;color: #848c99;background: #fafafa;width: 99%;margin-left: 4px;"><img src="<?php //echo Url::home(true);   ?>images/oimg/ic-search-property.png"/> No payment made for owner </div>
                                </tr>
                                </table>
                            
                        </tr-->
    <?php //}   ?> 
                    </tbody>
                </table>
            </div>
        </div>
        <!-- mobile view -->
        <section class="show-mobile">
            <div class="continer">
                <div class="row card-bacolor">
                    <div class="cust-card">
                        <form>
                            <div class="form-row cust-form">
                                <div class="form-group col-md-12">
                                    <label for="inputEmail4">Payment Amount : </label>
                                    <p class="crd-style">₹ <?php if ($paymentsummary->net_amount > 0) { ?>Rs. <?php
                                            if (!empty($paymentsummary)) {
                                                echo Yii::$app->userdata->getformat($paymentsummary->net_amount);
                                            } else {
                                                echo 0;
                                            }
                                            ?> <?php } ?></p>
                                    <!-- <input type="text" class="form-control cust-formcontrol" id="inputEmail4" placeholder="₹ <?php if ($paymentsummary->net_amount > 0) { ?>Rs. <?php
                                        if (!empty($paymentsummary)) {
                                            echo Yii::$app->userdata->getformat($paymentsummary->net_amount);
                                        } else {
                                            echo 0;
                                        }
                                        ?> <?php } ?>"> -->
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="inputPassword4">Payment Mode : </label>
                                    <p class="crd-style"><?php if ($paymentsummary->net_amount > 0) { ?>NEFT<?php } ?></p>
                                    <!-- <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="<?php if ($paymentsummary->net_amount > 0) { ?>NEFT<?php } ?>"> -->
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="inputPassword4">Payment Status : </label>
                                    <p class="crd-style"><?php if ($paymentsummary->net_amount > 0) { ?><?php
                                        if (!empty($paymentsummary)) {
                                            if ($paymentsummary->payment_status == 1) {
                                                echo "Paid";
                                            } else {
                                                echo "Unpaid";
                                            }
                                        } else {
                                            echo "Unpaid";
                                        }
                                        ?><?php } ?></p>
                                    <!-- <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="<?php if ($paymentsummary->net_amount > 0) { ?><?php
            if (!empty($paymentsummary)) {
                if ($paymentsummary->payment_status == 1) {
                    echo "Paid";
                } else {
                    echo "Unpaid";
                }
            } else {
                echo "Unpaid";
            }
            ?><?php } ?>"> -->
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="inputPassword4">Payment Date : </label>
                                    <p class="crd-style"><?php if ($paymentsummary->net_amount > 0) { ?><?php
            if (!empty($paymentsummary->payment_date)) {
                echo date('d-M-Y', strtotime($paymentsummary->payment_date));
            }
            ?><?php } ?></p>
                                    <!-- <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="<?php if ($paymentsummary->net_amount > 0) { ?><?php
            if (!empty($paymentsummary->payment_date)) {
                echo date('d-M-Y', strtotime($paymentsummary->payment_date));
            }
            ?><?php } ?>"> -->
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <!-- ./mobile view -->




<?php } else { ?>
        <div class="col-md-12">
            <div class="row cust-nopayment">
                <p style="color: black;">No payment data available. If you think that's not quite right, 
                    please contact your dedicated support person or write to 
                    <u><a style="color: blue;" href="mailto:support@easyleases.in">support@easyleases.in</a></u><br /><br />
                    <b>Note: </b>Typically we process payment statement on or before 15<sup>th</sup> of every month.
                </p>
            </div>
        </div>
<?php } ?>

</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
        });
    });
</script>	
<style>
    .buttonSubmit,.buttonSubmit:hover
    {

        width: 100% !important;
        margin-top: 25px;
        width: 140px;
        height: 48px;
        border-radius: 3px;
        font-size: 13px;
        font-weight: bold;
        font-style: normal;
        font-stretch: normal;
    }
    select.form-control
    {
        width: 100% !important;
    }
    .col-md-5,.col-md-2
    {
        padding-right: 0px;
        padding-left: 15px;
    }
    .navbar-btn {
        margin-top: -35px;
        margin-left: -15px;
        padding: 7px;
    }
    .container
    {
        width:100%;
    }
    .table-borderless>tbody>tr>td, .table-borderless>tbody>tr>th, .table-borderless>tfoot>tr>td, .table-borderless>tfoot>tr>th, .table-borderless>thead>tr>td, .table-borderless>thead>tr>th {
        border: none;
    }

    .table-padd{
        padding-top: 20px !important;
        padding-bottom: 20px !important;
    }
    .trbg{
        background-color: #fafafa;
        border-bottom: 1px solid rgba(50, 58, 71, 0.1);
    }
    .custom_dropdown{
        width: 280px !important;
        height: 48px !important;
        border-radius: 3px !important;
        background-color: #fafafa !important;
        background-color: var(--white) !important;
        border: solid 1px #e9ebf0 !important;
    }
    .custom_up_btn{
        width: 114px;
        height: 48px;
        border-radius: 3px;
        background-color: #59abe3;
        /* background-color: var(--dark-sky-blue); */
        /* background-color: white; */
        margin-left: -15px;
    }
    @media screen and (max-width: 767px){
        #content{
            width: 100%;
        }
        .cust-t{
            margin-top: -20px !important;
        }
        .cust-trh{
            padding-bottom: 11px !important;
        }
        .cust-trh1{
            padding-top: 15px !important;
        }
        .show-mobile {
            width: 81%;
            margin-left: 45px;
        }
        .crd-style{
            color: white;
            border-bottom: 1px solid white;
        }
    }
</style>