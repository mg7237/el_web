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
            <br>
            <div class="col-lg-8 col-sm-8selectleft">
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


                        <th>Client Type</th>
                        <th>Name</th>
                        <th>Contact details</th> 
                        <th>Property Name</th>           
                        <th>Agreement Start Date</th>
                        <th>Agreement End</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    if (count($data) != 0) {
                        foreach ($data as $key => $value) {
                            ?>
                            <tr>
                                <td><?= $value['user_type_name']; ?></td>
                                <td><?= $value['full_name']; ?></td>
                                <td><?= $value['login_id'] . '<br/>' . $value['phone']; ?></td>
                                <td><?= $value['property_name'] ?></td>
                                <td><?= $value['start_date']; ?></td>
                                <td><?= $value['end_date']; ?></td>
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
