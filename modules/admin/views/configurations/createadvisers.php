<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AdvisorsDefaultPaymentConfig */
/* @var $form yii\widgets\ActiveForm */

$this->title = $model->isNewRecord ? 'Create' : 'Update'.' Advisers Configrations';
$this->params['breadcrumbs'][] = ['label' => 'Pages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pages-create">
	
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>
    
	<?= $form->field($model, 'config_type')->dropDownList([1=>'Properties',2=>'Tenants'], ['prompt'=>'Select Type']); ?>

    <?= $form->field($model, 'min')->textInput() ?>
    <?= $form->field($model, 'max')->textInput() ?>
    <?= $form->field($model, 'advisor_fees')->textInput() ?>




    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
