<?php
$this->title = 'PMS - Refer Owner';

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $form yii\widgets\ActiveForm */
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <div class="col-lg-8 col-xs-offset-2">

                <div class="tenentdetaillist borderbottom"></div>
                <?php if (Yii::$app->session->hasFlash('success')) { ?>
                    <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                        <?= Yii::$app->session->getFlash('success'); ?>
                    </div>   
                <?php } ?>
                dashboard
            </div></div></div></div>
