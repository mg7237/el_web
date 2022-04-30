<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use app\models\Properties;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Wallet';
$this->params['breadcrumbs'][] = $this->title;
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<style>
    .propertynamebox {
        background: #fdfdfd none repeat scroll 0 0;
        border: 1px solid #cccccc;
        float: left;
        margin-bottom: 5px;
        padding: 14px;
        width: 49%;
        margin-right: 1%;
    }
    .bgstyle {
        background-color: #fafafa !important;
        background-color: var(--white);
        margin-left: -15px !important;
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
    @media screen and (max-width: 768px){
        .boxstyle_data{
            width: 100% !important;
        }
        .boxstyle_p{
            overflow: scroll;
        }
    }
    .boxstyle_data {
        background-color: #59abe3;
        width: 473px;
        height: auto;
        color: white !important;
        margin: 25px;
    }

    .boxstyle_p>p {
        color: white;
    }

    .boxstyle_p {
        margin: 5px;
        color: white;
    }

    .form-group.boxstyle_p {
        /* padding: 20px; */
        color: white;
    }
    .flagclass
    {
        background: #4e9100 !important;
    }
    hr {
        margin-top: 10px !important;
        margin-bottom: 10px !important;
        border: 0;
        border-top: 1px solid #eee;
    }
    @media screen and (max-width: 767px){
        .cust-edit{
            margin-top: 0;
            float: left !important;
        }
        #content{
            width: 100% !important;
        }
        .boxstyle_data{
            margin-left: 0;
        }
        .bgstyle{
            margin-left: 15px !important;
        }
    }

    @media (min-width: 769px) and (max-width: 991px){
        .cust-edit{
            margin-right: 32% !important;
        }
    }

    @media (min-width: 992px) and (max-width: 1166px){
        .cust-edit{
            float: left !important;
        }
        .boxstyle_p{
            overflow: scroll;
        }
    }
    .flagclass{
        padding: 10px;
    }
    .boxstyle_data{
        padding: 10px;
    }
</style>
<?php //echo "<pre>";print_r($walletHistory);echo "</pre>";die;?>
<div id="content" class="col-md-10 col-sm-10">
    <button type="button" id="sidebarCollapse" class="btn btn-info navbar-btn">
        <i class="glyphicon glyphicon-align-left"></i>
        <span></span>
    </button>
    <div class="container" style="margin-top: -42px;">
        <div class="col-md-4 col-sm-4">
            <h4 id="personal_info">My Wallet : ₹ <?= $walletAmount ? $walletAmount->amount : 0 ?></h4>
        </div>
        <div class="col-md-6 col-sm-6">
            <?php if ($walletAmount->amount > 0) { ?>
                <?php $form = ActiveForm::begin(['id' => 'refund_to_bank_frm_wall_master', 'options' => ['enctype' => 'multipart/form-data']]); ?>
                <input type="hidden" name="data" id="collection-data" value="" />
                <input type="hidden" name="op_level" value="1" /> 

                <button id="first_button" class="btn btn-info btn-lg pull-right cust-edit">Refund to bank</button>
                <?php ActiveForm::end(); ?>
            <?php } ?>
        </div>
    </div>

    <div class="form-row">
        <div class="col-md-12 bgstyle">
            <?php
            if ($walletHistory) {
                $collection = [];
                $flag = 0;
                foreach ($walletHistory as $walletData) {
                    $collection[$flag]['property_id'] = $walletData->property_id;
                    $collection[$flag]['child_id'] = $walletData->child_id;
                    $collection[$flag]['amount'] = $walletData->amount;
                    if ($walletData->transaction_type == 2) {
                        $flagclass = "flagclass";
                    } else {
                        $flagclass = "";
                    }
                    ?>
                    <?php
                    $property = Properties::find(['property_name'])->where(['id' => $walletData->property_id])->one();
                    ?>
                    <div class="col-md-6 boxstyle_data <?php echo $flagclass; ?>">
                        <div class="form-group ">
                            <div class="boxstyle_p">
                                <p>Property Name</p>
                                <p><?= (isset($property->property_name) ? $property->property_name : '') ?></p>
                                <!-- <p><?php echo 'Bangalore'; ?></p> -->
                                <hr>
                            </div>
                        </div>
                        <div class="form-group boxstyle_p">
                            <p>Amount</p>
                            <p>₹ <?= $walletData->amount; ?></p>
                            <hr>
                        </div>
                        <div class="form-group boxstyle_p">
                            <p>Status</p>
                            <p>
                                <?php
                                if ($walletData->transaction_type == 1) {
                                    echo 'Added To Wallet';
                                } else if ($walletData->transaction_type == 2) {
                                    echo 'Refund Initiated';
                                } else if ($walletData->transaction_type == 3) {
                                    echo 'Adjusted Against Rent';
                                } else {
                                    echo 'Status Not Available';
                                }
                                ?>
                            </p>
                            <hr>
                        </div>
                        <div class="form-group boxstyle_p">
                            <p>Update On</p>
                            <p><?= $walletData->updated_date; ?></p>
                            <hr>
                        </div>

                    </div>


                    <?php
                    $flag++;
                }
            } else {
                ?>
                <div class="propertynamebox"> <p> No Transaction</p></div>

            <?php } ?>
        </div>
    </div>
</div>
<script>
<?php if (!empty($collection)) { ?>
        var collection = <?php echo json_encode($collection); ?>;
        $('#collection-data').val(JSON.stringify(collection));
<?php } ?>
</script>
