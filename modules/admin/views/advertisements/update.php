<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
$this->title = 'Update Adverstisement: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Adverstisement', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
//echo "<pre>";print_r($advertisementtype);echo "</pre>";die;
?>
<div class="users-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
                    <div class="col-lg-12">
													

				<h3><?= Html::encode($this->title) ?></h3>

										
			   <?php $form = ActiveForm::begin([
                        'options' => ['enctype' => 'multipart/form-data']
            ]); ?>
			   <?= $form->errorSummary($model); ?>
			  
				
				
				<?= $form->field($model, 'link')->textInput(['maxlength' => true,'placeholder' => 'Banner URL','required'=>true]) ?>
				<div style="width: 20%;height:20%;">
        <img style="width: inherit;" src="<?php echo Url::home(true).$model->banner; ?>" />
    </div>
				<?= $form->field($model, 'banner')->fileInput() ?>
				
				
<?php /*echo $form->field($model, 'type[]')->checkboxList(
			['o'=>'Property Owner','t'=>'Tenant','a'=>'Advisor']
   );*/
?>
<div id="advertisements-type"><label><input type="checkbox" name="Advertisements[type][]" value="o" <?php if($advertisementtype->owner == 1){echo "checked";}?>> Property Owner</label>
<label><input type="checkbox" name="Advertisements[type][]" value="t" <?php if($advertisementtype->tenant == 1){echo "checked";}?>> Tenant</label>
<label><input type="checkbox" name="Advertisements[type][]" value="a" <?php if($advertisementtype->advisor == 1){echo "checked";}?>> Advisor</label></div>
				<div class="form-group">
					<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
				</div>

				<?php ActiveForm::end(); ?>					


                </div>
                <!-- /.row -->
            </div>
</div>