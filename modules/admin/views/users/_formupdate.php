<?php
// echo '<pre>'; print_r($model);
// echo '<pre>'; print_r($advisorPhoneModel); die;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\UserTypes;
use app\models\Roles;
use app\models\Users;
use yii\helpers\ArrayHelper;



/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $form yii\widgets\ActiveForm */
?>
<script src="//maps.googleapis.com/maps/api/js?key=AIzaSyDCFkcmtCqHONWxjbZn3bftmAqQ0A2YSKw&amp;libraries=places" type="text/javascript"></script>
<script type="text/javascript">
    function initialize() {
        var input = document.getElementById('searchTextField');
        var autocomplete = new google.maps.places.Autocomplete(input);
		google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var place = autocomplete.getPlace();
            document.getElementById('users-lat').value = place.geometry.location.lat();
            document.getElementById('users-lon').value = place.geometry.location.lng();
        });
    }
    google.maps.event.addDomListener(window, 'load', initialize);
    

</script>
<div class="users-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php 
	$countries=UserTypes::find()->where(['IN','id',['2','3','4','5']])->all();
	//$listData=ArrayHelper::map($countries,'id','user_type_name');
	?>
	<!-- <?//= $form->field($model, 'user_type')->dropDownList($listData, ['prompt'=>'Select user type']); ?> -->
	
    <?= $form->field($model, 'full_name')->textInput(['maxlength' => true,'class'=>'form-control isCharacter']) ?>

    <?= $form->field($model, 'login_id')->textInput(['maxlength' => true,'readonly' => true]) ?>

    <?= $form->field($advisorPhoneModel, 'phone')->textInput(['maxlength' => true,'class'=>'form-control isNumber']) ?>

	<?=  $form->field($model, 'lat')->hiddenInput(['value'=> ''])->label(false); ?>
	
	<?=  $form->field($model, 'lon')->hiddenInput(['value'=> ''])->label(false); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>