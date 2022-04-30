<?php
$this->title = 'PMS- My Visits';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<!-- Modal signup -->
<!-- Modal signup -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-ms-12 col-xs-12">

            <h2>My Visits</h2>


            <table id="favlist" class="table table-bordered mt" cellpadding="5"> 
                <thead>
                    <tr> 
                        <th> ID</th>
                        <th>Property Name</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Visit Status</th>
                        <th>Action</th>
                    </tr>
                </thead> 
                <tbody>

                    <?php
                    if ($propertyVisits) {

                        $i = 1;
                        foreach ($propertyVisits as $key => $wish) {
                            ?>
                            <tr> 
                                <td scope="row"><?= $i ?></td>
                                <td><?= Yii::$app->userdata->getPropertyNameById($wish->property_id); ?></td> 
                                <td><?= $wish->visit_date; ?></td>
                                <td><?= $wish->visit_time; ?></td>
                                <td><?php
                                    if ($wish->visit_confirm == 0) {
                                        echo "Pending Response";
                                    } elseif ($wish->visit_confirm == 1) {
                                        echo "Confirmed";
                                    } else {
                                        echo "Canceled";
                                    }
                                    ?></td>
                                <td> <?php if ($wish->visit_confirm == 0) { ?>
                                        <a data-propertyid="<?= $wish->property_id; ?>" data-email="<?= $wish->email_id; ?>"  data-id="<?= $wish->id; ?>" class="confirm btn btn-info">confirm</a> 
                                        <a data-propertyid="<?= $wish->property_id; ?>" data-email="<?= $wish->email_id; ?>" data-id="<?= $wish->id; ?>" class="cancel btn btn-danger">cancel</a> <?php }
                                    ?></td>
                            </tr>
                            <?php
                            $i++;
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
        $(document).on("click", ".confirm", function (e) {
            startLoader();
            e.preventDefault();
            $.ajax({
                url: '<?= \Yii::$app->getUrlManager()->createUrl('site/changeprop') ?>',
                type: 'POST',
                data: {propertyid: $(this).attr('data-propertyid'), email: $(this).attr('data-email'), id: $(this).attr('data-id'), status: 1, _csrf: csrfToken},
                success: function (data) {
                    hideLoader();
                    $('#favlist').load(' #favlist');
                    window.location.href = "<?= \Yii::$app->getUrlManager()->createUrl('site/dashboard') ?>";
                },
                error: function (data) {
                    //    console.log(data.responseText);
                    hideLoader();
                    alert('Failure');
                    // alert(data.responseText);
                }
            });
        });
        $(document).on("click", ".cancel", function (e) {

            e.preventDefault();
            startLoader();
            $.ajax({
                url: '<?= \Yii::$app->getUrlManager()->createUrl('site/changeprop') ?>',
                type: 'POST',
                data: {propertyid: $(this).attr('data-propertyid'), email: $(this).attr('data-email'), id: $(this).attr('data-id'), status: 2, _csrf: csrfToken},
                success: function (data) {
                    $('#favlist').load(' #favlist');
                    hideLoader();
                },
                error: function (data) {
                    //    console.log(data.responseText);
                    hideLoader();
                    alert('Failure');
                    // alert(data.responseText);
                }
            });
        });
    });
</script> 
