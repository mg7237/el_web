<?php
$this->title = 'PMS-  Details';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\grid\GridView;
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="col-lg-5 col-sm-8 selectright pull-right topMargin">
                <form class="navbar-form navbar-search" role="search" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="Search By Name, Email, Phone Number, Address" value="<?= (isset($_GET['search'])) ? $_GET['search'] : '' ?>">
                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-search btn-danger selecttext"> <span class="glyphicon glyphicon-search"></span> <span class="label-icon"></span> </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-12 col-sm-12 adminusermanag">
            <table class="table table-bordered" cellpadding="5" id="ExportTable">
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
                        <tr class="clickable-row" data-href="<?= Url::home(true) . 'external/tenantdetails?id=' . base64_encode($value['tenant_id']) ?>">
                            <td><?= $value['tenant_id'] ?></td>
                            <td><?= $value['full_name'] ?></td>
                            <td><?= $value['property_name'] ?></td>
                            <td><?= $rented ?></td>
                            <td><?= $value['address_line_1'] . ' ' . $value['address_line_2'] ?></td>
                            <td><?= $value['property_type'] ?></td>
                            <td><span style="float: left;">Rent=<?= $value['rent'] ?></span><br><span style="float: left;">Maintenance=<?= $value['maintainance'] ?></span><br><span style="float: left;">Deposit= <?= $value['deposit'] ?></span></td>
                            <td><?= $value['lease_start_date'] ?></td>
                            <td><?= $value['lease_end_date'] ?></td>
                            <td><?= $value['operations_name'] ?></td>
                            <td><?= $value['sales_name'] ?></td>
                        </tr>
                        <?php
                    }
                    ?>

                </tbody>
            </table>

            <div class="col-lg-3 col-sm-6 exportexecel">
                <button type="button" class="btn savechanges_submit_contractaction" id="btnExport">Export to Excel </button>
            </div>
        </div>
    </div> 
</div>
