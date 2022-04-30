<?php
$this->title = 'PMS-  Property Expense';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\grid\GridView;

$data = [];
$showApproveBtn = true;
?>
<style>
    input#upload-expense {
        padding: 3px 5px 4px 5px;
        border: 1px solid lightgrey;
    }

    input#search-property-expense {
        width: 160px;
        margin-right: 6px;
        height: 28px;
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

    .approved-row {
        background-color: #03AC13 !important;
    }

    #summarized-expense {
        padding: 10px 15px;
        border: 1px solid black;
        display: none;
    }
</style>
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="expense-query-form" method="post" action="">
                    <input id="expense-query-property-id" type="hidden" name="property_id" value="" />
                    <div class="row">
                        <div class="col-4 col-sm-4 col-md-4">
                            Expense Type
                        </div>
                        <div class="col-4 col-sm-4 col-md-4">
                            <?php
                            $expType = \app\models\ExpenseType::find()->all();
                            ?>
                            <select class="" name="expense_type">
                                <option value="">&nbsp; Select expense type &nbsp;</option>;
                                <?php foreach ($expType as $type) { ?>
                                    <option value="<?= $type->expense_code ?>"><?= $type->expense_name ?></option>;
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-3 col-sm-3 col-md-3" style="padding-left: 30px;">
                            Attachment
                        </div>
                        <div class="col-1 col-sm-1 col-md-1">
                            <input style="width: 90%;" type="checkbox" name="is_attached" value="1" />
                        </div>
                    </div>
                    <p></p>
                    <div class="row">
                        <div class="col-4 col-sm-4 col-md-4">
                            Created
                        </div>
                        <div class="col-4 col-sm-4 col-md-4">
                            <input class="expense-query-date" style="width: 90%;" type="text" name="created_from" value="" placeholder="From" />
                        </div>
                        <div class="col-4 col-sm-4 col-md-4">
                            <input class="expense-query-date" style="width: 90%;" type="text" name="created_to" value="" placeholder="To" />
                        </div>
                    </div>
                    <p></p>
                    <div class="row">
                        <div class="col-4 col-sm-4 col-md-4">
                            Expense Amount
                        </div>
                        <div class="col-4 col-sm-4 col-md-4">
                            <input style="width: 90%;" type="text" name="expense_amount_from" value="" placeholder="From" />
                        </div>
                        <div class="col-4 col-sm-4 col-md-4">
                            <input style="width: 90%;" type="text" name="expense_amount_to" value="" placeholder="To" />
                        </div>
                    </div>
                    <p></p>
                    <div class="row">
                        <div class="col-4 col-sm-4 col-md-4">
                            Invoice Date
                        </div>
                        <div class="col-4 col-sm-4 col-md-4">
                            <input class="expense-query-date" style="width: 90%;" type="text" name="invoice_date_from" value="" placeholder="From" />
                        </div>
                        <div class="col-4 col-sm-4 col-md-4">
                            <input class="expense-query-date" style="width: 90%;" type="text" name="invoice_date_to" value="" placeholder="To" />
                        </div>
                    </div>
                    <p></p>
                    <div class="row">
                        <div class="col-4 col-sm-4 col-md-4">
                            Paid On
                        </div>
                        <div class="col-4 col-sm-4 col-md-4">
                            <input class="expense-query-date" style="width: 90%;" type="text" name="paid_on_from" value="" placeholder="From" />
                        </div>
                        <div class="col-4 col-sm-4 col-md-4">
                            <input class="expense-query-date" style="width: 90%;" type="text" name="paid_on_to" value="" placeholder="To" />
                        </div>
                    </div>
                    <p></p>
                    <div class="row">
                        <div class="col-3 col-sm-3 col-md-3">
                            Vendor
                        </div>
                        <div class="col-3 col-sm-3 col-md-3">
                            <input id="query-expense-vendor" style="width: 90%;" type="text" name="vendor_name" value="" placeholder=" ...... " autocomplete="off" />
                        </div>
                        <div class="col-3 col-sm-3 col-md-3">
                            Created By
                        </div>
                        <div class="col-3 col-sm-3 col-md-3">
                            <input id="search-created-by" style="width: 90%;" type="text" name="created_by_placeholder" value="" placeholder=" ...... " autocomplete="off" />
                            <input id="search-created-by-value" type="hidden" name="created_by" value="" />
                        </div>
                    </div>
                    <p></p>
                    <div class="row">
                        <div class="col-3 col-sm-3 col-md-3">
                            Paid By
                        </div>
                        <div class="col-3 col-sm-3 col-md-3">
                            <input id="query-expense-paid-by" style="width: 90%;" type="text" name="paid_by" value="" placeholder=" ...... " autocomplete="off" />
                        </div>
                        <div class="col-3 col-sm-3 col-md-3">
                            Approved By
                        </div>
                        <div class="col-3 col-sm-3 col-md-3">
                            <input id="query-expense-approved-by" style="width: 90%;" type="text" name="approved_by_placeholder" value="" placeholder=" ...... " autocomplete="off" />
                            <input id="query-expense-approved-by-value" type="hidden" name="approved_by" value="" />
                        </div>
                    </div>
                    <p></p>
                    <div class="row">
                        <div class="col-3 col-sm-3 col-md-3">
                            Month
                        </div>
                        <div class="col-3 col-sm-3 col-md-3">
                            <input class="expense-query-month" style="width: 90%;" type="text" name="month" value="" placeholder=" ...... " />
                        </div>
                    </div>
                    <p></p>
                </form>
                <div class="row">
                    <div class="col-12">
                        <button class="btn query-expense-terms">Search</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="page-wrapper">
    <div class="row">
        <div class="col-12">
            <h4><b>Property Expense</b></h4>
        </div>
    </div>
    <p></p>
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="row">
                <div class="col-lg-6 col-sm-6">
                    <?php $form = ActiveForm::begin(['id' => 'property-expense-form', 'options' => ['name' => 'property-expense-form', 'class' => 'form-inline', 'enctype' => 'multipart/form-data']]); ?>
                    <button style="margin: 0px !important;" type="button" id="sidebarCollapse" class="btn btn-info navbar-btn">
                        <i class="glyphicon glyphicon-align-left"></i>
                        <span></span>
                    </button>
                    <label class="control-label" for="search-property-expense">Select Property &nbsp;&nbsp;</label>
                    <div class="input-group">
                        <input type="text" id="search-property-expense" class="form-control ui-autocomplete-input" name="property_name" placeholder="Search Property*" autocomplete="off">
                    </div>
                    <div class="input-group">
                        <input style="display: none;" id="upload-expense" type="file" name="expense_csv" class="form-control">
                    </div>
                    <input id="property-id-expense" type="hidden" name="property_id" value="">
                    <?php ActiveForm::end(); ?>
                </div>
                <div class="col-lg-1 col-sm-1">
                    <button style="display: none; width: 95%;" id="upload-expense-btn" class="btn-2 btn-primary">Upload</button>
                </div>
                <div class="col-lg-1 col-sm-1">
                    <button data-toggle="modal" data-target="#myModal" style="display: none; width: 95%;" id="query-expense-btn" class="btn-2 btn-primary">Query</button>
                </div>
                <div class="col-lg-1 col-sm-1">
                    <button style="display: none; width: 95%;" id="add-expense-btn" class="btn-2 btn-primary after-sel-csv">Add</button>
                </div>
                <div class="col-lg-1 col-sm-1">
                    <button style="display: none; width: 95%;" id="delete-expense-btn" class="btn-2 btn-primary after-sel-csv">Delete</button>
                </div>
                <div class="col-lg-1 col-sm-1">
                    <button disabled="disabled" style="display: none; width: 95%;" id="save-csv" class="btn after-sel-csv">Save</button>
                </div>
                <div class="col-lg-1 col-sm-1">
                    <button disabled="disabled" style="display: none; width: 95%;" id="discard-changes" class="btn after-sel-csv">Undo</button>
                </div>
            </div>
            <p></p>
            <div class="row" style="">
                <div class="col-lg-2 col-sm-2">
                    <button style="display: none; width: 95%;" id="download-csv" class="btn-2 btn-primary after-sel-csv">Export</button>
                </div>
                <div class="col-lg-2 col-sm-2">
                    <button style="display: none; width: 95%;" id="toggle-table-view" class="btn-2 btn-primary after-sel-csv">Expand/Collapse</button>
                </div>
                <?php
                $userType = Yii::$app->user->identity->user_type;
                if ($userType == 7) {
                    $opUser = \app\models\OperationsProfile::findOne(['operations_id' => Yii::$app->user->identity->id]);
                    if ($opUser->role_code == 'OPEXE' && $opUser->role_value != 'ELHQ') {
                        $showApproveBtn = false;
                    }
                } else if ($userType == 6) {
                    $salesUser = \app\models\SalesProfile::findOne(['sale_id' => Yii::$app->user->identity->id]);
                    if ($salesUser->role_code == 'SLEXE') {
                        $showApproveBtn = false;
                    }
                }
                ?>
                <?php if ($showApproveBtn) { ?>
                    <div class="col-lg-2 col-sm-2" style="float: right;">
                        <button style="display: none; width: 98%;" id="approve-expense-rows" class="btn after-sel-csv">Approve</button>
                    </div>
                <?php } ?>
            </div>
            <p></p>
            <div id="summarized-expense" class="row" style="">
                <table class="table table-striped" style="width: 20%;">
                    <?php foreach ($this->params['expenseTypes'] as $type) { ?>
                        <tr>
                            <td id="expense-type-block-head-<?= $type->expense_code ?>">
                                <?= $type->title ?>
                            </td>
                            <td id="expense-type-block-<?= $type->expense_code ?>">
                                <span id="summarized-expense-value-<?= $type->expense_code ?>"></span>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <th>
                            Total Expense
                        </th>
                        <td id="summarized-expense-value" style="font-weight: bold;"></td>
                    </tr>
                </table>
            </div>
        </div>
        <p></p>
        <div class="col-lg-12 col-sm-12 adminusermanag" style="overflow-x: auto;margin-top: 10px;">
            <div class="csv-list-items"></div>
        </div>
    </div> 
</div>
<script>
    var isUnload = true;
</script>