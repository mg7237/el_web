<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Fee Details';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .table_td_style{
        width: 20%;
        font-size: 12px;
        font-weight: 600;
    } 
    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
        padding: 8px;
        line-height: 1.42857143;
        vertical-align: top;
        border-top: 1px solid #fff !important;
        font-size: 12px;
    } 
    .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
        border: inherit;
    }
    .tbl_style{
        width: 100%;
        /*margin-left: 20px;*/
        border: 2px solid #ddd !important;
        box-shadow: 0px 2px 2px black;
        -moz-box-shadow: 0px 2px 2px black,;
        -webkit-box-shadow: 1px 1px 1px 1px #ddd;
    }
    .container
    {
        width:100%;
    }
    .feed-details-tbl
    {
        /*border: 1px solid #dddddd !important;*/
    }
    .feed-details-tbl tr:nth-child(even){ background-color:#fff !important;}
    .feed-details-tbl tr:nth-child(odd){background-color:#fafafa !important; }
    .feed-details-tbl thead tr:nth-child(odd){ background-color:#fff !important;}
    .dclass
    {
        background-color: #fafafa !important;
    }
    table {
        /*margin: 15px 0;
        border: 1px solid black;
        table-layout: auto;
        width: 100%;*/ /* must have this set */
        font-family: arial, sans-serif;
        border-collapse: inherit;
        width: 100%;

        table-layout: fixed;
    }
    td, th {
        /* border: 1px solid #dddddd; */
        text-align: left;
        padding-left: 30px;
        height:64px;
        word-wrap: break-word;
    }
    .bgstyle {
        background-color: #fafafa !important;
        background-color: var(--white);
    }

    .box+.box {
        margin-top: 2.5rem;
    }

    .box {
        background-color: #e9e9e9;
        padding: 6.25rem 1.25rem;
    }

    .inputfile {
        width: 0.1px;
        height: 0.1px;
        opacity: 0;
        overflow: hidden;
        position: absolute;
        z-index: -1;
    }

    .well {
        min-height: 236px;
        padding: 0px !important;
        margin-bottom: 20px;
        background-color: #f5f5f5;
        border: 1px solid #e3e3e3;
        border-radius: 4px;
        -webkit-box-shadow: inset 0 1px 1px #e9e9e9;
        box-shadow: inset 0 1px 1px #e9e9e9;
        width: 617px;
    }

    .input-group {
        position: relative;
        display: table;
        border-collapse: separate;
        width: 75%;
        padding-top: 15px;
    }

    input.form-control {
        border-top: none;
        border-right: navajowhite;
        background: #fafafa;
        box-shadow: none;
        border-left: navajowhite;
        padding-left: 0;
    }

    button#first_button {
        background-color: #F44336;
        border-color: #f44336;
        width: 39%;
        /* margin-top: -15px; */
        /* margin-top: -42px; */
        width: 140px;
        height: 40px;
        border-radius: 3px;
        font-size: 13px;
        font-weight: bold;
        font-style: normal;
        font-stretch: normal;
    }
    .buttonSubmit, .buttonSubmit:hover, .buttonSubmit:focus {
        background-color: #F44336;
        border-color: #f44336;
        width: 39%;
        width: 140px;
        height: 40px;
        border-radius: 3px;
        font-size: 13px;
        font-weight: bold;
        font-style: normal;
        font-stretch: normal;
    }
    .feed-details-tbl tr th
    {			

        font-size: 12px;
        font-weight: 600;
    }
    .feed-details-tbl tr td
    {
        font-size: 1px;

    }
    .feedtls-summary tr th
    {
        font-size: 12px;
        font-weight: 600;
        background-color: transparent !important;
    }
    .table>thead>tr>th
    {
        vertical-align: top;
        width:auto;
    }
    @media screen and ( max-width: 767px){
        .show-mobile {
            width: 90%;
            margin-left: 15px;
            margin-top: 60px;
        }
        .buttonSubmit{
            width: 100%;
            margin-top: 10px;
        }
        .padrt{ 
            padding-right: 0;
        }
        .crd-style{
            color: white;
            border-bottom: 1px solid white;
        }
    }

    @media screen and (min-width: 768px) and (max-width: 987px){
        .buttonSubmit{
            width: 100%;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .padrt{ 
            padding-right: 0;
        }
    }
</style>
<div id="content">
    <button type="button" id="sidebarCollapse" class="btn btn-info navbar-btn">
        <i class="glyphicon glyphicon-align-left"></i>
        <span></span>
    </button>
    <div class="container" style="margin-top: -42px;">

        <div class="col-md-4">
            <h4>FEE DETAILS</h5>
        </div>
        <!-- <div class="col-md-6">
            <button id="first_button" class="btn btn-info btn-lg pull-right">Edit</button>
        </div -->

    </div>
    <hr>


    <?php $form = ActiveForm::begin(['method' => 'POST', 'action' => Url::to(['advisers/feesdetails'])], ['options' => ['enctype' => 'multipart/form-data', 'class' => 'form-inline']]); ?>
    <div class="form-group col-md-12 padrt">

        <div class="col-md-4">

            <select class="form-control selectyear" name="year">
                <?php
                if ($year == '') {
                    $cur_year = date('Y');
                    for ($year1 = ($cur_year - 10); $year1 <= ($cur_year); $year1++) {
                        if ($year1 == $cur_year) {
                            echo '<option value="' . $year1 . '" selected="selected">' . $year1 . '</option>';
                        } else {
                            echo '<option value="' . $year1 . '">' . $year1 . '</option>';
                        }
                    }
                } else {
                    $cur_year = date('Y');
                    for ($year1 = ($cur_year - 10); $year1 <= ($cur_year); $year1++) {
                        if ($year1 == $year) {
                            echo '<option value="' . $year1 . '" selected="selected">' . $year1 . '</option>';
                        } else {
                            echo '<option value="' . $year1 . '">' . $year1 . '</option>';
                        }
                    }
                }
                ?>
            </select>
        </div>

        <div class="col-md-4">

            <select class="form-control selectyear" name="month">
                <?php
                $formattedMonthArray = array(
                    "01" => "January", "02" => "February", "03" => "March", "04" => "April",
                    "05" => "May", "06" => "June", "07" => "July", "08" => "August",
                    "09" => "September", "10" => "October", "11" => "November", "12" => "December",
                );
                if ($month == '') {
                    foreach ($formattedMonthArray as $key => $mArray) {
                        echo '<option ' . (Yii::$app->request->get('month') == $key ? 'Selected' : '') . ' value="' . $key . '">' . $mArray . '</option>';
                    }
                } else {
                    foreach ($formattedMonthArray as $key => $mArray) {
                        echo '<option ' . ($month == $key ? 'Selected' : '') . ' value="' . $key . '">' . $mArray . '</option>';
                    }
                }
                ?>
            </select>
        </div>
        <div class="col-md-4"><?= Html::submitButton('Update', ['class' => 'btn btn-danger btn-lg pull-left savebutton buttonSubmit']) ?></div>

    </div>
    <?php ActiveForm::end(); ?>
    <?php
    $pms = \app\models\PmsPropertyConfigurations::find()->one();
//echo "<pre>";print_r($feedetail);echo "</pre>";die;
    ?>   	


    <div class="col-md-12" style="margin:15px 0px 0px 15px;">

        <table class="table table-bordered tbl_style hide-mobile" frame="box">
            <thead>
                <tr>
                    <th>Referred Client Type</th>
                    <th>Referred Client Name</th>
                    <th>Property Name</th>
                    <th>Property Type</th>


                    <th>PMS Fee</th>
                    <th>Slab</th>
                    <th>Applicable Fee %</th>
                    <th>Applicable Fee</th>
                    <th>TDS</th>
                    <th>Net Due</th>
                    <!--th>Payment Mode</th-->
                    <th>Payment Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $totalAmount = 0;
                if (count($feedetail) == 0) {
                    ?>
                    <tr>
                        <td colspan="11" align="center"><span><i class="fa fa-search" aria-hidden="true"></i> No Record Found</span></td>

                    </tr>
                    <?php
                } else {
                    $i = 1;
                    foreach ($feedetail as $key => $value) {
                        if ($i % 2 == 0) {
                            $dclass = "";
                        } else {
                            $dclass = "dclass";
                        }
                        ?>
                        <tr class="<?php echo $dclass; ?>">
                            <td><?= ($value->source_type == '2') ? 'Tenant' : 'Owner' ?></td>
                             <!--td><?= ($value->source_type == '2') ? Yii::$app->userdata->getFullNameById($value->source_id) : Yii::$app->userdata->getOwnerIdByProperty($value->source_id) ?></td-->
                            <td><?= $value->source_name ?></td>
                            <?php
                            if ($value->source_type == '2') {
                                $payment_id = explode(",", $value->payment_id);
                                $property_idd = \app\models\TenantPayments::findOne(['id' => $payment_id]);
                                if (!empty($property_idd)) {
                                    $property_idd = $property_idd->property_id;
                                } else {
                                    $property_idd = '';
                                }

                                $property_name = \app\models\Properties::findOne(['id' => $property_idd]);
                                if (!empty($property_name)) {
                                    $property_name1 = $property_idd->property_name;
                                } else {
                                    $property_name1 = '';
                                }
                            } else {
                                $property_name1 = '';
                            }
                            ?>
                            <td><?= $value->property_name ?></td>
                            <td><?= $value->property_type ?></td>


                            <td><?= $value->pms_comission ?></td>
                            <td><?= $value->slab ?></td>
                            <td><?= $value->commission_percentage ?></td>
                            <td><?= $value->comission_amount ?></td>
                            <td><?= $value->tds ?></td>
                            <td><?= $value->payable_amount ?></td>
                                                            <!--td><?= ($value->payment_status == '1') ? Yii::$app->userdata->getPaymentModeType($value->payment_type) : '' ?></td-->
                            <td><?= ($value->payment_status == '0') ? '' : Date('d-M-Y', strtotime($value->payment_date)) ?></td>
                            <?php
                            $totalAmount = $totalAmount + $value->payable_amount;
                            ?>
                        </tr>
                        <?php
                    }
                }
                ?>   




            </tbody>
        </table>

        <!-- mobile view -->
        <section class="show-mobile">
            <div class="continer">
                <?php
                $totalAmount = 0;
                if (count($feedetail) == 0) {
                    ?>
                    <tr>
                        <td colspan="11" align="center"><span><i class="fa fa-search" aria-hidden="true"></i> No Record Found</span></td>

                    </tr>
                    <?php
                } else {
                    $i = 1;
                    foreach ($feedetail as $key => $value) {
                        if ($i % 2 == 0) {
                            $dclass = "";
                        } else {
                            $dclass = "dclass";
                        }
                        ?>
                        <?php
                        if ($value->source_type == '2') {
                            $payment_id = explode(",", $value->payment_id);
                            $property_idd = \app\models\TenantPayments::findOne(['id' => $payment_id]);
                            if (!empty($property_idd)) {
                                $property_idd = $property_idd->property_id;
                            } else {
                                $property_idd = '';
                            }

                            $property_name = \app\models\Properties::findOne(['id' => $property_idd]);
                            if (!empty($property_name)) {
                                $property_name1 = $property_idd->property_name;
                            } else {
                                $property_name1 = '';
                            }
                        } else {
                            $property_name1 = '';
                        }
                        ?>
                        <div class="row card-bacolor">
                            <div class="cust-card">

                                <form>
                                    <div class="form-row cust-form">
                                        <div class="form-group col-md-12">
                                            <label for="inputPassword4">Referred Client Type : </label>
                                            <p class="crd-style"><?= ($value->source_type == '2') ? 'Tenant' : 'Owner' ?></p>
                                            <!-- <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="<?= ($value->source_type == '2') ? 'Tenant' : 'Owner' ?>"> -->
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="inputPassword4">Referred Client Name : </label>
                                            <p class="crd-style"><?= $value->source_name ?></p>
                                            <!-- <input type="text" class="form-control cust-formcontrol" id="inputPassword4" placeholder="<?= ($value->source_type == '2') ? Yii::$app->userdata->getFullNameById($value->source_id) : Yii::$app->userdata->getOwnerIdByProperty($value->source_id) ?>"> -->
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="inputEmail4">Property Name : </label>
                                            <p class="crd-style"><?= $value->property_name ?></p>
                                            <!-- <input type="text" class="form-control cust-formcontrol" id="inputEmail4" placeholder="<?= $property_name1 ?>"> -->
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="inputEmail4">Property Type : </label>
                                            <p class="crd-style"><?= $value->property_type ?></p>
                                            <!-- <input type="text" class="form-control cust-formcontrol" id="inputEmail4" placeholder="<?= $value->property_type ?>"> -->
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="inputEmail4">PMS Fee : </label>
                                            <p class="crd-style"><?= $value->pms_comission ?></p>
                                            <!-- <input type="text" class="form-control cust-formcontrol" id="inputEmail4" placeholder="<?= $value->pms_comission ?>"> -->
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="inputEmail4">Slab : </label>
                                            <p class="crd-style"><?= $value->slab ?></p>
                                            <!-- <input type="text" class="form-control cust-formcontrol" id="inputEmail4" placeholder="<?= $value->slab ?>"> -->
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="inputEmail4">Applicable Fee% : </label>
                                            <p class="crd-style"><?= $value->commission_percentage ?></p>
                                            <!-- <input type="text" class="form-control cust-formcontrol" id="inputEmail4" placeholder="<?= $value->applicable_fees ?>"> -->
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="inputEmail4">Applicable Fee : </label>
                                            <p class="crd-style"><?= $value->comission_amount ?></p>
                                            <!-- <input type="text" class="form-control cust-formcontrol" id="inputEmail4" placeholder="<?= $value->comission_amount ?>"> -->
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="inputEmail4">TDS : </label>
                                            <p class="crd-style"><?= $value->tds ?></p>
                                            <!-- <input type="text" class="form-control cust-formcontrol" id="inputEmail4" placeholder="<?= $value->tds ?>"> -->
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="inputEmail4">Net Due : </label>
                                            <p class="crd-style"><?= $value->payable_amount ?></p>
                                            <!-- <input type="text" class="form-control cust-formcontrol" id="inputEmail4" placeholder="<?= $value->payable_amount ?>"> -->
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="inputEmail4">Payment Date : </label>
                                            <p class="crd-style"><?= ($value->payment_status == '0') ? '' : Date('d-M-Y', strtotime($value->payment_date)) ?></p>
                                            <!-- <input type="text" class="form-control cust-formcontrol" id="inputEmail4" placeholder="<?= ($value->payment_status == '0') ? '' : Date('d-M-Y', strtotime($value->payment_date)) ?>"> -->
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                        <br />
                        <?php
                        $totalAmount = $totalAmount + $value->payable_amount;
                        ?>
                        <?php
                    }
                }
                ?> 
            </div>
        </section>
        <!-- ./mobile view -->


    </div>


    <div class="col-md-12 feedtls-sum-div" style="">

        <table class="table table-bordered tbl_style">
            <thead>
                <tr>
                    <th> Total Summary Amount</th>
                    <th><span class="summary-amount"> Rs <?= $totalAmount ?> </span> </th>
                </tr>
            </thead>
        </table>

    </div>


</div>


