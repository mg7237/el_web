<?php
$this->title = 'PMS - Applicant Interested';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\grid\GridView;

$data = [];
$showApproveBtn = true;
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

    .approved-row {
        background-color: #03AC13 !important;
    }

    #summarized-income {
        padding: 10px 15px;
        border: 1px solid black;
        display: none;
    }
</style>
<div id="page-wrapper">
    <div class="row">
        <div class="col-12">
            <h4><b>Applicant Interested</b></h4>
        </div>
    </div>
    <p></p>
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="row">
                <div class="col-lg-6 col-sm-6">
                    <?php $form = ActiveForm::begin(['id' => 'property-fav-record-form', 'options' => ['name' => 'property-income-form', 'class' => 'form-inline', 'enctype' => 'multipart/form-data']]); ?>
                    <label class="control-label" for="search-property-income">Search Property &nbsp;&nbsp;</label>
                    <div class="input-group">
                        <input required="" value="<?= (!empty($subData['property_list'])) ? $subData['property_list'] : '' ?>" type="text" id="property-list-inner" class="form-control ui-autocomplete-input" name="property_list" placeholder="Search Property*" autocomplete="off">
                    </div>
                    <input type="hidden" id="property-id-inner" name="property_id" value="<?= (!empty($subData['property_id'])) ? $subData['property_id'] : '' ?>">
                    <div class="input-group">
                        <button type="submit" style="width: 95%;" id="search-property-record" class="btn-2 btn-primary">Search</button>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
                <div class="col-lg-1 col-sm-1">

                </div>
            </div>
        </div>
        <p></p>
        <?php if (!empty($subData)) { ?>
            <div class="col-lg-12 col-sm-12 adminusermanag" style="overflow-x: auto;margin-top: 10px;">
                <div class="">
                    <table class="table table-bordered tbl_style hide-mobile">
                        <thead>
                            <tr>
                                <th class="td_style text-center">Applicant Name</th>
                                <th class="td_style text-center">Shortlist Type</th>
                                <th class="td_style text-center">Scheduled Date/Time</th>
                                <th class="td_style text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($favProData)) {
                                $i = 1;
                                foreach ($favProData as $key => $value) {
                                    if ($i % 2 == 0) {
                                        $dclass = "";
                                    } else {
                                        $dclass = "dclass";
                                    }

                                    $favVal = '';
                                    if (empty($value['shortlist_type'])) {
                                        $favVal = 'N/A';
                                    }

                                    if ($value['shortlist_type'] == 1) {
                                        $favVal = 'Favourite';
                                    } else if ($value['shortlist_type'] == 2) {
                                        $favVal = 'Booked';
                                    } else if ($value['shortlist_type'] == 3) {
                                        $favVal = 'Scheduled';
                                    }

                                    $schDateTime = '';
                                    if (!empty($value['visit_date'])) {
                                        $schDateTime = date('d-M-Y h:i A', strtotime($value['visit_date'] . ' ' . $value['visit_time']));
                                    } else {
                                        $schDateTime = 'N/A';
                                    }
                                    ?>
                                    <tr class="<?php echo $dclass; ?>">
                                        <td width="20%" class="td_style"><?= $value['full_name'] ?></td>
                                        <td width="40%" class="td_style"><?= $favVal ?></td>
                                        <td width="25%" class="td_style"><?= $schDateTime ?></td>
                                        <td width="15%" class="td_style"><button id="delete-fav-row" data-fv-row="<?= $value['id'] ?>" class="btn btn-info">Delete</button></td>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                            } else {
                                ?> 
                                <tr> <td colspan="4">No record found</td></tr>
                                <?php
                            }
                            ?> 
                        </tbody>
                    </table>
                </div>
            </div>
        <?php } ?>
    </div> 
</div>
<script>
    $(document).ready(function () {
    });
</script>