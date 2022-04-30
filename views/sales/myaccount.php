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
        <div class="col-lg-12">

            <div class="col-lg-8 selectleft">
                <form class="navbar-form navbar-search" action="" method="GET" role="search">
                    <div class="input-group">
                        <?php
                        $search = '';
                        if (isset($_REQUEST['search'])) {
                            $search = $_REQUEST['search'];
                        }
                        ?>
                        <input type="text" name="search" class="form-control" value="<?= $search; ?>" placeholder="Search by name, email, phone number, address, status">
                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-search btn-danger selecttext"> <span class="glyphicon glyphicon-search"></span> <span class="label-icon"></span> </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-12 rightleads">
            <table cellpadding="5" class="table table-bordered tableheadingrow">
                <thead>
                    <tr>


                        <th>Client Type<span aria-hidden="true" class="glyphicon glyphicon-triangle-bottom"></span></th>
                        <th>Name<span aria-hidden="true" class="glyphicon glyphicon-triangle-bottom"></span></th>
                        <th>Contact details<span aria-hidden="true" class="glyphicon glyphicon-triangle-bottom"></span></th> 
                        <th>Property Name<span aria-hidden="true" class="glyphicon glyphicon-triangle-bottom"></span></th>           
                        <th>Agreement Start Date<span aria-hidden="true" class="glyphicon glyphicon-triangle-bottom"></span></th>
                        <th>Agreement End<span aria-hidden="true" class="glyphicon glyphicon-triangle-bottom"></span></th>
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
            <div class="col-lg-12">
                <div class="col-lg-4">
                    <button class="btn savechanges_submit_contractaction_Tenant" type="button">Export </button>
                </div>
            </div>


        </div>
        <!-- /.col-lg-12 -->
    </div>
</div>
