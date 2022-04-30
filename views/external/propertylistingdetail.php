<style>
    body {
        font-family: 'Oswald', sans-serif !important;
    }

    tr td img {
        height: 35px !important;
        width: 35px !important;
    }

    tr td {
        text-align: left !important;
    }

    input {
        width: 100px;
    }
</style>
<?php
$this->title = 'PMS - Property Listing Detail';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

// echo "<pre>";
// print_r($listing);
// die;
?>
<script src="../assets/5cf71ed/jquery.js"></script>
<div id="page-wrapper">
    <div class="row">
        <div class="col-md-12">
            <div class="parmentaddress" style="float: left;">
                <h1>Property Listing - <?= $Name ?></h1>
            </div>
            <div class="" style="float: right; padding-top: 25px;">
                <button class="btn btn-primary hidden save_prop_list" >Save</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="col-lg-2 selectleft">

            </div>
            <!-- <div class="col-lg-5 selectright pull-right"> -->
            <!-- <form class="navbar-form navbar-search" role="search"> -->
            <!-- <div class="input-group"> -->
              <!-- <input type="text" class="form-control" value="Name, email, phone number, address, status"> -->
            <!-- <div class="input-group-btn"> -->
              <!-- <button type="button" class="btn btn-search btn-danger selecttext"> <span class="glyphicon glyphicon-search"></span> <span class="label-icon"></span> </button> -->
            <!-- </div> -->
            <!-- </div> -->
            <!-- </form> -->
            <!-- </div> -->
        </div>
        <div class="col-lg-12 adminusermanag">
            <table class="table table-bordered tableheadingrow" cellpadding="5">
                <thead>
                    <?php
                    if ($Type == 3) {
                        ?>
                        <tr>
                            <th>Rent</th>
                            <th>Deposit</th>
                            <th>Token Amount</th>
                            <th>Maintenance</th>
                            <th>Availability From</th>
                            <th>Status</th>
                            <th>Operation</th>
                        </tr>
                        <?php
                    } else {
                        ?>
                        <tr>
                            <th>Unit Name</th>
                            <th>Rent</th>
                            <th>Deposit</th>
                            <th>Token Amount</th>
                            <th>Maintenance</th>
                            <th>Availability From</th>
                            <th>Status</th>
                            <th>Operation</th>
                        </tr>
                        <?php
                    }
                    ?>
                </thead>
                <tbody>
                    <?php
                    if ($Type == 3) {
                        ?>
                        <tr>
                            <td><input disabled id="prop_list_rent_0" type="text" name="rent" value="<?= $listing['rent']; ?>" /></td> 
                            <td><input disabled id="prop_list_deposit_0" type="text" name="rent" value="<?= $listing['deposit']; ?>" /></td>
                            <td><input disabled id="prop_list_token_amount_0" type="text" name="rent" value="<?= $listing['token_amount']; ?>" /></td>
                            <td><input disabled id="prop_list_maintenance_0" type="text" name="rent" value="<?= $listing['maintenance']; ?>" /></td>
                            <td><input disabled id="prop_list_availability_from_0" class="datepicker" type="text" name="rent" value="<?= $listing['availability_from']; ?>" /></td>
                            <?php
                            $booked_data = Yii::$app->userdata->getPropertyByPidHome(base64_decode($_GET['id']));
                            ?>
                            <td>
                                <?php if ($booked_data == 2) { ?>
                                    <select disabled id="prop_list_property_status_0" name="property_status" onchange="showalert(this);">
                                    <?php } else { ?>
                                        <select disabled id="prop_list_property_status_0" name="property_status">
                                        <?php } ?>
                                        <option <?php
                                        if ($listing['status'] == 0) {
                                            echo 'selected';
                                        }
                                        ?> value="0">Unlisted</option>
                                        <option <?php
                                        if ($listing['status'] == 1) {
                                            echo 'selected';
                                        }
                                        ?> value="1">Listed</option>
                                    </select>
                            </td>
                            <td>
    <?php //echo (true/* $booked_data == 3 */) ? '<a href="' . Url::home(true) . 'external/editlisting?property_id=' . $listing['property_id'] . '&type=' . $Type . '&main=' . $_GET['id'] . '" onclick="$(\'body\').on(\'hidden.bs.modal\', \'.modal\', function () { $(this).removeData(\'bs.modal\'); });"  data-toggle="modal" data-target="#myModal2">Edit</a>' : '<span style="color:red;">Property Booked</span>'   ?>
                                <input id="prop_list_id_0" type="hidden" name="id" value="<?= $listing['id'] ?>" />
                                <input id="prop_list_child_id_0" type="hidden" name="child_id" value="<?= $listing['property_id'] ?>" />
                                <input id="prop_list_type_0" type="hidden" name="type" value="<?= $Type ?>" />
                                <input id="prop_list_main_0" type="hidden" name="main" value="<?= $_GET['id'] ?>" />
                                <input id="prop_list_editable_0" class="is_editable" type="hidden" name="editable" value="0" />
                                <a href="javascript:void(0);" onclick="propertyListEditable(0, <?= $listing['property_id'] ?>, <?= $Type ?>, '<?= $_GET['id'] ?>');">Edit</a>
                            </td>
                        </tr>
                        <?php
                    } else if ($Type == 4) {
                        $array = Array();
                        $beds = Array();
                        $booked_data = Yii::$app->userdata->getPropertyByPidBeds(base64_decode($_GET['id']));
                        // $booked_data1=Yii::$app->userdata->getPropertyByPidRooms(base64_decode($_GET['id']));
                        // print_r($booked_data1);
                        // die;
                        // $completedata=array_unique(array_merge($booked_data,$booked_data1));

                        foreach ($listing as $key => $value) {
                            if (!in_array($value['parent_id'], $array)) {
                                $array[] = $value['parent_id'];
                            }
                            $beds[$value['parent_id']][] = $value['child_id'];
                            ?>
                            <tr>
                                <!--<td><?php if (Yii::$app->userdata->getChildPropertyType($value['child_id']) == 1) { ?>Room <?= array_search($value['parent_id'], $array) + 1 ?><?php } else { ?>Room <?= array_search($value['parent_id'], $array) + 1 ?><br/>Bed <?= array_search($value['child_id'], $beds[$value['parent_id']]) ?><?php } ?></td>-->
                                <td><?php if (Yii::$app->userdata->getChildPropertyType($value['child_id']) == 1) { ?> <img style="margin-left: -15px;" src="../images/icons/assets_7-list-page_2018-02-19/1642866-512-room-logo.png"> <?= Yii::$app->userdata->getRoomName($value['parent_id']) ?><?php } else { ?> <img style="height:30px; margin-bottom: 10px;" src="../images/icons/assets_7-list-page_2018-02-19/ic-bedrooms@2x.png"> <?= Yii::$app->userdata->getBedName($value['child_id']) ?><?php } ?></td>
                                <td><input disabled id="prop_list_rent_<?= $key ?>" type="text" name="rent" value="<?= $value['rent']; ?>" /></td>
                                <td><input disabled id="prop_list_deposit_<?= $key ?>" type="text" name="rent" value="<?= $value['deposit']; ?>" /></td>
                                <td><input disabled id="prop_list_token_amount_<?= $key ?>" type="text" name="rent" value="<?= $value['token_amount']; ?>" /></td>
                                <td><input disabled id="prop_list_maintenance_<?= $key ?>" type="text" name="rent" value="<?= $value['maintenance']; ?>" /></td>
                                <td><input disabled id="prop_list_availability_from_<?= $key ?>" class="datepicker" type="text" name="rent" value="<?= $value['availability_from']; ?>" /></td>
                                <td>
        <?php
        $listingStatus = Yii::$app->userdata->getPropertyStausByPropertyId2($value['child_id'], 2);
        ?>
                                    <!--<select disabled id="prop_list_property_status_<?= $key ?>" name="property_status" onchange="showalert(this);">-->
                                    <select disabled id="prop_list_property_status_<?= $key ?>" name="property_status">
                                        <option <?php
                                            if ($listingStatus == 0) {
                                                echo 'selected';
                                            }
                                            ?> value="0">Unlisted</option>
                                        <option <?php
                                            if ($listingStatus == 1) {
                                                echo 'selected';
                                            }
                                            ?> value="1">Listed</option>
                                    </select>
                                </td>
                                <td>
                                    <input id="prop_list_child_id_<?= $key ?>" type="hidden" name="child_id" value="<?= $value['child_id'] ?>" />
                                    <input id="prop_list_type_<?= $key ?>" type="hidden" name="type" value="<?= $Type ?>" />
                                    <input id="prop_list_main_<?= $key ?>" type="hidden" name="main" value="<?= $_GET['id'] ?>" />
                                    <input id="prop_list_editable_<?= $key ?>" class="is_editable" type="hidden" name="editable" value="0" />
                            <?php //echo (!in_array($value['child_id'], $booked_data)) ? '<a href="' . Url::home(true) . 'external/editlisting?property_id=' . $value['child_id'] . '&type=' . $Type . '&main=' . $_GET['id'] . '" onclick="$(\'body\').on(\'hidden.bs.modal\', \'.modal\', function () { $(this).removeData(\'bs.modal\'); });"  data-toggle="modal" data-target="#myModal2">Edit</a>' : '<a href="' . Url::home(true) . 'external/editlisting?property_id=' . $value['child_id'] . '&type=' . $Type . '&main=' . $_GET['id'] . '" onclick="$(\'body\').on(\'hidden.bs.modal\', \'.modal\', function () { $(this).removeData(\'bs.modal\'); });"  data-toggle="modal" data-target="#myModal2">Edit</a><span style="color:red;display: block;">Booked</span>' ?>
                                    <a href="javascript:void(0);" onclick="propertyListEditable(<?= $key ?>, <?= $value['child_id'] ?>, <?= $Type ?>, '<?= $_GET['id'] ?>');">Edit</a>
                                </td>
                            </tr>
                            <?php
                        }
                    } else if ($Type == 5) {
                        $array = Array();
                        $beds = Array();
                        $booked_data = Yii::$app->userdata->getPropertyByPidBeds(base64_decode($_GET['id']));
                        // $booked_data1=Yii::$app->userdata->getPropertyByPidRooms(base64_decode($_GET['id']));
                        // print_r($booked_data1);
                        // die;
                        // $completedata=array_unique(array_merge($booked_data,$booked_data1));

                        foreach ($listing as $key => $value) {
                            if (!in_array($value['parent_id'], $array)) {
                                $array[] = $value['parent_id'];
                            }
                            $beds[$value['parent_id']][] = $value['child_id'];
                            ?>
        <?php if ($value->child_id == $value->parent_id) { ?>
                                <tr>
                                    <!--<td><?php if (Yii::$app->userdata->getChildPropertyType($value['child_id']) == 1) { ?>Room <?= array_search($value['parent_id'], $array) + 1 ?><?php } else { ?>Room <?= array_search($value['parent_id'], $array) + 1 ?><br/>Bed <?= array_search($value['child_id'], $beds[$value['parent_id']]) ?><?php } ?></td>-->
                                    <td><?php if (Yii::$app->userdata->getChildPropertyType($value['child_id']) == 1) { ?> <img style="margin-left: -15px;" src="../images/icons/assets_7-list-page_2018-02-19/1642866-512-room-logo.png"> <?= Yii::$app->userdata->getRoomName($value['parent_id']) ?><?php } else { ?> <img style="height:30px; margin-bottom: 10px;" src="../images/icons/assets_7-list-page_2018-02-19/ic-bedrooms@2x.png"> <?= Yii::$app->userdata->getBedName($value['child_id']) ?><?php } ?></td>
                                    <td><input disabled id="prop_list_rent_<?= $key ?>" type="text" name="rent" value="<?= $value['rent']; ?>" /></td>
                                    <td><input disabled id="prop_list_deposit_<?= $key ?>" type="text" name="rent" value="<?= $value['deposit']; ?>" /></td>
                                    <td><input disabled id="prop_list_token_amount_<?= $key ?>" type="text" name="rent" value="<?= $value['token_amount']; ?>" /></td>
                                    <td><input disabled id="prop_list_maintenance_<?= $key ?>" type="text" name="rent" value="<?= $value['maintenance']; ?>" /></td>
                                    <td><input disabled id="prop_list_availability_from_<?= $key ?>" class="datepicker" type="text" name="rent" value="<?= $value['availability_from']; ?>" /></td>
                                    <td>
                                            <?php
                                            $listingStatus = Yii::$app->userdata->getPropertyStausByPropertyId2($value['child_id'], 2);
                                            ?>
                                        <!--<select disabled id="prop_list_property_status_<?= $key ?>" name="property_status" onchange="showalert(this);">-->
                                        <select disabled id="prop_list_property_status_<?= $key ?>" name="property_status">
                                            <option <?php
                                    if ($listingStatus == 0) {
                                        echo 'selected';
                                    }
                                    ?> value="0">Unlisted</option>
                                            <option <?php
                                    if ($listingStatus == 1) {
                                        echo 'selected';
                                    }
                                    ?> value="1">Listed</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input id="prop_list_child_id_<?= $key ?>" type="hidden" name="child_id" value="<?= $value['child_id'] ?>" />
                                        <input id="prop_list_type_<?= $key ?>" type="hidden" name="type" value="<?= $Type ?>" />
                                        <input id="prop_list_main_<?= $key ?>" type="hidden" name="main" value="<?= $_GET['id'] ?>" />
                                        <input id="prop_list_editable_<?= $key ?>" class="is_editable" type="hidden" name="editable" value="0" />
            <?php //echo (!in_array($value['child_id'], $booked_data)) ? '<a href="' . Url::home(true) . 'external/editlisting?property_id=' . $value['child_id'] . '&type=' . $Type . '&main=' . $_GET['id'] . '" onclick="$(\'body\').on(\'hidden.bs.modal\', \'.modal\', function () { $(this).removeData(\'bs.modal\'); });"  data-toggle="modal" data-target="#myModal2">Edit</a>' : '<a href="' . Url::home(true) . 'external/editlisting?property_id=' . $value['child_id'] . '&type=' . $Type . '&main=' . $_GET['id'] . '" onclick="$(\'body\').on(\'hidden.bs.modal\', \'.modal\', function () { $(this).removeData(\'bs.modal\'); });"  data-toggle="modal" data-target="#myModal2">Edit</a><span style="color:red;display: block;">Booked</span>'   ?>
                                        <a href="javascript:void(0);" onclick="propertyListEditable(<?= $key ?>, <?= $value['child_id'] ?>, <?= $Type ?>, '<?= $_GET['id'] ?>');">Edit</a>
                                    </td>
                                </tr>
            <?php
        }
    }
}
?>
                </tbody>
            </table>

        </div>

    </div>


