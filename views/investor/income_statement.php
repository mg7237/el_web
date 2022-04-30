<?php
$this->title = 'PMS-  Property Income Statements';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\grid\GridView;

$data = [];
?>
<style>
    input#upload-income {
        padding: 3px 5px 4px 5px;
        border: 1px solid lightgrey;
    }

    input#search-property-income {
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
</style>
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="income-query-form" method="post" action="">
                    <input id="income-query-property-id" type="hidden" name="property_id" value="" />
                    <div class="row">
                        <div class="col-4 col-sm-4 col-md-4">
                            Income Type
                        </div>
                        <div class="col-4 col-sm-4 col-md-4">
                            <?php
                            $expType = \app\models\IncomeType::find()->all();
                            ?>
                            <select class="" name="income_type">
                                <option value="">&nbsp; Select income type &nbsp;</option>;
                                <?php foreach ($expType as $type) { ?>
                                    <option value="<?= $type->income_code ?>"><?= $type->income_name ?></option>;
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
                            <input class="income-query-date" style="width: 90%;" type="text" name="created_from" value="" placeholder="From" />
                        </div>
                        <div class="col-4 col-sm-4 col-md-4">
                            <input class="income-query-date" style="width: 90%;" type="text" name="created_to" value="" placeholder="To" />
                        </div>
                    </div>
                    <p></p>
                    <div class="row">
                        <div class="col-4 col-sm-4 col-md-4">
                            Income Amount
                        </div>
                        <div class="col-4 col-sm-4 col-md-4">
                            <input style="width: 90%;" type="text" name="income_amount_from" value="" placeholder="From" />
                        </div>
                        <div class="col-4 col-sm-4 col-md-4">
                            <input style="width: 90%;" type="text" name="income_amount_to" value="" placeholder="To" />
                        </div>
                    </div>
                    <p></p>
                    <div class="row">
                        <div class="col-4 col-sm-4 col-md-4">
                            Due Date
                        </div>
                        <div class="col-4 col-sm-4 col-md-4">
                            <input class="income-query-date" style="width: 90%;" type="text" name="due_date_from" value="" placeholder="From" />
                        </div>
                        <div class="col-4 col-sm-4 col-md-4">
                            <input class="income-query-date" style="width: 90%;" type="text" name="due_date_to" value="" placeholder="To" />
                        </div>
                    </div>
                    <p></p>
                    <div class="row">
                        <div class="col-4 col-sm-4 col-md-4">
                            Paid On
                        </div>
                        <div class="col-4 col-sm-4 col-md-4">
                            <input class="income-query-date" style="width: 90%;" type="text" name="paid_on_from" value="" placeholder="From" />
                        </div>
                        <div class="col-4 col-sm-4 col-md-4">
                            <input class="income-query-date" style="width: 90%;" type="text" name="paid_on_to" value="" placeholder="To" />
                        </div>
                    </div>
                    <p></p>
                    <div class="row">
                        <div class="col-3 col-sm-3 col-md-3">
                            Approved By
                        </div>
                        <div class="col-3 col-sm-3 col-md-3">
                            <input id="query-income-approved-by" style="width: 90%;" type="text" name="approved_by" value="" placeholder=" ...... " autocomplete="off" />
                        </div>
                        <div class="col-3 col-sm-3 col-md-3">
                            Created By
                        </div>
                        <div class="col-3 col-sm-3 col-md-3">
                            <input id="search-income-created-by" style="width: 90%;" type="text" name="created_by_placeholder" value="" placeholder=" ...... " autocomplete="off" />
                            <input id="search-income-created-by-value" type="hidden" name="created_by" value="" />
                        </div>
                    </div>
                    <p></p>
                </form>
                <div class="row">
                    <div class="col-12">
                        <button class="btn query-income-terms">Search</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="row">
                <div class="col-lg-6 col-sm-6">
                    <?php $form = ActiveForm::begin(['id' => 'property-income-form', 'options' => ['name' => 'property-income-form', 'class' => 'form-inline', 'enctype' => 'multipart/form-data']]); ?>
                    <button style="margin: 0px !important;" type="button" id="sidebarCollapse" class="btn btn-info navbar-btn">
                        <i class="glyphicon glyphicon-align-left"></i>
                        <span></span>
                    </button>
                    <label class="control-label" for="search-property-income">Income &nbsp;&nbsp;</label>
                    <div class="input-group">
                        <input type="text" id="search-property-income" class="form-control ui-autocomplete-input" name="property_name" placeholder="Search Property*" autocomplete="off">
                    </div>
                    <div class="input-group">
                        <input style="display: none;" id="upload-income" type="file" name="income_csv" class="form-control">
                    </div>
                    <input id="property-id-income" type="hidden" name="property_id" value="">
                    <?php ActiveForm::end(); ?>
                </div>
                <div class="col-lg-1 col-sm-1">
                    <button style="display: none; width: 95%;" id="upload-income-btn" class="btn-2 btn-primary">Upload</button>
                </div>
                <div class="col-lg-1 col-sm-1">
                    <button data-toggle="modal" data-target="#myModal" style="display: none; width: 95%;" id="query-income-btn" class="btn-2 btn-primary">Query</button>
                </div>
                <div class="col-lg-1 col-sm-1">
                    <button style="display: none; width: 95%;" id="add-income-btn" class="btn-2 btn-primary after-sel-csv">Add</button>
                </div>
                <div class="col-lg-1 col-sm-1">
                    <button style="display: none; width: 95%;" id="delete-income-btn" class="btn-2 btn-primary after-sel-csv">Delete</button>
                </div>
                <div class="col-lg-1 col-sm-1">
                    <button disabled="disabled" style="display: none; width: 95%;" id="save-csv-income" class="btn after-sel-csv">Save</button>
                </div>
                <div class="col-lg-1 col-sm-1">
                    <button disabled="disabled" style="display: none; width: 95%;" id="discard-changes" class="btn after-sel-csv">Undo</button>
                </div>
            </div>
            <p></p>
            <div class="row" style="">
                <div class="col-lg-2 col-sm-2">
                    <button style="display: none; width: 95%;" id="download-income-csv" class="btn-2 btn-primary after-sel-csv">Export</button>
                </div>
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