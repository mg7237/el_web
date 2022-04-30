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
			  <?php 
					$countries=\app\models\Users::find()->where(['IN','user_type',['6','7']])->all();
					$listData=ArrayHelper::map($countries,'id','full_name');
				?>
				<?= $form->field($model, 'user_id')->dropDownList($listData, ['prompt'=>'Select User','url'=>\Yii::$app->getUrlManager()->createUrl('admin/userassignments/getmanager')]); ?>
				
				<?php 
					$listData = [];
					if(!$model->isNewRecord){
						$user = \app\models\Users::findOne($model->user_id);
						if(in_array($user->role,[4,8]  )) {
							$modelData = \app\models\Users::find()->where(['user_type'=>1])->all();
							$listData=\yii\helpers\ArrayHelper::map($modelData,'id','username');
						}
					}
				?>
				<?= $form->field($model, 'manager_id')->dropDownList($listData, ['prompt'=>'Select User']); ?>

				<div class="form-group">
					<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
				</div>

				<?php ActiveForm::end(); ?>
			</div>
			
	</div>
	</div>
