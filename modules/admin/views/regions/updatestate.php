<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;




/* @var $this yii\web\View */
/* @var $model app\models\Roles */

$this->title = 'Update State';
?>
         <div class="row">
                    <div class="col-lg-12">
													

				<h3><?= Html::encode($this->title) ?></h3>

										
			   <?php $form = ActiveForm::begin(); ?>
			   
				<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

				<div class="form-group">
					<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
				</div>

				<?php ActiveForm::end(); ?>					


                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->



