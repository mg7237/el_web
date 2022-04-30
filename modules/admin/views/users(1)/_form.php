<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\UserTypes;
use app\models\Roles;
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
    <?= $form->errorSummary($model); ?>
    <?php 
	$countries=UserTypes::find()->where(['IN','id',['2','4','5']])->all();
	$listData=ArrayHelper::map($countries,'id','user_type_name');
	?>

	<?= $form->field($model, 'user_type')->dropDownList($listData); ?>
	
    <?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'login_id')->textInput(['maxlength' => true]) ?>
    
	<?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

   
  <div id="applicant_phone" style="display: none;">
    <?= $form->field($modelApplicant, 'phone')->textInput(['maxlength' => true]) ?>
    </div>

    <div id="owner_phone" style="display: none;">
    <?= $form->field($modelOwner, 'phone')->textInput(['maxlength' => true]) ?>
    </div>

     <div id="advisor_phone" style="display: none;">
    <?= $form->field($modelAdvisor, 'phone')->textInput(['maxlength' => true]) ?>
    </div>

    <div id="address" style="display:none">
    <?= $form->field($modelAdvisor, 'address_line_1')->textInput(['maxlength' => true,'id' => 'searchTextField']) ?>
  </div>
  
  
	<?=  $form->field($model, 'lat')->hiddenInput(['value'=> ''])->label(false); ?>
	
	<?=  $form->field($model, 'lon')->hiddenInput(['value'=> ''])->label(false); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
<script>
$(document).ready(function(){

    $(document).on('change','#users-user_type',function(){
        alert($(this).val());
        if( $(this).val() == 2 ){
            $('#applicant_phone').css('display','block');
            $('#owner_phone').css('display','none');
            $('#advisor_phone').css('display','none');
        } else if( $(this).val() == 4 ) {
            $('#owner_phone').css('display','block');
            $('#advisor_phone').css('display','none');
            $('#applicant_phone').css('display','none');
        } else if( $(this).val() == 5 ) {
            $('#advisor_phone').css('display','block');
            $('#owner_phone').css('display','none');
            $('#applicant_phone').css('display','none');
        }        
   });
});
$(document).on('change','#users-user_type',function(){
        if( $(this).val() == 2 || $(this).val() == '' ){
            $('#address').css('display','none');
            $('#searchTextField').val('');

        } else if($(this).val() == 4) {
            $('#address2').css('display','block');
            $('#address1').css('display','none');
        }
        else if($(this).val() == 5){
            $('#address1').css('display','block');
            $('#address2').css('display','none');
        }    
   });
</script>