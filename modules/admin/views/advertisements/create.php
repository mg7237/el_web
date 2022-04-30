<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;




/* @var $this yii\web\View */
/* @var $model app\models\Roles */

$this->title = 'Create Advertisement';
?>
         <div class="row">
                    <div class="col-lg-12">
													

				<h3><?= Html::encode($this->title) ?></h3>

										
			   <?php $form = ActiveForm::begin([
                        'options' => ['enctype' => 'multipart/form-data']
            ]); ?>
			   <?= $form->errorSummary($model); ?>
			  
				
				
				<?= $form->field($model, 'link')->textInput(['maxlength' => true,'placeholder' => 'Banner URL','required'=>true]) ?>
				<?= $form->field($model, 'banner')->fileInput(['required'=>true]) ?>
				
				
<?php echo $form->field($model, 'type[]')->checkboxList(
			['o'=>'Property Owner','t'=>'Tenant','a'=>'Advisor']
   );
?>
				<div class="form-group">
					<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
				</div>

				<?php ActiveForm::end(); ?>					


                </div>
                <!-- /.row -->
            </div>
