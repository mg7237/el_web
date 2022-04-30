<?php
$this->title = 'PMS- My Lease';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<!-- Modal signup -->
<!-- Modal signup -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">

            <h2>My Lease</h2>


            <table id="favlist" class="table table-bordered mt" cellpadding="1"> 
                <thead>
                    <tr> 
                        <th>Property Name</th>
                        <th>Due Date</th>
                        <th>Rent</th>
                        <th>Manteinace</th>
                        <th>Deposit</th>
                        <th>Penalty %</th>
                        <th>Min Penalty</th>
                        <th>Minimum Stay</th>

                    </tr>
                </thead> 
                <tbody>

                    <?php
                    if ($agreements) {
                        foreach ($agreements as $request) {
                            ?>

                            <tr> 
                                <td><?= Yii::$app->userdata->getPropertyNameById($request->property_id); ?></td>
                                <td><?= $request->rent_date; ?></td>
                                <td><?= $request->rent; ?></td> 
                                <td><?= $request->manteinace; ?></td>
                                <td><?= $request->deposit; ?></td>
                                <td> <?= $request->late_penalty_percent; ?>% </td>
                                <td> <?= $request->min_penalty; ?> Rs </td>
                                <td> <?= $request->min_stay; ?> Months </td>

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
                    hideLoader();
                    if (data == 0) {
                        alert('Please login to add to wishlist');
                    } else {

                        $('#favlist').load(' #favlist');
                    }
                },
                error: function (data) {
                    //    console.log(data.responseText);
                    hideLoader();
                    // alert(data.responseText);
                    alert('Failure');
                }
            });
        });
    });
</script> 
