<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\UserTypes;
use app\models\Roles;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $model app\models\Users */

$this->title = 'Update Admin User';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="users-create">

    <h1><?= Html::encode($this->title) ?></h1>

<div class="users-form">

    <?php $form = ActiveForm::begin(); 
    echo  $form->errorSummary($model);
    ?>
     <?php 
    $countries=UserTypes::find()->where(['IN','id',['6','7']])->all();
    $listData=ArrayHelper::map($countries,'id','user_type_name');
    ?>
    <?= $form->field($model, 'user_type')->dropDownList($listData, ['prompt'=>'Select user type','url'=>\Yii::$app->getUrlManager()->createUrl('admin/users/getroles')],[]); ?>
    
    <?php 
    $roles=Roles::find()->where(['user_type'=>$model->user_type])->all();
    $listData=ArrayHelper::map($roles,'id','role_name');
    ?>
    <?= $form->field($model, 'role')->dropDownList($listData, ['prompt'=>'Select role']); ?>
    
    <?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'login_id')->textInput(['maxlength' => true]) ?>
     <?= $form->field($modelSales, 'phone')->textInput(['maxlength' => true]) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


</div>