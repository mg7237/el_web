<?php
$this->title = 'PMS- Thank you';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<?php if (Yii::$app->session->hasFlash('success')) { ?>
    <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
        <?= Yii::$app->session->getFlash('success'); ?>
    </div>   
<?php } ?>
<!--  <div class="col-lg-12">
 <div class="parmentaddress">
                  <h1> Search more for new booking</h1>
                  <a href="myfavlist">Go To Booked Lists</a>

</div>
</div> -->


<script>
    $(document).ready(function () {
        window.location.href = 'myfavlist';
    })
</script>