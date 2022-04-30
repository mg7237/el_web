<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\PropertyTypes;
use app\models\PropertyAttributeMap;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\models\Properties */
/* @var $form yii\widgets\ActiveForm */
?>
<script src="//maps.googleapis.com/maps/api/js?key=AIzaSyDCFkcmtCqHONWxjbZn3bftmAqQ0A2YSKw&amp;libraries=places" type="text/javascript"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script type="text/javascript">
    function initialize() {
        var input = document.getElementById('searchTextField');
        var autocomplete = new google.maps.places.Autocomplete(input);
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var place = autocomplete.getPlace();
            document.getElementById('propertiesaddress-lat').value = place.geometry.location.lat();
            document.getElementById('propertiesaddress-lon').value = place.geometry.location.lng();
        });
    }
    google.maps.event.addDomListener(window, 'load', initialize);

</script>
<div class="properties-form">
    <div class="col-lg-6">
        <?php $form = ActiveForm::begin(['enableClientValidation' => false], ['options' => ['enctype' => 'multipart/form-data']]); ?>
        <?php //$form->errorSummary($model); ?>
        <?php //echo $form->errorSummary($modelAddress); ?>
        <?php //echo $form->errorSummary($modelAgreements); ?>
        <?= $form->field($modelAddress, 'property_name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($modelAddress, 'address')->textInput(['maxlength' => true, 'id' => 'searchTextField']) ?>

        <?= $form->field($modelAddress, 'lat')->hiddenInput(['value' => ''])->label(false); ?>

        <?= $form->field($modelAddress, 'lon')->hiddenInput(['value' => ''])->label(false); ?>

        <?= $form->field($modelAddress, 'city')->textInput(['maxlength' => true]) ?>
        <?= $form->field($modelAddress, 'state')->textInput(['maxlength' => true]) ?>
        <?= $form->field($modelAddress, 'pincode')->textInput(['maxlength' => true]) ?>

        <?= $form->field($modelImages, 'image_url[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>

        <?= $form->field($model, 'rent')->textInput() ?>

        <?= $form->field($model, 'deposit')->textInput() ?>

        <?= $form->field($model, 'token_amount')->textInput() ?>

        <?= $form->field($model, 'maintenance_included')->dropDownList(['0' => 'No', '1' => 'Yes'], ['prompt' => 'Select Maintenance']) ?>

        <?= $form->field($model, 'maintenance')->textInput() ?>

    </div>
    <div class="col-lg-6">
        <?= $form->field($model, 'property_description')->textarea(['rows' => 6]) ?>


        <?php
        $countries = PropertyTypes::find()->all();
        $listData = ArrayHelper::map($countries, 'id', 'property_type_name');
        $date
        ?>
        <?= $form->field($model, 'property_type')->dropDownList($listData, ['prompt' => 'Select property type']); ?>

        <div id="colive" style="display:none;">

            <?= $form->field($model, 'is_room')->dropDownList(['0' => 'No', '1' => 'Yes'], ['prompt' => 'is room']) ?>

        </div>
        <div id="no_of_beds" style="display:none;">

            <?= $form->field($model, 'no_of_beds')->textInput() ?>
        </div>
        <div id="no_of_rooms" style="display:none;">

            <?= $form->field($model, 'no_of_rooms')->textInput() ?>
        </div>

        <div id="flat" style="display:none;">
            <?= $form->field($model, 'flat_bhk')->textInput() ?>

            <?= $form->field($model, 'flat_area')->textInput() ?>

            <?= $form->field($model, 'flat_type')->dropDownList(['0' => 'semi-furnished', '1' => 'furnished'], ['prompt' => 'Select flat type']) ?>

        </div>   

        <?= $form->field($model, 'status')->dropDownList(['0' => 'Inactive', '1' => 'Active'], ['prompt' => 'Select status']) ?>




        <?= $form->field($model, 'availability_from')->textInput(['id' => 'visit_date']) ?>
        <?= $form->field($modelAgreements, 'rent_date')->textInput([]) ?>
        <?= $form->field($modelAgreements, 'min_stay')->textInput([]) ?>
        <?= $form->field($modelAgreements, 'notice_period')->textInput([]) ?>
        <?= $form->field($modelAgreements, 'late_penalty_percent')->textInput([]) ?>
        <?= $form->field($modelAgreements, 'min_penalty')->textInput([]) ?>



    </div>
    <div class="clearfix"></div>
    Property Attributes
    <?php
    $dataProper = [];
    $id = 0;
    if (!$model->isNewRecord) {
        $dataPropers = PropertyAttributeMap::find()->where(['property_id' => $model->id])->all();
        $id = $model->id;
        foreach ($dataPropers as $dataPrope) {
            $dataProper[] = $dataPrope->attr_id;
        }
    }

    foreach ($modelPropertiesAttributes as $modelPropertiesAttribute) {
        ?>
        <div class="form-group field-propertyattributemap-value-3 required">
            <label class="control-label" for="propertyattributemap-value-3"><?= $modelPropertiesAttribute->name; ?></label>
            <label><input type="checkbox" id="propertyattributemap-value-<?= $modelPropertiesAttribute->id; ?>" name="PropertyAttributeMap[value][<?= $modelPropertiesAttribute->id; ?>]" <?= in_array($modelPropertiesAttribute->id, $dataProper) ? 'checked' : '' ?>  > 
            </label>
        </div>
        <?php
    }
    ?>
    <?php foreach ($modelPropertiesAttributesInputs as $modelPropertiesAttributesInput) { ?>
        <?= $form->field($propertyAttributeMap, 'value2[' . $modelPropertiesAttributesInput->id . ']')->textInput(['value' => Yii::$app->userdata->getAttrVal($id, $modelPropertiesAttributesInput->id)])->label($modelPropertiesAttributesInput->name); ?>

    <?php } ?>

    <div class="clearfix"></div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php
    $this->registerJs(
            "
    $( document ).ready(function() {
 
    if($('#properties-property_type').val() =='' ){
    
           $('#flat').css('display','none'); 
		   $('#colive').css('display','none'); 
		   $('#no_of_beds').css('display','none'); 
		   $('#no_of_rooms').css('display','none'); 
    } else if($('#properties-property_type').val() == 3 ){
    
           $('#flat').css('display','block'); 
		   $('#colive').css('display','none'); 
		   $('#no_of_beds').css('display','none'); 
		   $('#no_of_rooms').css('display','none'); 
		   
		   $('#properties-is_room').val('');
		   $('#properties-no_of_rooms').val('');
		   $('#properties-no_of_beds').val('');
		   
    } else {
    
	   if($('#properties-is_room').val() ==''){
	   
			   $('#flat').css('display','none'); 
			   $('#colive').css('display','block'); 
			   $('#no_of_beds').css('display','none'); 
			   $('#no_of_rooms').css('display','none'); 
		} else if( $('#properties-is_room').val() ==1 ){
		
			   $('#flat').css('display','none'); 
			   $('#colive').css('display','block'); 
			   $('#no_of_beds').css('display','none'); 
			   $('#no_of_rooms').css('display','block'); 
			   
			   $('#properties-flat_bhk').val('');
			   $('#properties-flat_bhk').val('');
			   $('#properties-flat_area').val('');
			   $('#properties-flat_type').val('');
			   $('#properties-no_of_beds').val('');

			   
		  } else  {
		  
			   $('#flat').css('display','none'); 
			   $('#colive').css('display','block'); 
			   $('#no_of_beds').css('display','block'); 
			   $('#no_of_rooms').css('display','none'); 
			   
			   
			   $('#properties-flat_bhk').val('');
			   $('#properties-flat_bhk').val('');
			   $('#properties-flat_area').val('');
			   $('#properties-flat_type').val('');
			   $('#properties-no_of_rooms').val('');
			
		  }
    }
    
 
     
    $('#properties-property_type').on('change', function() {
      if ( this.value == '3')
      {
		   $('#flat').css('display','block'); 
		   $('#colive').css('display','none'); 
		   $('#no_of_beds').css('display','none'); 
		   $('#no_of_rooms').css('display','none'); 
		   
		   $('#properties-is_room').val('');
		   $('#properties-no_of_rooms').val('');
		   $('#properties-no_of_beds').val('');
		   
      } else  {
           $('#flat').css('display','none'); 
		   $('#colive').css('display','block'); 
		   $('#no_of_beds').css('display','none'); 
		   $('#no_of_rooms').css('display','none'); 
		   
		       $('#properties-flat_bhk').val('');
			   $('#properties-flat_bhk').val('');
			   $('#properties-flat_area').val('');
			   $('#properties-flat_type').val('');
			   $('#properties-no_of_beds').val('');
			   $('#properties-is_room').val('');
			   $('#properties-no_of_rooms').val('');
      
      }
    });
    
    $('#properties-is_room').on('change', function() {
      if ( this.value == '1')
      {
		   $('#flat').css('display','none'); 
		   $('#colive').css('display','block'); 
		   $('#no_of_beds').css('display','none'); 
		   $('#no_of_rooms').css('display','block'); 
		   
		       $('#properties-flat_bhk').val('');
			   $('#properties-flat_bhk').val('');
			   $('#properties-flat_area').val('');
			   $('#properties-flat_type').val('');
			   $('#properties-no_of_beds').val('');
		   
      } else  {
          
           $('#flat').css('display','none'); 
		   $('#colive').css('display','block'); 
		   $('#no_of_beds').css('display','block'); 
		   $('#no_of_rooms').css('display','none'); 
		   
		       $('#properties-flat_bhk').val('');
			   $('#properties-flat_bhk').val('');
			   $('#properties-flat_area').val('');
			   $('#properties-flat_type').val('');
			   $('#properties-no_of_rooms').val('');
      
      }
    });
		 
								$('#visit_date' ).datepicker({
									   minDate: 0,
									   changeMonth: true,
									   changeYear: true,
									   yearRange: '-0:+1',
									  numberOfMonths: 1,
									  dateFormat: 'dd-M-yy',
								});
								
     });
    ", View::POS_END, 'my-button-handler'
    );
    $this->registerJsFile('@web/js/app/jquery.ui.js', ['depends' => [\yii\web\JqueryAsset::className()], 'position' => View::POS_END,
    ]);
    ?>

    <?php
//print_r($dataImages);
    if (isset($dataImages)) {
        foreach ($dataImages as $image) {
            ?>
            <?php echo Html::img('@web/uploads/' . $image->image_url, ['width' => '200']) ?>
            <?php
        }
    }
    ?>
</div>
