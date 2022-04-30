<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Roles */

$this->title = 'Create City';
?>
<div class="row">
    <div class="col-lg-12">


        <h3><?= Html::encode($this->title) ?></h3>


<?php $form = ActiveForm::begin(); ?>
<?php
$countries = \app\models\States::find()->all();
$listData = ArrayHelper::map($countries, 'id', 'name');
?>
        <?= $form->field($model, 'state_id')->dropDownList($listData, ['prompt' => 'Select state']); ?>

        <?= $form->field($model, 'city_name')->textInput(['maxlength' => true]) ?>
        
        <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

            <?php ActiveForm::end(); ?>					


    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->



