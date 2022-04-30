<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Roles */

$this->title = 'Update Branch';
?>
<div class="row">
    <div class="col-lg-12">


        <h3><?= Html::encode($this->title) ?></h3>


<?php $form = ActiveForm::begin(); ?>
<?php
$countries = \app\models\States::find()->all();
$listData = ArrayHelper::map($countries, 'id', 'name');
?>
        <?= $form->field($model, 'state_id')->dropDownList($listData, ['prompt' => 'Select State', 'disabled' => 'disabled', 'url' => \Yii::$app->getUrlManager()->createUrl('admin/regions/getcities')]); ?>

        <?php
        $states = \app\models\Cities::find()->where(['state_id' => $model->state_id])->all();
        $cities = ArrayHelper::map($states, 'id', 'city_name');
        ?>

        <?= $form->field($model, 'city_id')->dropDownList($cities, ['prompt' => 'Select City', 'disabled' => 'disabled']); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'branch_code')->textInput(['maxlength' => true, 'disabled' => 'disabled']) ?>
        
        <div class="form-group field-regions-branch_type required">
            <label class="control-label" for="regions-branch_type">Branch Type</label>
            <select id="regions-branch_type" class="form-control" name="Regions[branch_type]" aria-required="true">
                <option <?= ($model->branch_type == 1) ? 'selected' : '' ?> value="1">Internal Branch</option>
                <option <?= ($model->branch_type == 2) ? 'selected' : '' ?> value="2">External Branch</option>
            </select>

            <div class="help-block"></div>
        </div>

        <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

            <?php ActiveForm::end(); ?>					


    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->



