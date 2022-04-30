<?php
$this->title = 'PMS- Adviser Leads';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\grid\GridView;

// echo "<pre>";
// print_r($dataProviderOwner);
// die;
// if(isset($_GET['LeadsTenant']['sort'])){
//   $sort=$_GET['LeadsTenant']['sort'];
// }
// else{
//   $sort='';
// }
?>
<style>

</style>
<div class="col-lg-12 selectright">

    <div id="page-wrapper">

        <div class="row">
            <?php
            if (isset($_POST['range'])) {
                $range = $_POST['range'];
            } else {
                $range = 'monthly';
            }
            ?>

            <div class="row">
                <form method="POST" action="" id="form_filter">
                    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" />
                    <div class="col-lg-12 col-sm-12 button_lead">
                        <h4>Date Duration</h4>
                        <br/>
                        <div class="col-md-12">
                            <div class="col-lg-3 col-sm-12">
                                <div class="form-group field-users-full_name required">
                                    <label class="control-label" for="users-full_name">Start Date</label>
                                    <input type="text" id="start_date" class="form-control datepicker" name="start_date" maxlength="255" placeholder="Start Date" aria-required="true" value="<?= (isset($_POST['start_date'])) ? $_POST['start_date'] : '' ?>">
                                </div>
                            </div>    

                            <div class="col-lg-3 col-sm-12">
                                <div class="form-group field-users-full_name required">
                                    <label class="control-label" for="users-full_name">End Date</label>
                                    <input type="text" id="end_date" class="form-control datepicker" name="end_date" maxlength="255" placeholder="End Date" aria-required="true" value="<?= (isset($_POST['end_date'])) ? $_POST['end_date'] : '' ?>" >
                                </div>
                            </div>
                            <div class="col-md-2">
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <div class="col-lg-3 selectright selectlefttop pull-right">

                                    <a class="btn btn-default btn-select pull-right">
                                        <input type="hidden" name="range" class="btn-select-input" value="<?= $range ?>">
                                        <span class="btn-select-value"><?= $range ?></span> <span class="btn-select-arrow glyphicon glyphicon-chevron-down"></span>
                                        <ul class="range_list">
                                            <li <?= ($range == 'daily') ? 'class="selected"' : '' ?> >daily</li>
                                            <li <?= ($range == 'weakly') ? 'class="selected"' : '' ?> >weakly</li>
                                            <li <?= ($range == 'monthly') ? 'class="selected"' : '' ?> >monthly</li>
                                            <li <?= ($range == '6 month') ? 'class="selected"' : '' ?> >6 month</li>
                                        </ul>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>




                <div class="col-lg-12 advisortetxt">



                    <table class="table table-bordered tableheadingrow" cellpadding="5">
                        <thead>
                            <tr>
                                <th>Tenants Paid rent </th>
                                <th>Tenants Over Due </th>
                                <th>Total payment received </th>
                                <th>Total payment made to Owner</th>
                                <th>Total payment made to Advisor</th>
                                <th>Profit gained by PMS </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Rs300</td>
                                <td>12/12/2016</td>
                                <td>rs200</td>
                                <td>Applicant</td>
                                <td>Tenant</td>
                                <td>rs400</td>  
                            </tr>
                            <tr>
                                <td>Rs700</td>
                                <td>1/1/2017</td>
                                <td>rs400</td>
                                <td>Owner</td>
                                <td>Advisor</td>
                                <td>rs600</td>  
                            </tr>


                        </tbody>
                    </table>

                    <div class="col-lg-4"><button type="button" class="btn savechanges_submit">Export</button></div>
                </div>

                <div style="clear:both;"></div>

                <!-- /.col-lg-12 -->
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modalbg modalbgpopup">

        <!-- Modal content-->
        <div class="modal-content">


        </div>

    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#end_date').change(function () {
            $('#form_filter').submit();
        });
        $('#start_date').change(function () {
            $('#form_filter').submit();
        })
        $('.range_list li').click(function () {
            $('.btn-select-value').text($(this).text());
            $('.btn-select-input[name="range"]').val($(this).text())
            $('#form_filter').submit();
        })
    });
</script>



