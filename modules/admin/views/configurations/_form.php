<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AdvisorsDefaultPaymentConfig */
/* @var $form yii\widgets\ActiveForm */

$this->title = $model->isNewRecord ? 'Create' : 'Update'.' PMS Configrations';
$this->params['breadcrumbs'][] = ['label' => 'Pages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="pms-property-configurations-form">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'lease_percent')->textInput() ?>

    <?= $form->field($model, 'sub_lease_percent')->textInput() ?>

    <?= $form->field($model, 'penalty_percent')->textInput() ?>

    <?= $form->field($model, 'advisor_owner_rent')->textInput() ?>

    <?= $form->field($model, 'advisor_tenant_rent')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
