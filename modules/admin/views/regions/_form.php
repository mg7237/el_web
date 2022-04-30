<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $model app\models\Roles */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="roles-form">

    <?php $form = ActiveForm::begin(); ?>
      <?php 
	$countries=\app\models\UserTypes::find()->where(['IN','id',['6','7']])->all();
	$listData=ArrayHelper::map($countries,'id','user_type_name');
	?>
	<?= $form->field($model, 'user_type')->dropDownList($listData, ['prompt'=>'Select user type','url'=>\Yii::$app->getUrlManager()->createUrl('admin/users/getroles')],[]); ?>
	
    <?= $form->field($model, 'role_name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
