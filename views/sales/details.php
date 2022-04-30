<?php
$this->title = 'Details ' . $model->full_name;

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* echo "<pre>";
  print_r($model);
  die; */
?>
<div class="col-lg-12">

    <h1><?= $this->title; ?></h1>
    <?php if (Yii::$app->session->hasFlash('success')) { ?>
        <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
            <?= Yii::$app->session->getFlash('success'); ?>
        </div>   
    <?php } ?>
    <a id="testapp" href="<?php echo Url::home(true); ?>sales/addfollowup?id=<?= $model->id; ?>" class="btn btn-info pull-right" data-toggle="modal" data-target="#myModal" style= "margin-top:15px;">Add FollowUp</a>
    <br />
    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'full_name',
            'email:email',
            'contact_number',
            'address',
            [
                'label' => 'Referred by advisor',
                'format' => 'raw',
                'attribute' => 'created_by',
                'value' => function($model) {
                    return Yii::$app->userdata->getFullNameById($model->created_by) . '<br />' . Yii::$app->userdata->getUserEmailById($model->created_by);
                }
            ]
        ],
    ])
    ?>
</div>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">


        </div>

    </div>
</div>

