<?php
$this->title = 'PMS- Payment information for advisor';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* echo "<pre>";
  //print_r($model);
  Yii::$app->userdata->getStatusByProperty(1);
  die; */
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<!-- Modal signup -->
<!-- Modal signup -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">

            <h2>Payment information Owner</h2>


            <table id="favlist" class="table table-bordered mt" cellpadding="5"> 
                <thead>
                    <tr> 
                        <th>Property Code</th>
                        <th>Property Owner</th>
                        <th>Referral type</th>
                        <th>Earned by PMS</th>
                        <th>Slab</th>
                        <th>Advisor revenue in percentage</th>
                    </tr>
                </thead> 
                <tbody>

                    <?php
                    if ($model) {

                        $i = 1;
                        foreach ($model as $key => $wish) {
                            ?>
                            <tr> 
                                <td><?= Yii::$app->userdata->getCodeByProperty($wish->property_id); ?></td>
                                <td><?= Yii::$app->userdata->getUserNameByProperty($wish->property_id); ?></td>
                                <td></td>
                                <td><?= Yii::$app->userdata->getTotalRentByPidPMS($wish->property_id, $wish->email_id); ?></td>
                                <td><?= Yii::$app->userdata->getSlab($countProperties); ?></td>
                                <td><?= Yii::$app->userdata->getTotalRentByPidPMSAdviser($wish->property_id, $wish->email_id, $countProperties); ?></td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?> 
                        <tr> <td colspan="4">no record found</td> </tr>
                        <?php
                    }
                    ?> 
                </tbody> 
            </table>

            <h2>Payment information Tenant</h2>


            <table id="favlist" class="table table-bordered mt" cellpadding="5"> 
                <thead>
                    <tr> 
                        <th>Property Code</th>
                        <th>Property Owner</th>
                        <th>Property Tenant</th>
                        <th>Referral type</th>
                        <th>Earned by PMS</th>
                        <th>Slab</th>
                        <th>Advisor revenue in percentage</th>
                    </tr>
                </thead> 
                <tbody>

                    <?php
                    if ($agreementsTenant) {

                        $i = 1;
                        foreach ($agreementsTenant as $key => $wish) {
                            ?>
                            <tr> 
                                <td><?= Yii::$app->userdata->getCodeByProperty($wish->property_id); ?></td>
                                <td><?= Yii::$app->userdata->getUserNameByProperty($wish->property_id); ?></td>
                                <td><?= Yii::$app->userdata->getUserNameByEmail($wish->email_id); ?></td>
                                <td></td>
                                <td><?= Yii::$app->userdata->getTotalRentByPidPMS($wish->property_id, $wish->email_id); ?></td>
                                <td><?= Yii::$app->userdata->getSlabTenant($countProperties); ?></td>
                                <td><?= Yii::$app->userdata->getTotalRentByPidPMSAdviserTenant($wish->property_id, $wish->email_id, $countTenant); ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                        <?php
                    } else {
                        ?> 
                        <tr> <td colspan="4">no record found</td> </tr>
                        <?php
                    }
                    ?> 
                </tbody> 
            </table>

        </div>

        <!-- /.col-lg-12 -->
    </div>
</div>
<script>
    $(document).ready(function () {
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        $(document).on("click", ".delete", function (e) {
            e.preventDefault();
            startLoader();
            $.ajax({
                url: '<?= \Yii::$app->getUrlManager()->createUrl('site/deletefav') ?>',
                type: 'POST',
                data: {id: $(this).attr('data-id'), _csrf: csrfToken},
                success: function (data) {
                    if (data == 0) {
                        alert('Please login to add to wishlist');
                    } else {

                        $('#favlist').load(' #favlist');
                    }
                    hideLoader();
                },
                error: function (data) {
                    hideLoader();
                    alert('Failure');
                }
            });
        });
    });
</script> 
