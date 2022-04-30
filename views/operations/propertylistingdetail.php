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
<div id="page-wrapper">
    <div class="row">
        <div class="col-md-5">
            <div class="parmentaddress">
                <h1>Property Listing - <?= $Name ?></h1>
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
                            <td><?= $listing['rent']; ?></td>
                            <td><?= $listing['deposit']; ?></td>
                            <td><?= $listing['token_amount']; ?></td>
                            <td><?= ($listing['maintenance_included'] == 0) ? 'Not Included' : $listing['maintenance']; ?></td>
                            <td><?= $listing['availability_from']; ?></td>
                            <td><?= (!Yii::$app->userdata->getPropertyBook($listing['property_id'], $Type)) ? 'Vacant' : 'Booked'; ?></td>
                            <td><a href="javascript:"><i class="fa fa-pencil-square-o fa-2x" aria-hidden="true"  data-toggle="modal" data-target="#myModal2"></i></a></td>
                        </tr>
                        <?php
                    } else {
                        $array = Array();
                        $beds = Array();
                        $booked_data = Yii::$app->userdata->getPropertyByPidBeds(base64_decode($_GET['id']));
                        foreach ($listing as $key => $value) {
                            if (!in_array($value['parent_id'], $array)) {
                                $array[] = $value['parent_id'];
                            }
                            $beds[$value['parent_id']][] = $value['child_id'];
                            ?>
                            <tr>
                                <td>Room <?= array_search($value['parent_id'], $array) + 1 ?><br/>Bed <?= array_search($value['child_id'], $beds[$value['parent_id']]) + 1 ?></td>
                                <td><?= $value['rent']; ?></td>
                                <td><?= $value['deposit']; ?></td>
                                <td><?= $value['token_amount']; ?></td>
                                <td><?= (Yii::$app->userdata->getMaintenanceIncluded($value['main']) == 0) ? 'Not Included' : $value['maintenance']; ?></td>
                                <td><?= $value['availability_from']; ?></td>
                                <td><?= (in_array($value['child_id'], $booked_data)) ? 'Booked' : 'Vacant'; ?></td>
                                <td><?= (!in_array($value['child_id'], $booked_data)) ? '<a href="' . Url::home(true) . 'operations/editlisting?property_id=' . $value['child_id'] . '&type=' . $Type . '&main=' . $_GET['id'] . '" onclick="$(\'body\').on(\'hidden.bs.modal\', \'.modal\', function () { $(this).removeData(\'bs.modal\'); });"  data-toggle="modal" data-target="#myModal2"><i class="fa fa-pencil-square-o fa-2x" aria-hidden="true" ></i></a>' : '<span style="color:red;">Property Details Can\'t Be Edited</span>' ?></td>
                            </tr>
                            <?php
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