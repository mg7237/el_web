<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Roles */
$this->title = 'Assignments';
?> 
       <div class="row">
			<div class="col-lg-12">
												<h3><?= Html::encode($this->title) ?></h3>

				<div class="panel-body">
																
			  <?php $form = ActiveForm::begin(); ?>
			  
				<?= $form->field($model, 'type')->dropDownList([1=> 'State',2=>'City',3=>'Branch'], ['prompt'=>'Select Type','url'=>\Yii::$app->getUrlManager()->createUrl('admin/regionassignments/choice')]); ?>
					
				<?= $form->field($model, 'user_type')->dropDownList([1=> 'Sales',2=>'Operations'], ['prompt'=>'Select Type']); ?>
				
				<?php 
					$listData = [];
					if($model->type  == 1 ){	
						
						$modelStates = \app\models\States::find()->all();
						$listData=\yii\helpers\ArrayHelper::map($modelStates,'id','name');
						
					} elseif( $model->type == 2 ){
						
						$modelCities = \app\models\Cities::find()->all();
						$listData=\yii\helpers\ArrayHelper::map($modelCities,'id','city_name');
						
					} elseif( $model->type == 3 ){
						
						$modelRegions = \app\models\Regions::find()->all();
						$listData=\yii\helpers\ArrayHelper::map($modelRegions,'id','region_name');
					}
				
				?>
				
				<?= $form->field($model, 'assign_region_id')->dropDownList($listData, ['prompt'=>'Select Branch','url'=>\Yii::$app->getUrlManager()->createUrl('admin/regionassignments/getusers')]); ?>
				
				
				<?php 
					$modelUsers = \app\models\Users::find()->where(['IN','user_type',[6,7]])->all();
					$listDataUsers=\yii\helpers\ArrayHelper::map($modelUsers,'id','full_name');
				?>	
				<?= $form->field($model, 'user_id')->dropDownList($listDataUsers, ['prompt'=>'Select User','url'=>\Yii::$app->getUrlManager()->createUrl('admin/regionassignments/getusers')]); ?>

				
				<div class="form-group">
					<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
				</div>

				<?php ActiveForm::end(); ?>
			</div>
			</div>
			
	</div>