</div>


<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modalbg modalbgpopup" role="document">
        <div class="modal-content">

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script>


    eleEditable = [];

    function propertyListEditable(eleId, childId, type, main) {
        $('#prop_list_rent_' + eleId).removeAttr('disabled');
        $('#prop_list_deposit_' + eleId).removeAttr('disabled');
        $('#prop_list_token_amount_' + eleId).removeAttr('disabled');
        $('#prop_list_maintenance_' + eleId).removeAttr('disabled');
        $('#prop_list_availability_from_' + eleId).removeAttr('disabled');
        $('#prop_list_property_status_' + eleId).removeAttr('disabled');
        $('#prop_list_editable_' + eleId).val(1);
        $('.save_prop_list').removeClass('hidden');
        var status = $.inArray(eleId, eleEditable);
        if (status == -1) {
            eleEditable.push(eleId);
        }
        console.log(eleEditable);
    }

    $('.save_prop_list').click(function () {
        startLoader();
        $(this).addClass('hidden');
        var arrLen = eleEditable.length;
        var i = 0;
        for (i; i < arrLen; i++) {
            console.log(eleEditable[i]);
            var propertyId = $('#prop_list_child_id_' + eleEditable[i]).val();
            var type = $('#prop_list_type_' + eleEditable[i]).val();
            var main = $('#prop_list_main_' + eleEditable[i]).val();
            var form = new FormData();
            var csrfToken = $('meta[name="csrf-token"]').attr("content");
            if ($('#prop_list_rent_' + eleEditable[i]).val() < 1) {
                alert('Rent cannot be equal to zero (0)');
                hideLoader();
                $('.save_prop_list').removeClass('hidden');
                return false;
            }
            form.append('_csrf', csrfToken);
            if (type == 3) {
                form.append("PropertyListing[id]", $('#prop_list_id_' + eleEditable[i]).val());
                form.append("PropertyListing[rent]", $('#prop_list_rent_' + eleEditable[i]).val());
                form.append("PropertyListing[deposit]", $('#prop_list_deposit_' + eleEditable[i]).val());
                form.append("PropertyListing[token_amount]", $('#prop_list_token_amount_' + eleEditable[i]).val());
                form.append("PropertyListing[maintenance]", $('#prop_list_maintenance_' + eleEditable[i]).val());
                form.append("PropertyListing[availability_from]", $('#prop_list_availability_from_' + eleEditable[i]).val());
                form.append("PropertyListing[status]", $('#prop_list_property_status_' + eleEditable[i]).val());
            } else {
                form.append("ChildPropertiesListing[rent]", $('#prop_list_rent_' + eleEditable[i]).val());
                form.append("ChildPropertiesListing[deposit]", $('#prop_list_deposit_' + eleEditable[i]).val());
                form.append("ChildPropertiesListing[token_amount]", $('#prop_list_token_amount_' + eleEditable[i]).val());
                form.append("ChildPropertiesListing[maintenance]", $('#prop_list_maintenance_' + eleEditable[i]).val());
                form.append("ChildPropertiesListing[availability_from]", $('#prop_list_availability_from_' + eleEditable[i]).val());
                form.append("ChildProperties[status]", $('#prop_list_property_status_' + eleEditable[i]).val());
            }
            $.ajax({
                url: 'editlisting2?property_id=' + propertyId + '&type=' + type + '&main=' + main,
                type: "POST",
                data: form,
                processData: false,
                contentType: false,
                async: false,
                success: function (data) {

                },
                error: function (data) {
                    hideLoader();
                    alert('Some Error Occured.');

                }

            });
        }
        hideLoader();
        window.location.reload();
    });

    function showalert(target)
    {
        var id = target.id;
        var dropval = $('#' + id + ' option:selected').text();
        if (dropval == 'Listed')
        {
            var r = confirm("This property has been booked by an applicant. Do you still want to list this property?");
            if (r != true) {
                event.preventDefault();
            }
        }
    }
</script>