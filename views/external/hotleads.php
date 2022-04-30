<?php
$this->title = 'Hot Leads ';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* echo "<pre>";
  print_r($model);
  die; */
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12 col-sm-12">

            <div class="col-lg-8 col-sm-8 selectleft">
                <form class="navbar-form navbar-search" action="" method="GET" role="search">
                    <div class="input-group">
                        <?php
                        $search = '';
                        if (isset($_REQUEST['search'])) {
                            $search = $_REQUEST['search'];
                        }
                        ?>
                        <input type="text" name="search" class="form-control" value="<?= $search; ?>" placeholder="Search By Name, Email, Phone Number, Address">
                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-search btn-danger selecttext"> <span class="glyphicon glyphicon-search"></span> <span class="label-icon"></span> </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-12 col-sm-12 rightleads">
            <table cellpadding="5" class="table table-bordered tableheadingrow" id="ExportTable">
                <thead>
                    <tr>

                        <th width="12%">Reference ID</th>
                        <th width="12%">Type</th>
                        <th width="10%">Name</th>
                        <th width="19%">Contact details</th>
                        <th width="20%">Location/Address</th>
                        <th width="5%">Status</th>
                        <!-- <th width="17%">Type of Visit<span aria-hidden="true" class="glyphicon glyphicon-triangle-bottom"></span></th> -->
                        <th width="20%">Follow Up Date &amp; time</th>
                        <!-- <th width="22%">Sales person ID<span aria-hidden="true" class="glyphicon glyphicon-triangle-bottom"></span></th> -->
                    </tr>
                </thead>
                <tbody>

                    <?php
                    if (count($data) != 0) {
                        foreach ($data as $key => $value) {
                            ?>
                            <tr>
                                <td><?= $key + 1; ?></td>
                                <td><?= $value['type']; ?></td>
                                <td><?= $value['name']; ?></td>
                                <td><?= $value['email'] . '<br/>' . $value['contact']; ?></td>
                                <td><?= $value['address'] . '<br/>' . $value['city']; ?></td>
                                <td><?= $value['ref_status']; ?></td>
                                <td><?= Date('d-M-Y H:i', strtotime($value['follow_up_date_time'])); ?></td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="7">No Data Found</td>
                        </tr>
                        <?php
                    }
                    ?>

                </tbody>
            </table>
            <div class="col-lg-12 col-sm-12">
                <div class="col-lg-4 col-sm-4">
                    <button class="btn savechanges_submit_contractaction_Tenant" id="btnExport" type="button">Export </button>
                </div>
            </div>


        </div>
        <!-- /.col-lg-12 -->
    </div>
</div>
