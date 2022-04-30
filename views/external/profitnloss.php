<?php
$this->title = 'PMS-  Expense Reporting';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\grid\GridView;

$data = [];
?>
<style>
    input#upload-expense {
        padding: 3px 5px 4px 5px;
        border: 1px solid lightgrey;
    }

    input:-moz-read-only {
        background-color: lightgrey;
    }

    input:read-only {
        background-color: lightgrey;
    }

    .ui-autocomplete {
        position:relative;
        cursor:default;
        z-index:9999 !important;
    }

    .toggle-side-nav {
        display: none;
    }

    .toggle-page-wrapper {
        margin: -27px 0 0 0px !important;
    }
</style>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="row">
                <button style="margin: 0px !important;" type="button" id="sidebarCollapse" class="btn btn-info navbar-btn">
                    <i class="glyphicon glyphicon-align-left"></i>
                    <span></span>
                </button>
                <h4>Profit & Loss</h4>
                <p>&nbsp;</p>
                <?php $form = ActiveForm::begin(['id' => 'pnl-reporting-form', 'options' => ['name' => 'property-expense-form', 'class' => 'form-inline', 'enctype' => 'multipart/form-data']]); ?>
                <div class="col-lg-4 col-sm-4">
                        <!--<button style="margin: 0px !important;" type="button" id="sidebarCollapse" class="btn btn-info navbar-btn"><i class="glyphicon glyphicon-align-left"></i><span></span></button>-->
                    <label class="control-label" for="search-property-expense">Select Property &nbsp;&nbsp;</label>
                    <div class="input-group" style="width: 65%;">
                        <input type="text" id="search-property-expense" class="form-control ui-autocomplete-input" name="property_name" placeholder="Search Property*" autocomplete="off">
                    </div>
                    <input id="property-id-expense" type="hidden" name="property_id" value="">
                </div>
                <div class="col-lg-2 col-sm-2">
                    <select id="pnl-statement-type" style="width: 99%;" class="form-control" name="pnl_statement_type">
                        <option value="1">Monthly</option>
                        <option value="2">Quarterly</option>
                        <option value="3">Calendar Year</option>
                        <option value="4">Financial Year</option>
                    </select>
                </div>
                <div class="col-lg-4 col-sm-4">
                    <select id="pnl-statement-from-monthly" style="width: 40%;" class="form-control" name="pnl_statement_from_month">
                        <option value="1">January</option>
                        <option value="2">February</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                    <select id="pnl-statement-from-quarterly" style="width: 40%; display: none;" class="form-control" name="pnl_statement_from_quarterly">
                        <option value="1">January to March</option>
                        <option value="2">April to June</option>
                        <option value="3">July to September</option>
                        <option value="4">October to December</option>
                    </select>
                    <select id="pnl-statement-from-year" style="width: 51%;" class="form-control" name="pnl_statement_from_year">
                        <option value="2019">2019</option>
                        <option value="2020">2020</option>
                    </select>
                    <select id="pnl-statement-from-fy" style="width: 51%; display: none;" class="form-control" name="pnl_statement_from_fy">
                        <option value="2019">2019 - 2020</option>
                        <option value="2020">2020 - 2021</option>
                    </select>
                </div>
                <?php ActiveForm::end(); ?>
                <div class="col-lg-1 col-sm-1">
                    <button style="margin-right: 5px; height: 32px;" id="generate-pnl-report" class="btn-2 btn-primary">Generate</button>
                </div>
            </div>
            <p></p>
        </div>
        <p></p>
        <button style="display: none;" id="download-pnl-report" class="btn-2 btn-primary">Export</button>
        <div class="col-lg-12 col-sm-12 adminusermanag" style="overflow-x: auto;margin-top: 10px;">
            <div class="pnl-reporting-list"></div>
        </div>
        <div class="col-lg-12 col-sm-12" style="overflow-x: auto;margin-top: 10px;">
            <div class="viewTransModalBox"></div>
        </div>
    </div> 
</div>
<script>
    var isUnload = true;
</script>