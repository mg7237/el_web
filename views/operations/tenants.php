<?php
$this->title = 'PMS- Tenant Details';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\grid\GridView;
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <!-- <div class="col-lg-2 selectleft"> -->
            <!-- <a class="btn btn-default btn-select"> -->
             <!-- <input type="hidden" class="btn-select-input" id="" name="" value="" /> -->
             <!-- <span class="btn-select-value">choose type</span> <span class='btn-select-arrow glyphicon glyphicon-chevron-down'></span> -->
            <!-- <ul> -->
            <!-- <li class="selected">choose type</li> -->
            <!-- <li>Tenant name</li> -->
            <!-- <li>Tenant email</li> -->
            <!-- <li>Tenant phone number</li> -->
            <!-- <li>Address</li> -->
            <!-- <li>Property type</li> -->
            <!-- <li>Sales person ID</li> -->
            <!-- </ul> -->
            <!-- </a> -->
            <!-- </div> -->
            <!-- <div class="col-lg-2 selectleft"> -->
            <!-- <a class="btn btn-default btn-select"> -->
             <!-- <input type="hidden" class="btn-select-input" id="" name="" value="" /> -->
             <!-- <span class="btn-select-value">Start date</span> <span class='btn-select-arrow glyphicon glyphicon-chevron-down'></span> -->
            <!-- <ul> -->
            <!-- <li class="selected">Start date</li> -->
            <!-- <li>12/12/2016</li> -->
            <!-- <li>01/02/2017</li> -->
            <!-- <li>01/03/2017</li> -->
            <!-- </ul> -->
            <!-- </a> -->
            <!-- </div> -->
            <!-- <div class="col-lg-2 selectleft"> -->
            <!-- <a class="btn btn-default btn-select"> -->
             <!-- <input type="hidden" class="btn-select-input" id="" name="" value="" /> -->
             <!-- <span class="btn-select-value">End date</span> <span class='btn-select-arrow glyphicon glyphicon-chevron-down'></span> -->
            <!-- <ul> -->
            <!-- <li class="selected">End date</li> -->
            <!-- <li>01/02/2017</li> -->
            <!-- <li>01/03/2017</li> -->
            <!-- <li>12/12/2016</li> -->
            <!-- </ul> -->
            <!-- </a> -->
            <!-- </div> -->
            <div class="col-lg-5 selectright pull-right">
                <form class="navbar-form navbar-search" role="search">
                    <div class="input-group">
                        <input type="text" class="form-control" value="Name, email, phone number, address, status">
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-search btn-danger selecttext"> <span class="glyphicon glyphicon-search"></span> <span class="label-icon"></span> </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-12 adminusermanag">
            <table class="table table-bordered" cellpadding="5">
                <thead>
                    <tr>
                        <th>Tenant id</th>
                        <th>Tenant name</th>
                        <th>Property name</th>
                        <th>Unit Rented</th>
                        <th>Property address</th>
                        <th>Type</th>
                        <th>Payment details</th>
                        <th>Contract Start Date</th>
                        <th>Contract End date</th>
                        <th>Operations person</th>
                        <th>Sales Person</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($data as $key => $value) {
                        if ($value['property_type_id'] == 1 || $value['property_type_id'] == 2) {
                            $rented = count(explode(",", $value['rented_prop']));
                            if ($rented == 1) {
                                $rented = $rented . ' Bed';
                            } else {
                                $rented = $rented . ' Beds';
                            }
                        } else {
                            $rented = '1 Flat';
                        }
                        ?>
                        <tr class="clickable-row" data-href="<?= Url::home(true) . 'operations/tenantdetails?id=' . base64_encode($value['tenant_id']) ?>">
                            <td><?= $value['tenant_id'] ?></td>
                            <td><?= $value['full_name'] ?></td>
                            <td><?= $value['property_name'] ?></td>
                            <td><?= $rented ?></td>
                            <td><?= $value['address_line_1'] . ' ' . $value['address_line_2'] ?></td>
                            <td><?= $value['property_type'] ?></td>
                            <td><span style="float: left;">Rent=<?= $value['rent'] ?></span><br><span style="float: left;">Maintenance=<?= $value['maintainance'] ?></span><br><span style="float: left;">Deposit= <?= $value['deposit'] ?></span></td>
                            <td><?= $value['lease_start_date'] ?></td>
                            <td><?= $value['lease_end_date'] ?></td>
                            <td><?= $value['sales_name'] ?></td>
                            <td><?= $value['operations_name'] ?></td>
                        </tr>
                        <?php
                    }
                    ?>

                </tbody>
            </table>
            <div class="col-lg-4 exportexecel">
                <button type="button" class="btn savechanges_submit">Export to Excel </button>
            </div>
        </div>
    </div>
</div>
