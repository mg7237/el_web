<?php
$this->title = (isset($edit)) ? 'Edit Property' : 'Add Property';

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\ArrayHelper;

if (isset($model->owner_id)) {
    $owner = $model->owner_id;
} else {
    $owner = base64_decode($_GET['id']);
}

$lead_id = Yii::$app->userdata->getLeadByEmailId(Yii::$app->userdata->getUserEmailById($owner));

// $lead_id=Yii::$app->userdata->getLeadByEmailId(Yii::$app->userdata->getUserEmailById(base64_decode($_GET['id'])));
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<style>
    #owner-lead-assign-btn {
        width: 100%;
        height: 32px;
    }
</style>
<div id="page-wrapper">

    <script>

        $(function () {
            $("#propertyagreements-contract_start_date").datepicker({
                changeMonth: true,
                changeYear: true,
                // yearRange: "-0:+1",
                //minDate: new Date(),
                numberOfMonths: 1,
                dateFormat: 'dd-M-yy',
                //maxDate: '0',
                onClose: function (selectedDate) {
                    $("#propertyagreements-contract_end_date").datepicker("option", "minDate", selectedDate);
                }

            });

            $("#propertyagreements-contract_end_date").datepicker({
                changeMonth: true,
                changeYear: true,
                //minDate: $('#propertyagreements-contract_start_date').val(),
                numberOfMonths: 1,
                dateFormat: 'dd-M-yy',
                //maxDate: '0',
                onClose: function (selectedDate) {
                    $("#propertyagreements-contract_start_date").datepicker("option", "maxDate", selectedDate);
                }
            });
        });


    </script>

    <div class="row">

        <?php if (Yii::$app->session->hasFlash('success')) { ?>
            <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                <?= Yii::$app->session->getFlash('success'); ?>
            </div>
        <?php } ?>

        <?php if (Yii::$app->session->hasFlash('error')) { ?>
            <div class="alert alert-danger fade in alert-dismissable" style="margin-top:18px;">
                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                <?= Yii::$app->session->getFlash('error'); ?>
            </div>
        <?php } ?>

        <?php if (Yii::$app->session->hasFlash('leadssuccess')) { ?>
            <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                <?= Yii::$app->session->getFlash('leadssuccess'); ?>
            </div>
        <?php } ?>

        <?php if (Yii::$app->session->hasFlash('error')) { ?>
            <div class="alert alert-danger fade in alert-dismissable" style="margin-top:18px;">
                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                <?= Yii::$app->session->getFlash('error'); ?>
            </div>
        <?php } ?>

        <div class="">
            <ul class="nav nav-pills">
                <?php if (!empty($_GET['t'])) { ?>
                    <li><a data-toggle="pill" href="#p_info" class="data_submit" id="p_info_submit">Property Information</a></li>
                <?php } else { ?>
                    <li class="active"><a data-toggle="pill" href="#p_info" class="data_submit" id="p_info_submit">Property Information</a></li>
                <?php } ?>
                <li><a data-toggle="pill" data-next="p_features" data-submit="p_info" href="<?= (isset($edit)) ? '#p_features' : 'javascript:' ?>" class="data_submit">Property & Complex Amenities</a></li>
                <li><a data-toggle="pill" data-next="p_image"  data-submit="p_features" href="<?= (isset($edit)) ? '#p_image' : 'javascript:' ?>" class="data_submit">Property Images</a></li>
                <li id="pro-lead-assig-tab-li"><a id="pro-lead-assig-tab" data-toggle="pill" data-next="lead-assignment" data-submit="p_contract" href="<?= (isset($edit)) ? '#lead-assignment' : '#lead-assignment' ?>" class="data_submit">Lead assignment</a></li>
                <li id="pro-contract-tab-li"><a id="agreement-tab" data-toggle="pill" data-next="p_contract" data-submit="p_image" href="<?= (isset($edit)) ? '#p_contract' : 'javascript:' ?>" class="data_submit">Property Agreement</a></li>
            </ul>

            <div class="tab-content">
                <?php if (!empty($_GET['t'])) { ?>
                    <div id="p_info" class="tab-pane fade">
                    <?php } else { ?>
                        <div id="p_info" class="tab-pane fade in active">
                        <?php } ?>
                        <div class= "row">
                            <div class= "col-md-12 col-sm-12"><h3>Property information</h3> </div>
                        </div>
                        <div class="col-md-10 col-sm-10">
                            <div class="parmentaddress parmentaddressnew">
                                <?php
                                $form = ActiveForm::begin(['id' => 'ownerform', 'options' => [
                                                'class' => 'form-inline'
                                            ],
                                            'fieldConfig' => [
                                                'template' => "{label}{input}{error}",
                                                'options' => [
                                                    'tag' => 'false'
                                                ]
                                            ],
                                            'enableAjaxValidation' => true]);
                                ?>	

                                <?php
                                if ($model->status == 0) {
                                    $disabled = '';
                                } else {

                                    $disabled = ' isDisabledd';
                                }
                                ?>

                                <?= $form->field($model, 'property_name')->textInput(['maxlength' => true, 'placeholder' => 'Property Name*', 'required' => true, 'class' => 'form-control toBeDisabled' . $disabled, 'autocomplete' => 'off']) ?>

                                <?= $form->field($model, 'property_description')->textarea(['rows' => '5', 'placeholder' => 'Property Description*', 'autocomplete' => 'off']) ?>

                                <input type="hidden" id="redirection" value="<?= (isset($_GET['redirection'])) ? '1' : '0' ?>">

                                <?php
                                $proGen = \app\models\PropertyGender::find()->all();
                                $proGenData = ArrayHelper::map($proGen, 'id', 'name');
                                ?>
                                <?= $form->field($model, 'gender')->dropDownList($proGenData, ['prompt' => 'Select Available for', 'class' => 'form-control toBeDisabled' . $disabled])->label('Available for'); ?>

                                <?php
                                $manBy = \app\models\ManagedBy::find()->all();
                                $manByData = ArrayHelper::map($manBy, 'id', 'name');
                                ?>
                                <?= $form->field($model, 'managed_by')->dropDownList($manByData, ['prompt' => 'Select Managed By', 'class' => 'form-control toBeDisabled' . $disabled]); ?>

                                <div class="form-group">
                                    <label for="exampleInputPassword1">Property Type</label>
                                    <?php
                                    $states = \app\models\PropertyTypes::find()->all();
                                    $stateData = ArrayHelper::map($states, 'id', 'property_type_name');
                                    ?>
                                    <?= $form->field($model, 'property_type')->dropDownList($stateData, ['prompt' => 'Select Property Type', 'class' => 'form-control toBeDisabled' . $disabled, 'autocomplete' => 'off'])->label(false); ?>

                                </div>

                                <div id="colive" style="display:none;">

                                    <?= $form->field($model, 'is_room')->dropDownList([0 => 'Beds with rooms', 1 => 'Rooms'], ['prompt' => 'Is Room', 'class' => 'form-control toBeDisabled' . $disabled]) ?>

                                </div>
                                <div id="no_of_beds" style="display:none;">
                                    <?php
                                    if (isset($edit)) {
                                        $roomsOne = \app\models\ChildProperties::find()->where(['main' => $model->id, 'type' => 1])->all();
                                        $roomsCountOne = count($roomsOne);
                                        ?>

                                    <?php } ?>
                                    <input type="hidden" id="beds_room" value="<?= (isset($edit)) ? $roomsCountOne : 0; ?>">
                                    <?= $form->field($model, 'beds_room')->textInput(['class' => 'form-control isNumber blockedKey toBeDisabled' . $disabled, 'autocomplete' => 'off', 'value' => (isset($edit)) ? $roomsCountOne : '']) ?>
                                    <div id="newFields">
                                        <?php
                                        if (isset($edit)) {
                                            foreach ($roomsOne as $key => $data) {
                                                $roomsChild = \app\models\ChildProperties::find()->where(['parent' => $data->id, 'type' => 2])->all();
                                                $roomsChild2 = \app\models\ChildProperties::find()->where(['id' => $data->id, 'type' => 1])->one();
                                                $roomsChildOne = count($roomsChild);
                                                ?>
                                                <label> Room <?= $key + 1 ?> </label>
                                                <input style="width: 200px !important;" type="text" placeholder="Sub Unit Name*" value="<?= $roomsChild2->sub_unit_name ?>" name="Properties[sub_unit_name][]" class="form-control toBeDisabled<?= $disabled ?>">
                                                <input style="width: 100px !important;" type="text" placeholder="Beds*" value="<?= $roomsChildOne ?>" name="Properties[beds][]"  class="form-control isNumber toBeDisabled<?= $disabled ?>" autocomplete='off'>
                                                <br />
                                                <?php
                                            }
                                        }
                                        ?>

                                    </div>
                                </div>
                                <div id="no_of_rooms" style="display:none;">

                                    <?= $form->field($model, 'rooms')->textInput(['class' => 'form-control isNumber toBeDisabled' . $disabled, 'autocomplete' => 'off']) ?>
                                </div>

                                <div id="flat" style="display:none;">
                                    <?= $form->field($model, 'flat_bhk')->textInput(['class' => 'form-control isNumber toBeDisabled' . $disabled, 'autocomplete' => 'off', 'placeholder' => 'Flat BHK*'])->label('Flat BHK') ?>

                                    <?= $form->field($model, 'flat_area')->textInput(['class' => 'form-control isNumber toBeDisabled' . $disabled, 'autocomplete' => 'off', 'placeholder' => 'Flat Area (sq. feet)*'])->label('Flat Area (sq. feet)') ?>

                                </div>  

                                <?= $form->field($model, 'unit')->textInput(['maxlength' => true, 'placeholder' => 'Unit *', 'required' => true, 'class' => 'form-control toBeDisabled' . $disabled, 'autocomplete' => 'off']) ?>

                                <?= $form->field($model, 'address_line_1')->textInput(['maxlength' => true, 'id' => 'searchTextField', 'placeholder' => 'Address Line 1*', 'class' => 'form-control toBeDisabled' . $disabled, 'autocomplete' => 'off']) ?>

                                <?= $form->field($model, 'address_line_2')->textInput(['maxlength' => true, 'placeholder' => 'Address Line 2', 'class' => 'form-control toBeDisabled' . $disabled, 'autocomplete' => 'off']) ?>


                                <?php
                                $userType = Yii::$app->user->identity->user_type;
                                $userId = Yii::$app->user->identity->id;
                                if ($userType == 7) {
                                    $opsModel = \app\models\OperationsProfile::findOne(['operations_id' => $userId]);
                                    if ($opsModel->role_code == 'OPEXE' && $opsModel->role_value == 'ELHQ') {
                                        $states = \app\models\States::find()->where(['status' => 1])->all();
                                    } else if ($opsModel->role_code == 'OPSTMG') {
                                        $states = \app\models\States::find()->where(['status' => 1, 'state_code' => $opsModel->role_value])->all();
                                    } else if ($opsModel->role_code == 'OPCTMG') {
                                        $cities = \app\models\Cities::find()->where(['status' => 1, 'code' => $opsModel->role_value])->one();
                                        $states = \app\models\States::find()->where(['status' => 1, 'code' => $cities->state_code])->all();
                                    } else if ($opsModel->role_code == 'OPBRMG') {
                                        $branchModel = \app\models\Branches::findOne(['branch_code' => $opsModel->role_value]);
                                        $cities = \app\models\Cities::find()->where(['status' => 1, 'code' => $branchModel->city_code])->one();
                                        $states = \app\models\States::find()->where(['status' => 1, 'code' => $cities->state_code])->all();
                                    } else if ($opsModel->role_code == 'OPEXE') {
                                        $branchModel = \app\models\Branches::findOne(['branch_code' => $opsModel->role_value]);
                                        $cities = \app\models\Cities::find()->where(['status' => 1, 'code' => $branchModel->city_code])->one();
                                        $states = \app\models\States::find()->where(['status' => 1, 'code' => $cities->state_code])->all();
                                    }
                                } else if ($userType == 6) {
                                    $saleModel = \app\models\SalesProfile::findOne(['sale_id' => $userId]);
                                    if ($saleModel->role_code == 'SLEXE' && $saleModel->role_value == 'ELHQ') {
                                        $states = \app\models\States::find()->where(['status' => 1])->all();
                                    } else if ($saleModel->role_code == 'SLSTMG') {
                                        $states = \app\models\States::find()->where(['status' => 1, 'state_code' => $saleModel->role_value])->all();
                                    } else if ($saleModel->role_code == 'SLCTMG') {
                                        $cities = \app\models\Cities::find()->where(['status' => 1, 'code' => $saleModel->role_value])->one();
                                        $states = \app\models\States::find()->where(['status' => 1, 'code' => $cities->state_code])->all();
                                    } else if ($saleModel->role_code == 'SLBRMG') {
                                        $branchModel = \app\models\Branches::findOne(['branch_code' => $saleModel->role_value]);
                                        $cities = \app\models\Cities::find()->where(['status' => 1, 'code' => $branchModel->city_code])->one();
                                        $states = \app\models\States::find()->where(['status' => 1, 'code' => $cities->state_code])->all();
                                    } else if ($saleModel->role_code == 'SLEXE') {
                                        $branchModel = \app\models\Branches::findOne(['branch_code' => $saleModel->role_value]);
                                        $cities = \app\models\Cities::find()->where(['status' => 1, 'code' => $branchModel->city_code])->one();
                                        $states = \app\models\States::find()->where(['status' => 1, 'code' => $cities->state_code])->all();
                                    }
                                }

                                $stateData = \yii\helpers\ArrayHelper::map($states, 'code', 'name');
                                ?>

                                <?= $form->field($model, 'state')->dropDownList($stateData, ['prompt' => 'Select state', 'url' => \Yii::$app->getUrlManager()->createUrl('users/getcity'), 'class' => 'form-control toBeDisabled' . $disabled]); ?>

                                <?php
                                $cities = \app\models\Cities::find()->where(['state_code' => $model->state])->all();
                                $cityData = ArrayHelper::map($cities, 'code', 'city_name');
                                // echo $model
                                ?>
                                <?= $form->field($model, 'city')->dropDownList($cityData, ['prompt' => 'Select city', 'class' => 'form-control toBeDisabled' . $disabled, 'url' => \Yii::$app->getUrlManager()->createUrl('advisers/getregions'), $model->city => array('selected' => true)]); ?>

                                <?php
                                $branches = \app\models\Branches::find()->where(['city_code' => $model->city])->all();
                                $branchData = ArrayHelper::map($branches, 'branch_code', 'branch_code');
                                ?>
                                <?php //echo $form->field($model, 'branch_code')->dropDownList($branchData, ['prompt' => 'Select Branch', 'class' => 'form-control'])->label('Branch'); ?>
                                <?php //} ?>
                                <?php //if($model->status==0) { ?>
                                <false class="field-properties required">
                                <label class="control-label">Location</label><input type="text" id="location" class="form-control toBeDisabled<?= $disabled ?>" placeholder="Location*" aria-required="true" aria-invalid="false" autocomplete='off'><div class="help-block"></div>
                                </false>
                                <?= $form->field($model, 'lat')->textInput(['maxlength' => true, 'placeholder' => 'Latitude*', 'class' => 'form-control isLocation toBeDisabled' . $disabled, 'autocomplete' => 'off'])->label('Latitude'); ?>
                                <?= $form->field($model, 'lon')->textInput(['maxlength' => true, 'placeholder' => 'Longitude*', 'class' => 'form-control isLocation toBeDisabled' . $disabled, 'autocomplete' => 'off'])->label('Longitude'); ?>
                                <?= $form->field($model, 'pincode')->textInput(['maxlength' => 15, 'placeholder' => 'Pincode*', 'class' => 'form-control toBeDisabled' . $disabled, 'autocomplete' => 'off']); ?>





                                <?= $form->field($model, 'flat_type')->dropDownList([0 => 'Semi-furnished', 1 => 'Furnishsed'], ['prompt' => 'Select Type', 'class' => 'form-control toBeDisabled' . $disabled]); ?>
                                <script>
                                    $(function () {
                                        var input = $('<input style="width: 200px !important;" type="text" placeholder="Sub Unit Name*" name="Properties[sub_unit_name][]" value="" class="form-control toBeDisabled<?= $disabled ?>">  <input style="width: 100px !important;" type="text" placeholder="Beds*" name="Properties[beds][]" value="" class="form-control isNumber toBeDisabled<?= $disabled ?>"> <br> ');
                                        var newFields = $('');
                                        var newlabel = $('');

                                        $('#properties-beds_room').bind('keyup change', function () {
                                            var current = parseInt($('#beds_room').val());
                                            var n = this.value || 0;
                                            if (n + 1) {
                                                if (parseInt(n) > current) {
                                                    addFields(n - current, current);
                                                } else {
                                                    removeFields(current - n);
                                                }

                                            }
                                            $('#beds_room').val(n);
                                        });

                                        function addFields(n, current) {

                                            for (i = 0; i < n; i++) {
                                                var newInput = input.clone();
                                                var label = $('<label> Room ' + (current + i + 1) + ' </label>').clone();

                                                newFields = newFields.add(newInput);
                                                newlabel = newlabel.add(label);
                                                label.appendTo('#newFields');
                                                newInput.appendTo('#newFields');
                                            }

                                        }

                                        function removeFields(n) {
                                            for (var i = 1; i <= n; i++) {
                                                $('#newFields input:last-child').remove();
                                                $('#newFields label:last-child').remove();
                                            }

                                        }
                                    });

                                </script>
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
		   
		   $('#properties-is_room').val('0');
		   $('#properties-no_of_rooms').val('0');
		   $('#properties-no_of_beds').val('0');
		   
    } else {
    
	 //   if($('#properties-is_room').val() ==''){
	   
		// 	   $('#flat').css('display','none'); 
		// 	   $('#no_of_beds').css('display','none'); 
		// 	   $('#no_of_rooms').css('display','none'); 
			   
		// } else if( $('#properties-is_room').val() == 1 ){
		
		// 	   $('#flat').css('display','none'); 
		// 	   $('#no_of_beds').css('display','none'); 
		// 	   $('#no_of_rooms').css('display','block'); 
			   
		// 	   $('#properties-flat_bhk').val('0');
		// 	   $('#properties-flat_area').val('0');
		// 	   $('#properties-no_of_beds').val('0');

			   
		//   } else  {
		  
			   $('#flat').css('display','none'); 
			   $('#no_of_beds').css('display','block'); 
			   $('#no_of_rooms').css('display','none'); 
			   
			   
			   $('#properties-flat_bhk').val('0');
			   $('#properties-flat_area').val('0');
			   $('#properties-no_of_rooms').val('0');
			
		  // }
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
      }else if ( this.value == '') {
		   $('#flat').css('display','none'); 
		   $('#colive').css('display','none'); 
		   $('#no_of_beds').css('display','none'); 
		   $('#no_of_rooms').css('display','none'); 
		   
		   $('#properties-is_room').val('');
		   $('#properties-no_of_rooms').val('');
		   $('#properties-no_of_beds').val('');
		   
      } else  {
           $('#flat').css('display','none'); 
		   $('#colive').css('display','none'); 
		   $('#no_of_beds').css('display','block'); 
		   $('#no_of_rooms').css('display','none'); 
		   
		   $('#properties-flat_bhk').val('0');
		   $('#properties-flat_area').val('0');
		   $('#properties-no_of_beds').val('0');
		   $('#properties-is_room').val('0');
		   $('#properties-no_of_rooms').val('0');
      
      }
    });
    
   /* $('#properties-is_room').on('change', function() {
      if ( this.value == '1')
      {
		   $('#flat').css('display','none'); 
		   $('#no_of_beds').css('display','none'); 
		   $('#no_of_rooms').css('display','block'); 
		   
			   $('#properties-flat_bhk').val('0');
			   $('#properties-flat_area').val('0');
			   $('#properties-no_of_beds').val('0');
      }else if ( this.value == '')  {
		   $('#flat').css('display','none'); 
		   $('#no_of_beds').css('display','none'); 
		   $('#no_of_rooms').css('display','none'); 
		   
			   $('#properties-flat_bhk').val('0');
			   $('#properties-flat_area').val('0');
			   $('#properties-no_of_beds').val('0');
		   
      } else  {
          
           $('#flat').css('display','none'); 
		   $('#no_of_beds').css('display','block'); 
		   $('#no_of_rooms').css('display','none'); 
		   
			   $('#properties-flat_bhk').val('0');
			   $('#properties-flat_area').val('0');
			   $('#properties-no_of_rooms').val('0');
      
      }
    });		*/					
     });
    ", View::POS_END, 'my-button-handler'
                                );
                                ?>
                                <div class="col-lg-3 col-sm-3">
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                </div>
                                <div style="padding-top:20px; padding-left: 40px;" class="row pull-right col-lg-9 col-sm-9 text-right">
                                    <div class="col-lg-5 col-sm-5">

                                        <!-- <a data-toggle="pill" id="form_property_listing" class="btn savechanges_submit_contractaction click_window" data-click="#p_features">Next</a> -->

                                        <?php echo Html::submitButton('Next', ['class' => 'btn btn savechanges_submit_contractaction', 'id' => (isset($edit)) ? "edit_form_property_listing" : "form_property_listing", 'data-click' => "#p_features", 'onclick' => 'goTop()']) ?>

                                    </div>
                                    <div class="col-lg-5 col-sm-5">
                                        <button type="button" class="btn savechanges_submit <?= (!isset($edit) ? 'confirm_close1' : 'confirm_close2') ?> verify_close1 cancel_confirm_all" data-dismiss="modal">cancel</button>

                                    </div>
                                    &nbsp; 
                                </div>

                                <?php ActiveForm::end(); ?>

                            </div>     

                        </div>
                    </div>
                    <div id="p_features" class="tab-pane fade">
                        <div class= "row">
                            <div class= "col-md-12 col-sm-12">
                                <h3>Property & Complex Amenities</h3>
                            </div>

                        </div>
                        <br>
                        <div class="col-md-10 col-sm-10">
                            <div class="parmentaddress">
                                <h4>Property Features</h4>
                                <?php
                                $propertyAttrFacility = \app\models\PropertiesAttributes::find()->where(['type' => '1'])->all();
                                $propertyAttrComplex = \app\models\PropertiesAttributes::find()->where(['type' => '2'])->all();

                                $dataProper = [];
                                $dataPropers = \app\models\PropertyAttributeMap::find()->where(['property_id' => $model->id])->all();
                                foreach ($dataPropers as $dataPrope) {
                                    $dataProper[] = $dataPrope->attr_id;
                                }
                                $dataIdsAttri = implode(',', $dataProper);
                                /* echo  "<pre>";
                                  print_r($dataProper);
                                  echo  "</pre>"; */
                                ?>
                                <div class="row">
                                    <div class="item active">
                                        <ul class="thumbnails thumbnailslide_new">
                                            <?php foreach ($propertyAttrFacility as $keyfac => $propertyAttrFac) { ?>
                                                <li class="col-md-2 col-sm-4 thumbnailslide">
                                                    <div class="fff">
                                                        <div class="thumbnail">
                                                            <p>
                                                                <img style="width: 50px;" src="../<?= $propertyAttrFac->icon; ?>" alt="Icon">
                                                                <span><?= $propertyAttrFac->name; ?></span>
                                                            </p>
                                                            <label class="checkbox-inline">
                                                                <input class="check_click"  <?= in_array($propertyAttrFac->id, $dataProper) ? 'checked' : '' ?> data-id="text<?= $propertyAttrFac->id ?>" type="checkbox" value="<?= $propertyAttrFac->id; ?>">

                                                            </label>
                                                        </div>
                                                        <div>
                                                            <input type="text" data-name="<?= $propertyAttrFac->id ?>" id="text<?= $propertyAttrFac->id ?>" value="<?= in_array($propertyAttrFac->id, $dataProper) ? Yii::$app->userdata->getFacilityValue($propertyAttrFac->id, $model->id) : '' ?>"   <?= in_array($propertyAttrFac->id, $dataProper) ? '' : 'style="display: none"' ?> class="attrText form-control">
                                                        </div>

                                                    </div>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>


                                </div>


                                <div class="row">
                                    <div class="item active">
                                        <h4>Complex Features</h4>
                                        <ul class="thumbnails thumbnailslide_new">
                                            <?php foreach ($propertyAttrComplex as $keyCom => $propertyAttrCom) { ?>
                                                <li class="col-md-2 col-sm-4 thumbnailslide">
                                                    <div class="fff">
                                                        <div class="thumbnail">
                                                            <p>
                                                                <img style="width: 50px;" src="../<?= $propertyAttrCom->icon; ?>" alt="Icon">
                                                                <span><?= $propertyAttrCom->name; ?></span>
                                                            </p>
                                                            <label class="checkbox-inline">
                                                                <input class="check_click" <?= in_array($propertyAttrCom->id, $dataProper) ? 'checked' : '' ?>   type="checkbox" value="<?= $propertyAttrCom->id; ?>" data-id="text<?= $propertyAttrCom->id ?>">

                                                            </label>
                                                        </div>

                                                    </div>
                                                    <div>
                                                        <!-- <input type="text"  data-name="<?//= $propertyAttrCom->id?>" id="text<?//= $propertyAttrCom->id ?>" value="<?//= in_array($propertyAttrCom->id  ,$dataProper) ? Yii::$app->userdata->getFacilityValue($propertyAttrCom->id,$model->id):''  ?>" <?//= in_array($propertyAttrCom->id  ,$dataProper) ? '':'style="display: none"'  ?> class="attrText form-control isNumber"> -->
                                                    </div>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>



                                <div style="padding-top:20px;" class="row pull-right col-lg-8 col-sm-10">
                                    <div class="col-lg-4 col-sm-4">
                                        <button type="button" class="btn savechanges_submit_previous savechanges_submit_previous1 previous_btn" data-click="#p_info">Previous</button>
                                    </div>
                                    <div class="col-lg-4 col-sm-4" style="text-align: center;">
                                        <input type="hidden" id="attributes" name="attributes" value="<?= $dataIdsAttri; ?>"> 
                                        <a data-toggle="pill"  class="btn savechanges_submit_contractaction click_window save_property_features" data-click="#p_image"  onclick="goTop();">Next</a>

                                    </div>
                                    <div class="col-lg-4 col-sm-4">
                                        <button type="button" class="btn savechanges_submit <?= (!isset($edit) ? 'confirm_close' : 'confirm_close2') ?> verify_close2 cancel_confirm_all" data-dismiss="modal" style="float:left;">cancel</button>

                                    </div>
                                    &nbsp; 
                                </div>

                            </div>
                        </div>
                    </div>

                    <div id="p_image" class="tab-pane fade">

                        <div class= "col-md-12 col-sm-12">
                            <h3>Property Image</h3>
                        </div>

                        <br>
                        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
                        <table id="dataimages" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style= "width: 40%">Image (Thumbnail)</th>
                                    <th>Default</th>
                                    <th style= "width : 20%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                if (isset($edit)) {
                                    foreach ($modelImages as $key => $images) {
                                        ?>
                                        <tr>
                                            <td><img src="<?php echo ($images) ? "../" . $images->image_url : Url::home(true) . 'images/gallery_imgone.jpg'; ?>" alt="Image" class="mCS_img_loaded" width="100%"></td>
                                            <td><div class="radio">
                                                    <label><input value="<?= $images->id; ?>" type="radio" name="optradio" class="" <?= $images->is_main == 1 ? 'checked' : ''; ?> ></label>
                                                </div></td>
                                            <td><span><i data-id="<?= $images->id; ?>"  class="fa fa-trash fa-3x deleteimg" aria-hidden="true"></i></span></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                        <div class="row" style= "width:30%; padding: 10px;">

                            <input type="hidden" name="property_id" value="<?= $model->id; ?>" id="property_id" />
                            <!-- <input type= "file" name= "PropertyImages['image_url'][]" id= "add_image"  style="display: none;"> -->
                            <button onclick="apply_image('add_image'); return false;"  class= "btn savechanges_submit_contractaction" type="button">Add New Image</button>
                            <div class="imageList" style="display: none">

                            </div>


                        </div>
                        <div class="col-lg-10 col-sm-10">
                            <div style="padding-top:20px;" class="row pull-right col-lg-8 col-sm-10">
                                <div class="col-lg-4 col-sm-4">
                                    <button type="button" class="btn savechanges_submit_previous savechanges_submit_previous1  previous_btn" data-click="#p_features">Previous</button>

                                </div>
                                <div class="col-lg-4 text-center col-sm-4">

                                    <a data-toggle="pill"  class="btn savechanges_submit_contractaction click_window setAsDefault" data-click="#lead-assignment"  onclick="goTop();">Next</a>
                                </div>
                                <div class="col-lg-4 col-sm-4" style="padding:0px 4px;">
                                    <button type="button" class="btn savechanges_submit <?= (!isset($edit) ? 'confirm_close' : 'confirm_close2') ?> verify_close3 cancel_confirm_all" data-dismiss="modal" style="float:left; margin-left:0px;">cancel</button>

                                </div>
                                <div class="col-lg-4">
                                </div>
                                &nbsp; 
                            </div>
                        </div>
                    </div>



                    <div id="p_contract" class="tab-pane fade">

                        <div class= "row">
                            <div class= "col-md-12 col-sm-12">
                                <h3>Property Agreement</h3>
                            </div>
                        </div>
                        <br>
                        <div class="col-md-10 col-sm-10">
                            <div class="parmentaddress parmentaddressnew">
                                <?php
                                $form = ActiveForm::begin(['id' => 'property_agreement', 'action' => 'addnewagreement',
                                            'options' => [
                                                'class' => 'form-inline',
                                                'data-href' => 'addagreementajax',
                                                'enctype' => 'multipart/form-data'
                                            ],
                                            'fieldConfig' => [
                                                'template' => "{label}{input}{error}",
                                                'options' => [
                                                    'tag' => 'false'
                                                ]
                                            ],
                                            'enableAjaxValidation' => false]);
                                ?>
                                <?php
                                //if(($model->status=='0' && isset($edit))||(!isset($edit))){
                                //echo $form->field($modelPropertyAgreements, 'agreement_type')->dropDownList([1 => 'Normal Leasing', 2 => 'Guaranteed Leasing', 3 => 'Co-living'], ['prompt' => 'Select Agreement Type', 'class' => 'form-control']);
                                echo $form->field($modelPropertyAgreements, 'agreement_type')->dropDownList([1 => 'Normal Leasing', 2 => 'Guaranteed Leasing'], ['prompt' => 'Select Agreement Type', 'class' => 'form-control' . $disabled]);
                                if (!empty($disabled)) {
                                    ?>
                                    <input type="hidden" name="PropertyAgreements[agreement_type]" value="<?= $modelPropertyAgreements->agreement_type ?>" />
                                    <?php
                                }
                                // }
                                // else{
                                // echo $form->field($modelPropertyAgreements, 'agreement_type')->dropDownList([1 => 'Normal Leasing',2 =>'Guaranteed Leasing',3=>'Co-living'],['prompt'=>'Select Agreement Type','disabled'=>true]); 
                                // }
                                ?>

                                <input type="hidden" name="proagreetab" value="1" />


                                <?php
                                if (isset($model->owner_id)) {
                                    $owner = $model->owner_id;
                                } else {
                                    $owner = base64_decode($_GET['id']);
                                }
                                ?>

                                <?= $form->field($modelPropertyAgreements, 'rent')->textInput(['maxlength' => true, 'placeholder' => 'Rent*', 'class' => 'form-control isNumber isDisabled toBeDisabled', 'autocomplete' => 'off']) ?>
                                <?= $form->field($modelPropertyAgreements, 'property_id')->hiddenInput(['placeholder' => 'Rent*', 'class' => 'form-control isNumber', 'value' => (isset($edit)) ? $model->id : ''])->label(false); ?>
                                <?= $form->field($modelPropertyAgreements, 'owner_id')->hiddenInput(['value' => $owner, 'placeholder' => 'Rent*'])->label(false); ?>
                                <?= $form->field($modelPropertyAgreements, 'manteinance')->textInput(['maxlength' => true, 'placeholder' => 'Maintenance*', 'class' => 'form-control isNumber isDisabled toBeDisabled', 'autocomplete' => 'off', 'value' => (!isset($edit)) ? 0 : $modelPropertyAgreements->manteinance])->label('Maintenance') ?>
                                <?= $form->field($modelPropertyAgreements, 'deposit')->textInput(['maxlength' => true, 'placeholder' => 'Deposit*', 'class' => 'form-control isNumber isDisabled toBeDisabled', 'autocomplete' => 'off', 'value' => (!isset($edit)) ? 0 : $modelPropertyAgreements->deposit]) ?>
                                <?= $form->field($modelPropertyAgreements, 'rent_start_date')->textInput(['maxlength' => true, 'placeholder' => 'Rent Start Date*', 'class' => 'form-control datepicker isDisabled toBeDisabled', 'autocomplete' => 'off', 'value' => (!isset($edit)) ? 0 : $modelPropertyAgreements->rent_start_date]) ?>
                                <input type="hidden" name="agreement1" id="agreement1" value="0">
                                <?= $form->field($modelPropertyAgreements, 'contract_start_date')->textInput(['maxlength' => true, 'placeholder' => 'Contract Start Date*', 'class' => 'form-control datepicker toBeDisabled', 'datacomp' => 'datepickerEndComplete', 'minDate' => '23-02-2018', 'autocomplete' => 'off']) ?>
                                <?= $form->field($modelPropertyAgreements, 'contract_end_date')->textInput(['maxlength' => true, 'placeholder' => 'Contract End Date*', 'class' => 'form-control datepickerEndComplete toBeDisabled', 'datacomp' => 'datepickerEndComplete', 'minDate' => '23-02-2018', 'autocomplete' => 'off']) ?>
                                <?php
                                // }
                                ?>


                                <?= $form->field($modelPropertyAgreements, 'furniture_rent')->textInput(['maxlength' => true, 'placeholder' => 'Furniture Rent', 'class' => 'form-control', 'autocomplete' => 'off']) ?>
                                <?= $form->field($modelPropertyAgreements, 'pms_commission')->textInput(['maxlength' => true, 'placeholder' => 'PMS Fee*', 'class' => 'form-control isNumber', 'autocomplete' => 'off'])->label('PMS Fee %') ?>
                                <?= $form->field($modelPropertyAgreements, 'notice_peroid')->textInput(['maxlength' => true, 'placeholder' => 'Notice Period*', 'class' => 'form-control isNumber1', 'autocomplete' => 'off'])->label('Notice Period (months)') ?>
                                <?= $form->field($modelPropertyAgreements, 'min_contract_peroid')->textInput(['maxlength' => true, 'placeholder' => 'Minimum Contract Period*', 'class' => 'form-control isNumber1', 'autocomplete' => 'off'])->label('Minimum Contract Period (months)') ?>
                                <?= $form->field($modelPropertyAgreements, 'agreement_url')->fileInput(['class' => 'form-control']) ?> 
                                <div class="previewDoc " id="agreement_doc">
                                    <?php if (isset($modelPropertyAgreements->agreement_url) && $modelPropertyAgreements->agreement_url != '') {
                                        ?>			          	

                                            <!--object data="<?= Url::home(true) . $modelPropertyAgreements->agreement_url ?>" type="application/pdf" class="abc" width="100%" frameborder="0" allowfullscreen>
                                                <a href="<?= Url::home(true) . $modelPropertyAgreements->agreement_url ?>">test.pdf</a>
                                            </object-->

                                            <!--<embed src="<?= Url::home(true) . $modelPropertyAgreements->agreement_url ?>#toolbar=0&navpanes=0&scrollbar=0" type="application/pdf" class="abc" width="100%" frameborder="0" allowfullscreen />-->
                                        <iframe src="//docs.google.com/gview?url=<?php echo Url::home(true) . $modelPropertyAgreements->agreement_url ?>&embedded=true" class="abc" width="100%" frameborder="0" allowfullscreen></iframe>
                                        <?php
                                    }
                                    ?>
                                </div><br/>	


                                <div style="padding-top:20px;" class="row pull-right col-lg-12 col-sm-12">

                                    <div class="col-lg-3 col-sm-3 text-center">
                                        <?php //echo Html::submitButton('Save', ['class' => 'btn btn savechanges_submit_contractaction', 'id' => 'saveContract', 'data-click' => '#lead-assignment', 'onclick' => 'nextagreement();'])  ?>
                                        <!--<a data-toggle="pill"  class="btn savechanges_submit_contractaction click_window setAsDefault" id="saveContract" data-click="#lead-assignment"  onclick="nextagreement();">Next</a>-->
                                        <a data-toggle="pill"  class="btn savechanges_submit_contractaction click_window setAsDefault" id="saveContract">Save</a>
                                    </div>
                                    <div class="col-lg-3 col-sm-3">
                                        <button type="button" class="btn savechanges_submit <?= (!isset($edit) ? 'confirm_close' : 'confirm_close2') ?> verify_close4 cancel_confirm_all" data-dismiss="modal" style="float:left;">cancel</button>

                                    </div>
                                    <div class="col-lg-3 col-sm-3">
                                        <button class="btn savechanges_submit_previous activate_property"  style="<?= $model->status == 1 ? 'font-size:20px;padding:12.5px' : 'font-size:20px;padding:12px' ?>" data-status="<?= ($model->status == 1) ? '0' : '1' ?>"><?= ($model->status == 1) ? 'Deactivate Property' : 'Activate Property' ?></button>
                                    </div>
                                    
                                    <div class="col-lg-3 col-sm-3">
                                        <button style="width: 94%;border-left: 1px solid white;" class="btn btn-primary <?= ($modelPropertyAgreements->notice_status != 0) ? "start-notification" : "stop-notification" ?>" onclick="return stopNotification('p', <?= $modelPropertyAgreements->id ?>, this);">
                                            <?= ($modelPropertyAgreements->notice_status != 0) ? "Restart Notification" : "Stop Notification" ?>
                                        </button>
                                    </div>
                                    &nbsp; 
                                </div>       

                                <?php ActiveForm::end(); ?>


                            </div>
                        </div>
                    </div>

                    <!-- This block is used while adding property -->
                    <?php if (isset($addProperty)) { ?>

                        <div id="lead-assignment" class="tab-pane fade">
                            <div class="col-lg-9 col-sm-9 col-xs-offset-1">
                                <h3>Lead assignment</h3>
                            </div>
                            <div class="row">
                                <div class="col-lg-9 col-sm-9 col-xs-offset-1">
                                    <div class="advisorbox">

                                        <?php $formLeads = ActiveForm::begin(['id' => 'property_lead_assignment', 'action' => 'addleadassignmentproperty', 'method' => 'post', 'options' => ['enctype' => 'multipart/form-data']]); ?>
                                        <input type="hidden" value="1" name="lead_assignment" />

                                        <?php
                                        $currentRoleCode = NULL;
                                        $currentRoleValue = NULL;

                                        if (Yii::$app->user->identity->user_type == 6) {
                                            $currentSaleProfile = app\models\SalesProfile::findOne(['sale_id' => Yii::$app->user->id]);
                                            $currentRoleCode = @$currentSaleProfile->role_code;
                                            $currentRoleValue = @$currentSaleProfile->role_value;
                                        } else if (Yii::$app->user->identity->user_type == 7) {
                                            $currentOperationProfile = app\models\OperationsProfile::findOne(['operations_id' => Yii::$app->user->id]);
                                            $currentRoleCode = @$currentOperationProfile->role_code;
                                            $currentRoleValue = @$currentOperationProfile->role_value;
                                        }
                                        ?>

                                        <input type="hidden" name="redirect" value="<?= $_GET['id'] ?>" />

                                        <input type="hidden" name="owner_id" value="<?= base64_decode($_GET['id']) ?>" />
                                        <input id="lead-assign-property-id" type="hidden" name="property_id" value="" />

                                        <br />

                                        <?php
                                        if (
                                                $currentRoleValue == 'ELHQ' || $currentRoleCode == 'OPCTMG' || $currentRoleCode == 'OPSTMG' ||
                                                $currentRoleCode == 'SLCTMG' || $currentRoleCode == 'SLSTMG'
                                        ) {
                                            ?>
                                            <label class="control-label" for="assigned_level_1">
                                                <input type="radio" name="assigned_level" value="1" class="assigned_level" id="assigned_level_1" />
                                                City Level &nbsp;&nbsp;
                                            </label>
                                        <?php } ?>

                                        <?php
                                        if (
                                                $currentRoleCode == 'OPCTMG' || $currentRoleCode == 'OPSTMG' ||
                                                $currentRoleCode == 'SLCTMG' || $currentRoleCode == 'SLSTMG' ||
                                                $currentRoleCode == 'OPBRMG' || $currentRoleCode == 'SLBRMG' ||
                                                $currentRoleCode == 'OPEXE' || $currentRoleCode == 'SLEXE'
                                        ) {
                                            ?>
                                            <label class="control-label" for="assigned_level_2">
                                                <input type="radio" name="assigned_level" value="2" class="assigned_level" id="assigned_level_2" />
                                                Branch Level &nbsp;&nbsp;
                                            </label>
                                        <?php } ?>

                                        <?php if ($currentRoleValue == 'ELHQ') { ?>
                                            <label class="control-label" for="assigned_level_3">
                                                <input type="radio" name="assigned_level" value="3" class="assigned_level" id="assigned_level_3" />
                                                ELHQ Level &nbsp;&nbsp;
                                            </label>
                                        <?php } ?>

                                        <br /><br />

                                        <div class="form-group field-leadsowner-ref_status has-success">
                                            <label class="control-label" for="leadsowner-ref_status">City</label>
                                            <select class="form-control" name="assigned_city" id="assigned_city">
                                                <option value="">Select City</option>
                                                <?php
                                                $userType = Yii::$app->user->identity->user_type;
                                                $userId = Yii::$app->user->identity->id;
                                                if ($userType == 7) {
                                                    $opsModel = \app\models\OperationsProfile::findOne(['operations_id' => $userId]);
                                                    if ($opsModel->role_code == 'OPEXE' && $opsModel->role_value == 'ELHQ') {
                                                        $cities = \app\models\Cities::find()->where(['status' => 1])->all();
                                                    } else if ($opsModel->role_code == 'OPSTMG') {
                                                        $cities = \app\models\Cities::find()->where(['status' => 1, 'state_code' => $opsModel->role_value])->all();
                                                    } else if ($opsModel->role_code == 'OPCTMG') {
                                                        $cities = \app\models\Cities::find()->where(['status' => 1, 'code' => $opsModel->role_value])->all();
                                                    } else if ($opsModel->role_code == 'OPBRMG') {
                                                        $branchModel = \app\models\Branches::findOne(['branch_code' => $opsModel->role_value]);
                                                        $cities = \app\models\Cities::find()->where(['status' => 1, 'code' => $branchModel->city_code])->all();
                                                    } else if ($opsModel->role_code == 'OPEXE') {
                                                        $branchModel = \app\models\Branches::findOne(['branch_code' => $opsModel->role_value]);
                                                        $cities = \app\models\Cities::find()->where(['status' => 1, 'code' => $branchModel->city_code])->all();
                                                    }
                                                } else if ($userType == 6) {
                                                    $saleModel = \app\models\SalesProfile::findOne(['sale_id' => $userId]);
                                                    if ($saleModel->role_code == 'SLEXE' && $saleModel->role_value == 'ELHQ') {
                                                        $cities = \app\models\Cities::find()->where(['status' => 1])->all();
                                                    } else if ($saleModel->role_code == 'SLSTMG') {
                                                        $cities = \app\models\Cities::find()->where(['status' => 1, 'state_code' => $saleModel->role_value])->all();
                                                    } else if ($saleModel->role_code == 'SLCTMG') {
                                                        $cities = \app\models\Cities::find()->where(['status' => 1, 'code' => $saleModel->role_value])->all();
                                                    } else if ($saleModel->role_code == 'SLBRMG') {
                                                        $branchModel = \app\models\Branches::findOne(['branch_code' => $saleModel->role_value]);
                                                        $cities = \app\models\Cities::find()->where(['status' => 1, 'code' => $branchModel->city_code])->all();
                                                    } else if ($saleModel->role_code == 'SLEXE') {
                                                        $branchModel = \app\models\Branches::findOne(['branch_code' => $saleModel->role_value]);
                                                        $cities = \app\models\Cities::find()->where(['status' => 1, 'code' => $branchModel->city_code])->all();
                                                    }
                                                }

                                                foreach ($cities as $city) {
                                                    ?>

                                                    <option <?= ($model->city == $city->code) ? 'selected' : '' ?> value="<?= $city->code; ?>"><?= ($city->city_name); ?></option>

                                                <?php }
                                                ?>

                                            </select>
                                            <div class="help-block"></div>
                                        </div>

                                        <div class="form-group field-leadsowner-ref_status has-success">
                                            <label class="control-label" for="leadsowner-ref_status">Branch</label>
                                            <select class="form-control" name="assigned_branch" id="assigned_branch">
                                                <option value="">Select Branch</option>
                                                <?php
                                                if ($userType == 7) {
                                                    $opsModel = \app\models\OperationsProfile::findOne(['operations_id' => $userId]);
                                                    if ($opsModel->role_code == 'OPEXE' && $opsModel->role_value == 'ELHQ') {
                                                        $branches = \app\models\Branches::find()->where('branch_code != :branch_code', ['branch_code' => 'NA'])->all();
                                                    } else if ($opsModel->role_code == 'OPSTMG') {
                                                        $branches = \app\models\Branches::find()->where('branch_code != :branch_code AND state_code = :state_code', ['branch_code' => 'NA', 'state_code' => $opsModel->role_value])->all();
                                                    } else if ($opsModel->role_code == 'OPCTMG') {
                                                        $branches = \app\models\Branches::find()->where('branch_code != :branch_code AND city_code = :city_code', ['branch_code' => 'NA', 'city_code' => $opsModel->role_value])->all();
                                                    } else if ($opsModel->role_code == 'OPBRMG') {
                                                        $branches = \app\models\Branches::find()->where('branch_code = :branch_code', ['branch_code' => $opsModel->role_value])->all();
                                                    } else if ($opsModel->role_code == 'OPEXE') {
                                                        $branches = \app\models\Branches::find()->where('branch_code = :branch_code', ['branch_code' => $opsModel->role_value])->all();
                                                    }
                                                } else if ($userType == 6) {
                                                    $saleModel = \app\models\SalesProfile::findOne(['sale_id' => $userId]);
                                                    if ($saleModel->role_code == 'SLEXE' && $saleModel->role_value == 'ELHQ') {
                                                        $branches = \app\models\Branches::find()->where('branch_code != :branch_code', ['branch_code' => 'NA'])->all();
                                                    } else if ($saleModel->role_code == 'SLSTMG') {
                                                        $branches = \app\models\Branches::find()->where('branch_code != :branch_code AND state_code = :state_code', ['branch_code' => 'NA', 'state_code' => $saleModel->role_value])->all();
                                                    } else if ($saleModel->role_code == 'SLCTMG') {
                                                        $branches = \app\models\Branches::find()->where('branch_code != :branch_code AND city_code = :city_code', ['branch_code' => 'NA', 'city_code' => $saleModel->role_value])->all();
                                                    } else if ($saleModel->role_code == 'SLBRMG') {
                                                        $branches = \app\models\Branches::find()->where('branch_code = :branch_code', ['branch_code' => $saleModel->role_value])->all();
                                                    } else if ($saleModel->role_code == 'SLEXE') {
                                                        $branches = \app\models\Branches::find()->where('branch_code = :branch_code', ['branch_code' => $saleModel->role_value])->all();
                                                    }
                                                }

                                                foreach ($branches as $branch) {
                                                    ?>

                                                    <option <?= ($model->branch_code == $branch->branch_code) ? 'selected' : '' ?> value="<?= $branch->branch_code; ?>"><?= $branch->name . " [" . $branch->branch_code . "]"; ?></option>

                                                <?php }
                                                ?>
                                            </select>
                                            <div class="help-block"></div>
                                        </div>

                                        <div class="form-group field-leadsowner-ref_status has-success">
                                            <label class="control-label" for="leadsowner-ref_status">Operation's Member</label>
                                            <select class="form-control" name="assigned_operation" id="assigned_operation">
                                                <option value="">Select Operation</option>
                                                <?php if (!empty($propertyModel->operations_id)) { ?>
                                                    <option selected="selected" value="<?= $propertyModel->operations_id; ?>"><?= (Yii::$app->userdata->getEmailById($propertyModel->operations_id)) . " [" . Yii::$app->userdata->getRoleCodeByUserIdOps($propertyModel->operations_id) . "] [" . Yii::$app->userdata->getRoleValueByUserIdOps($propertyModel->operations_id) . "]"; ?></option>
                                                <?php } ?>
                                                <?php
                                                if (!empty($model->branch_code)) {
                                                    $opUsers = app\models\OperationsProfile::find()->where(' role_value = "' . $model->branch_code . '" ')->all();
                                                    foreach ($opUsers as $op) {
                                                        ?>
                                                        <option value="<?= $op->operations_id; ?>"><?= ($op->email) . " [" . $op->role_code . "] [" . $op->role_value . "]"; ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <div class="help-block"></div>
                                        </div>

                                        <div class="form-group field-leadsowner-ref_status has-success">
                                            <label class="control-label" for="leadsowner-ref_status">Sale's Member</label>
                                            <select class="form-control" name="assigned_sale" id="assigned_sale">
                                                <option value="">Select Sale</option>
                                                <?php if (!empty($propertyModel->sales_id)) { ?>
                                                    <option selected="selected" value="<?= $propertyModel->sales_id; ?>"><?= (Yii::$app->userdata->getEmailById($propertyModel->sales_id)) . " [" . Yii::$app->userdata->getRoleCodeByUserIdSales($propertyModel->sales_id) . "] [" . Yii::$app->userdata->getRoleValueByUserIdSales($propertyModel->sales_id) . "]"; ?></option>
                                                <?php } ?>
                                                <?php
                                                if (!empty($model->branch_code)) {
                                                    $slUsers = app\models\SalesProfile::find()->where(' role_value = "' . $model->branch_code . '" ')->all();
                                                    foreach ($slUsers as $sl) {
                                                        ?>
                                                        <option value="<?= $sl->sale_id; ?>"><?= ($sl->email) . " [" . $sl->role_code . "] [" . $sl->role_value . "]"; ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <div class="help-block"></div>
                                        </div>

                                        <br />

                                        <div class="row">
                                            <div class="col-lg-6 col-sm-6">
                                                <?php echo Html::submitButton('Next', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn savechanges_submit_contractaction', 'id' => 'owner-lead-assign-btn']) ?>
                                            </div>
                                            <div class="col-lg-6 col-sm-6">
                                                <a style="color:#FFF" class="btn savechanges_submit cancel_confirm_2" href="<?= Url::home(true); ?>sales/applicants" type="button">Cancel</a>
                                            </div>
                                        </div>

                                        <?php ActiveForm::end(); ?>

                                    </div>



                                    <!--------------------------------------->
                                    <div class="modal fade" id="myModal_edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                        <div class="modal-dialog modalbg modalbgpopup" role="document">
                                            <div class="modal-content">
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- This block is used while editing property -->
                    <?php if (!isset($addProperty)) { ?>

                        <div id="lead-assignment" class="tab-pane fade">
                            <div class="col-lg-9 col-sm-9 col-xs-offset-1">
                                <h3>Lead assignment</h3>
                            </div>
                            <div class="row">
                                <div class="col-lg-9 col-sm-9 col-xs-offset-1">
                                    <div class="advisorbox">

                                        <?php $formLeads = ActiveForm::begin(['id' => 'property_lead_assignment', 'action' => 'editleadassignmentproperty', 'method' => 'post', 'options' => ['enctype' => 'multipart/form-data']]); ?>
                                        <input type="hidden" value="1" name="lead_assignment" />

                                        <?php
                                        //$propertyModel = app\models\Properties::findOne(['owner_id' => $model->owner_id]);
                                        $propertyModel = $model;
                                        $currentRoleCode = NULL;
                                        $currentRoleValue = NULL;
                                        $currentBranchCode = $propertyModel->branch_code;

                                        if (Yii::$app->user->identity->user_type == 6) {
                                            $currentSaleProfile = app\models\SalesProfile::findOne(['sale_id' => Yii::$app->user->id]);
                                            $currentRoleCode = @$currentSaleProfile->role_code;
                                            $currentRoleValue = @$currentSaleProfile->role_value;
                                        } else if (Yii::$app->user->identity->user_type == 7) {
                                            $currentOperationProfile = app\models\OperationsProfile::findOne(['operations_id' => Yii::$app->user->id]);
                                            $currentRoleCode = @$currentOperationProfile->role_code;
                                            $currentRoleValue = @$currentOperationProfile->role_value;
                                        }

                                        $ownerId = $propertyModel->owner_id;
                                        $propertyId = $propertyModel->id;
                                        $propertyOperation = $propertyModel->operations_id;
                                        $propertySale = $propertyModel->sales_id;

                                        $operationRoleCode = null;
                                        $operationRoleValue = null;

                                        $saleRoleCode = null;
                                        $saleRoleValue = null;

                                        if (!empty($propertyOperation)) {
                                            $operationProfileModel = app\models\OperationsProfile::findOne(['operations_id' => $propertyOperation]);
                                            $operationRoleCode = $operationProfileModel->role_code;
                                            $operationRoleValue = $operationProfileModel->role_value;
                                        }

                                        if (!empty($propertySale)) {
                                            $saleProfileModel = app\models\SalesProfile::findOne(['sale_id' => $propertySale]);
                                            $saleRoleCode = $saleProfileModel->role_code;
                                            $saleRoleValue = $saleProfileModel->role_value;
                                        }
                                        ?>

                                        <input type="hidden" name="operation_role_code" value="<?= $operationRoleCode ?>" />
                                        <input type="hidden" name="operation_role_value" value="<?= $operationRoleValue ?>" />
                                        <input type="hidden" name="sale_role_code" value="<?= $saleRoleCode ?>" />
                                        <input type="hidden" name="sale_role_value" value="<?= $saleRoleValue ?>" />
                                        <input type="hidden" name="redirect" value="<?= $_GET['id'] ?>" />

                                        <input type="hidden" name="owner_id" value="<?= $ownerId ?>" />
                                        <input type="hidden" name="property_id" value="<?= $propertyId ?>" />

                                        <div class="form-group field-leadsowner-ref_status has-success" style="display: none;">
                                            <label class="control-label" for="leadsowner-ref_status">Branch Assigned To</label>
                                            <input type="text" readonly="readonly" name="assigned_to" value="<?= (!empty($model->branch_code)) ? $model->branch_code : ""; ?>" placeholder="<?= (!empty($model->branch_code)) ? $model->branch_code : "N/A"; ?>" class="form-control" />
                                            <div class="help-block"></div>
                                        </div><br />

                                        <label class="control-label" for="assigned_level_1">
                                            <?php
                                            $assignLevelCheck = ($operationRoleCode == 'OPCTMG' || $saleRoleCode == 'SLCTMG') ? true : false;
                                            $assignLevelCheck2 = ($operationRoleCode == 'OPBRMG' && $saleRoleCode == 'SLBRMG') ? true : false;
                                            $assignLevelCheck3 = ($operationRoleCode == 'OPBRMG' && $saleRoleCode == 'SLEXE') ? true : false;
                                            $assignLevelCheck4 = ($operationRoleCode == 'OPEXE' && $saleRoleCode == 'SLBRMG') ? true : false;
                                            //$assignLevelCheck6 = (($currentRoleCode == 'OPBRMG' || $currentRoleCode == 'SLBRMG') && ($operationRoleCode != 'OPCTMG' || $saleRoleCode != 'SLCTMG')) ? true : false ;

                                            $assignLevelCheck5 = ($operationRoleValue == 'ELHQ') ? true : false;
                                            ?>

                                            <?php if ($currentRoleCode != 'OPEXE' && $currentRoleCode != 'SLEXE') { ?>
                                                <input <?= ($assignLevelCheck) ? 'checked' : '' ?> type="radio" name="assigned_level" value="1" class="assigned_level" id="assigned_level_1" />
                                                City Level &nbsp;&nbsp;</label>
                                        <?php } ?>

                                        <label class="control-label" for="assigned_level_2">
                                            <input <?= ($assignLevelCheck2 || $assignLevelCheck3 || $assignLevelCheck4/* || $assignLevelCheck6 */) ? 'checked' : '' ?> type="radio" name="assigned_level" value="2" class="assigned_level" id="assigned_level_2" />
                                            Branch Level &nbsp;&nbsp;</label>

                                        <?php if (($currentRoleCode == 'OPCTMG' || $currentRoleCode == 'SLCTMG' || $currentRoleCode == 'OPSTMG' || $currentRoleCode == 'SLSTMG') || $currentRoleValue == 'ELHQ') { ?>
                                            <label class="control-label" for="assigned_level_3">
                                                <input <?= ($assignLevelCheck5) ? 'checked' : ''; ?> type="radio" name="assigned_level" value="3" class="assigned_level" id="assigned_level_3" />
                                                ELHQ Level &nbsp;&nbsp;</label>
                                        <?php } ?>

                                        <br /><br />

                                        <div class="form-group field-leadsowner-ref_status has-success">
                                            <label class="control-label" for="leadsowner-ref_status">City</label>
                                            <select class="form-control" name="assigned_city" id="assigned_city">
                                                <option value="">Select City</option>
                                                <?php
                                                $userType = Yii::$app->user->identity->user_type;
                                                $userId = Yii::$app->user->identity->id;
                                                if ($userType == 7) {
                                                    $opsModel = \app\models\OperationsProfile::findOne(['operations_id' => $userId]);
                                                    if ($opsModel->role_code == 'OPEXE' && $opsModel->role_value == 'ELHQ') {
                                                        $cities = \app\models\Cities::find()->where(['status' => 1])->all();
                                                    } else if ($opsModel->role_code == 'OPSTMG') {
                                                        $cities = \app\models\Cities::find()->where(['status' => 1, 'state_code' => $opsModel->role_value])->all();
                                                    } else if ($opsModel->role_code == 'OPCTMG') {
                                                        $cities = \app\models\Cities::find()->where(['status' => 1, 'code' => $opsModel->role_value])->all();
                                                    } else if ($opsModel->role_code == 'OPBRMG') {
                                                        $branchModel = \app\models\Branches::findOne(['branch_code' => $opsModel->role_value]);
                                                        $cities = \app\models\Cities::find()->where(['status' => 1, 'code' => $branchModel->city_code])->all();
                                                    } else if ($opsModel->role_code == 'OPEXE') {
                                                        $branchModel = \app\models\Branches::findOne(['branch_code' => $opsModel->role_value]);
                                                        $cities = \app\models\Cities::find()->where(['status' => 1, 'code' => $branchModel->city_code])->all();
                                                    }
                                                } else if ($userType == 6) {
                                                    $saleModel = \app\models\SalesProfile::findOne(['sale_id' => $userId]);
                                                    if ($saleModel->role_code == 'SLEXE' && $saleModel->role_value == 'ELHQ') {
                                                        $cities = \app\models\Cities::find()->where(['status' => 1])->all();
                                                    } else if ($saleModel->role_code == 'SLSTMG') {
                                                        $cities = \app\models\Cities::find()->where(['status' => 1, 'state_code' => $saleModel->role_value])->all();
                                                    } else if ($saleModel->role_code == 'SLCTMG') {
                                                        $cities = \app\models\Cities::find()->where(['status' => 1, 'code' => $saleModel->role_value])->all();
                                                    } else if ($saleModel->role_code == 'SLBRMG') {
                                                        $branchModel = \app\models\Branches::findOne(['branch_code' => $saleModel->role_value]);
                                                        $cities = \app\models\Cities::find()->where(['status' => 1, 'code' => $branchModel->city_code])->all();
                                                    } else if ($saleModel->role_code == 'SLEXE') {
                                                        $branchModel = \app\models\Branches::findOne(['branch_code' => $saleModel->role_value]);
                                                        $cities = \app\models\Cities::find()->where(['status' => 1, 'code' => $branchModel->city_code])->all();
                                                    }
                                                }

                                                foreach ($cities as $city) {
                                                    ?>

                                                    <option <?= ($model->city == $city->code) ? 'selected' : '' ?> value="<?= $city->code; ?>"><?= ($city->city_name); ?></option>

                                                <?php }
                                                ?>

                                            </select>
                                            <div class="help-block"></div>
                                        </div>

                                        <div class="form-group field-leadsowner-ref_status has-success">
                                            <label class="control-label" for="leadsowner-ref_status">Branch</label>
                                            <select class="form-control" name="assigned_branch" id="assigned_branch">
                                                <option value="">Select Branch</option>
                                                <?php
                                                if ($userType == 7) {
                                                    $opsModel = \app\models\OperationsProfile::findOne(['operations_id' => $userId]);
                                                    if ($opsModel->role_code == 'OPEXE' && $opsModel->role_value == 'ELHQ') {
                                                        $branches = \app\models\Branches::find()->where('branch_code != :branch_code', ['branch_code' => 'NA'])->all();
                                                    } else if ($opsModel->role_code == 'OPSTMG') {
                                                        $branches = \app\models\Branches::find()->where('branch_code != :branch_code AND state_code = :state_code', ['branch_code' => 'NA', 'state_code' => $opsModel->role_value])->all();
                                                    } else if ($opsModel->role_code == 'OPCTMG') {
                                                        $branches = \app\models\Branches::find()->where('branch_code != :branch_code AND city_code = :city_code', ['branch_code' => 'NA', 'city_code' => $opsModel->role_value])->all();
                                                    } else if ($opsModel->role_code == 'OPBRMG') {
                                                        $branches = \app\models\Branches::find()->where('branch_code = :branch_code', ['branch_code' => $opsModel->role_value])->all();
                                                    } else if ($opsModel->role_code == 'OPEXE') {
                                                        $branches = \app\models\Branches::find()->where('branch_code = :branch_code', ['branch_code' => $opsModel->role_value])->all();
                                                    }
                                                } else if ($userType == 6) {
                                                    $saleModel = \app\models\SalesProfile::findOne(['sale_id' => $userId]);
                                                    if ($saleModel->role_code == 'SLEXE' && $saleModel->role_value == 'ELHQ') {
                                                        $branches = \app\models\Branches::find()->where('branch_code != :branch_code', ['branch_code' => 'NA'])->all();
                                                    } else if ($saleModel->role_code == 'SLSTMG') {
                                                        $branches = \app\models\Branches::find()->where('branch_code != :branch_code AND state_code = :state_code', ['branch_code' => 'NA', 'state_code' => $saleModel->role_value])->all();
                                                    } else if ($saleModel->role_code == 'SLCTMG') {
                                                        $branches = \app\models\Branches::find()->where('branch_code != :branch_code AND city_code = :city_code', ['branch_code' => 'NA', 'city_code' => $saleModel->role_value])->all();
                                                    } else if ($saleModel->role_code == 'SLBRMG') {
                                                        $branches = \app\models\Branches::find()->where('branch_code = :branch_code', ['branch_code' => $saleModel->role_value])->all();
                                                    } else if ($saleModel->role_code == 'SLEXE') {
                                                        $branches = \app\models\Branches::find()->where('branch_code = :branch_code', ['branch_code' => $saleModel->role_value])->all();
                                                    }
                                                }

                                                foreach ($branches as $branch) {
                                                    ?>

                                                    <option <?= ($model->branch_code == $branch->branch_code) ? 'selected' : '' ?> value="<?= $branch->branch_code; ?>"><?= $branch->name . " [" . $branch->branch_code . "]"; ?></option>

                                                <?php }
                                                ?>
                                            </select>
                                            <div class="help-block"></div>
                                        </div>

                                        <div class="form-group field-leadsowner-ref_status has-success">
                                            <label class="control-label" for="leadsowner-ref_status">Operation's Member</label>
                                            <select class="form-control" name="assigned_operation" id="assigned_operation">
                                                <option value="">Select Operation</option>
                                                <?php if (!empty($propertyModel->operations_id)) { ?>
                                                    <option selected="selected" value="<?= $propertyModel->operations_id; ?>"><?= (Yii::$app->userdata->getEmailById($propertyModel->operations_id)) . " [" . Yii::$app->userdata->getRoleCodeByUserIdOps($propertyModel->operations_id) . "] [" . Yii::$app->userdata->getRoleValueByUserIdOps($propertyModel->operations_id) . "]"; ?></option>
                                                <?php } ?>
                                                <?php
                                                if (!empty($model->branch_code)) {
                                                    $opUsers = app\models\OperationsProfile::find()->where(' role_value = "' . $model->branch_code . '" ')->all();
                                                    foreach ($opUsers as $op) {
                                                        ?>
                                                        <option value="<?= $op->operations_id; ?>"><?= ($op->email) . " [" . $op->role_code . "] [" . $op->role_value . "]"; ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <div class="help-block"></div>
                                        </div>

                                        <div class="form-group field-leadsowner-ref_status has-success">
                                            <label class="control-label" for="leadsowner-ref_status">Sale's Member</label>
                                            <select class="form-control" name="assigned_sale" id="assigned_sale">
                                                <option value="">Select Sale</option>
                                                <?php if (!empty($propertyModel->sales_id)) { ?>
                                                    <option selected="selected" value="<?= $propertyModel->sales_id; ?>"><?= (Yii::$app->userdata->getEmailById($propertyModel->sales_id)) . " [" . Yii::$app->userdata->getRoleCodeByUserIdSales($propertyModel->sales_id) . "] [" . Yii::$app->userdata->getRoleValueByUserIdSales($propertyModel->sales_id) . "]"; ?></option>
                                                <?php } ?>
                                                <?php
                                                if (!empty($model->branch_code)) {
                                                    $slUsers = app\models\SalesProfile::find()->where(' role_value = "' . $model->branch_code . '" ')->all();
                                                    foreach ($slUsers as $sl) {
                                                        ?>
                                                        <option value="<?= $sl->sale_id; ?>"><?= ($sl->email) . " [" . $sl->role_code . "] [" . $sl->role_value . "]"; ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <div class="help-block"></div>
                                        </div>

                                        <br />

                                        <div class="row">
                                            <div class="col-lg-6 col-sm-6">
                                                <?php echo Html::submitButton('Next', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn savechanges_submit_contractaction', 'id' => 'owner-lead-assign-btn']) ?>
                                            </div>
                                            <div class="col-lg-6 col-sm-6">
                                                <a style="color:#FFF" class="btn savechanges_submit cancel_confirm_2" href="<?= Url::home(true); ?>sales/applicants" type="button">Cancel</a>
                                            </div>
                                        </div>

                                        <?php ActiveForm::end(); ?>

                                    </div>



                                    <!--------------------------------------->
                                    <div class="modal fade" id="myModal_edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                        <div class="modal-dialog modalbg modalbgpopup" role="document">
                                            <div class="modal-content">
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>

        </div> 
    </div>  

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAUlWJke1V2SAp-RnQd4EK7O2J_G9IzgYw&libraries=places&callback=initAutocomplete" async defer></script>


    <script>
                                        var click_enable = 1;
                                        var map;
                                        var geocoder;
                                        var marker;
                                        var gmarker;
                                        var people = new Array();
                                        var latlng;
                                        var infowindow;

                                        function initAutocomplete() {
                                            var options = {
                                                //types: ['(cities + states + countries)'],
                                                //types: ['geocode'], 

                                                componentRestrictions: {country: "IN"}
                                            };
                                            var input = document.getElementById('location');
                                            var searchBox = new google.maps.places.SearchBox(input, options);
                                            var markers = [];

                                            searchBox.addListener('places_changed', function () {
                                                var places = searchBox.getPlaces();

                                                if (places.length == 0) {
                                                    return;
                                                }

                                                markers.forEach(function (marker) {
                                                    marker.setMap(null);
                                                });
                                                places.forEach(function (place) {


                                                });


                                            });
                                            autocomplete = new google.maps.places.Autocomplete(input, options);

                                            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                                                var place = autocomplete.getPlace();
                                                $('#properties-lat').val(place.geometry.location.lat());
                                                $('#properties-lon').val(place.geometry.location.lng());
                                                return false;
                                            });
                                        }

                                        function goTop() {
                                            var body = $("html, body");
                                            body.animate({scrollTop: 0}, 500, 'swing', function () {
                                            });
                                        }
                                        function nextagreement()
                                        {
                                            $('#agreement1').val("1");
                                        }
    </script>
    <script>
        $(document).ready(function () {
            $(".select-change-onload").change(function () {
                $(this).find("option:selected").each(function () {
                    var optionValue = $(this).attr("value");
                    if (optionValue) {
                        $(".box1").not("." + optionValue).hide();
                        $("." + optionValue).show();
                    } else {
                        $(".box1").hide();
                    }
                });
            }).change();
        });
    </script>
    <script>
        $(document).ready(function () {
            $(".select-change-onload").change(function () {
                $(this).find("option:selected").each(function () {
                    var optionValue = $(this).attr("value");
                    if (optionValue) {
                        $(".box_gua").not("." + optionValue).hide();
                        $("." + optionValue).show();
                    } else {
                        $(".box_gua").hide();
                    }
                });
            }).change();
        });
    </script>
    <script>
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        function apply_image(str) {
            var length = $('.imageList input[type="file"]').length;
            if (length != 0) {
                $('.imageList input[type="file"]:last-child').attr('data-id');
                length = parseInt(length) + 1;
            }
            $('.imageList').append('<input type="file" name="PropertyImages[\'image_url\'][' + length + ']" id="imageProp' + length + '" data-id="' + length + '">');
            $('.imageList input[type="file"]#imageProp' + length).click();
            return false;
        }

        $(document).ready(function () {

            var assignedLevel = $('input[name=assigned_level]:checked').val();

            $('#assigned_city').removeAttr('disabled');
            $('#assigned_branch').removeAttr('disabled');
            $('#assigned_operation').removeAttr('disabled');
            $('#assigned_sale').removeAttr('disabled');

            if (assignedLevel == 1) {
                $('#assigned_city').removeProp('disabled');
                $('#assigned_branch').prop('disabled', 'disabled');
                $('#assigned_operation').removeProp('disabled');
                $('#assigned_sale').removeProp('disabled');
            }

            if (assignedLevel == 2) {
                $('#assigned_branch').removeProp('disabled');
                $('#assigned_city').removeProp('disabled');
                $('#assigned_operation').removeProp('disabled');
                $('#assigned_sale').removeProp('disabled');
            }

            if (assignedLevel == 3) {
                $('#assigned_branch').prop('disabled', 'disabled');
                $('#assigned_city').prop('disabled', 'disabled');
                $('#assigned_operation').prop('disabled', 'disabled');
                $('#assigned_sale').prop('disabled', 'disabled');
            }

            $(".select-change-onload").change(function () {
                $(this).find("option:selected").each(function () {
                    var optionValue = $(this).attr("value");
                    if (optionValue) {
                        $(".box_rooms").not("." + optionValue).hide();
                        $("." + optionValue).show();
                    } else {
                        $(".box_rooms").hide();
                    }
                });
            }).change();
        });

        $(document).ready(function () {

            $('.check_click').change(function () {
                var allVals = [];
                $("input:checkbox:checked").each(function () {
                    if ($(this).is(":checked")) {
                        if (!isNaN($(this).val())) {
                            allVals.push($(this).val());
                        }
                    }
                });

                $('#attributes').val(allVals);
            });
        });


        $(document).on('change', '.imageList input[type="file"]', function (e) {
            startLoader();
            var fd = new FormData();
            var csrfToken = $('meta[name="csrf-token"]').attr("content");
            fd.append("PropertyImages[image_url]", $(this)[0].files[0]);
            fd.append("property_id", $('#propertyagreements-property_id').val());
            fd.append('_csrf', csrfToken);
            var thiselement = this;
            $.ajax({
                url: "saveimage",
                type: "POST",
                data: fd,
                processData: false,
                contentType: false,
                success: function (data) {
                    readURL(thiselement, data);
                    hideLoader();
                },
                error: function (data) {
                    hideLoader();
                    // alert(data.responseText);
                    alert('Failure')

                }

            });
        })

        function readURL(input, data) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                $(input).attr('data-id', data);
                reader.onload = function (e) {
                    var length = $('#dataimages tbody tr').length;
                    if (length == 0) {
                        $('#dataimages tbody').append('<tr><td><img src="' + e.target.result + '" alt="Image" class="mCS_img_loaded" width="100%"></td><td><div class="radio"><label><input value="' + data + '" name="optradio" class="" type="radio" checked="true"></label></div></td><td><span><i data-id="' + data + '" class="fa fa-trash fa-3x deleteimg" aria-hidden="true" checked="true"></i></span></td></tr>');

                    } else {
                        $('#dataimages tbody').append('<tr><td><img src="' + e.target.result + '" alt="Image" class="mCS_img_loaded" width="100%"></td><td><div class="radio"><label><input value="' + data + '" name="optradio" class="" type="radio"></label></div></td><td><span><i data-id="' + data + '" class="fa fa-trash fa-3x deleteimg" aria-hidden="true"></i></span></td></tr>');
                    }


                }

                reader.readAsDataURL(input.files[0]);
            }
        }


        $(document).on('click', '.deleteimg', function (e) {

            e.preventDefault();
            var csrfToken = $('meta[name="csrf-token"]').attr("content");
            var id = $(this).attr('data-id');
            var thiselement = $(this);
            var r = confirm("Are You Sure You Want To Delete Image!");
            if (r == true) {
                startLoader();
                $.ajax({
                    url: 'deleteimage',
                    type: 'POST',
                    data: {id: $(this).data('id'), _csrf: csrfToken},
                    success: function (data) {
                        thiselement.closest('span').closest('td').closest('tr').remove();
                        // $('#imageProp'+id).remove();
                        //$('#dataimages').load('<?= Url::home(true) ?>sales/editproperty?id=<?= $model->id; ?> #dataimages');
                        hideLoader();

                    },
                    error: function (data) {
                        hideLoader();
                        alert('Failure');
                    }
                });

            }

        })






        $(document).on('click', '.click_window', function () {
            var click = $(this).attr('data-click');
            $('a[href="' + click + '"]').click();
        });

        $(document).on('change', '#propertyagreements-agreement_type', function () {
            console.log($(this).val());
            if ($(this).val() != '2') {
                $('.isDisabled').closest('false').hide();
                $('.isDisabled').val('0');
            } else {
                $('.isDisabled').closest('false').show();
                if ($('#propertyagreements-rent_start_date').val() == '0') {
                    $('.isDisabled').val('');
                }
            }
        });

        $(document).ready(function () {
            if ($('#propertyagreements-agreement_type').val() != 2) {
                $('.isDisabled').closest('false').hide();
                $('.isDisabled').val('0');
            } else {
                $('.isDisabled').closest('false').show();
            }
        })

        $(document).on('change', '#properties-property_type', function () {
            var val = $(this).val();
            $('#propertyagreements-agreement_type option').removeAttr('disabled');
            if (val == 1 || val == 2)
            {
                $('#propertyagreements-agreement_type option').prop('disabled', true);
                $('#propertyagreements-agreement_type option[value="3"]').removeAttr('disabled');
                $('#propertyagreements-agreement_type').val('3');

            } else {
                $('#propertyagreements-agreement_type option[value="3"]').prop('disabled', true);
                var agreement_type = $('#propertyagreements-agreement_type').val();
                if (agreement_type == 3) {
                    $('#propertyagreements-agreement_type').val('1');
                }

            }
        });

        $(document).ready(function () {
            var val = $('#properties-property_type').val();
            $('#propertyagreements-agreement_type option').removeAttr('disabled');
            if (val == 1 || val == 2)
            {
                $('#propertyagreements-agreement_type option').prop('disabled', true);
                $('#propertyagreements-agreement_type option[value="3"]').removeAttr('disabled');
                $('#propertyagreements-agreement_type').val('3');

            } else {
                $('#propertyagreements-agreement_type option[value="3"]').prop('disabled', true);
                var agreement_type = $('#propertyagreements-agreement_type').val();
                if (agreement_type == 3) {
                    $('#propertyagreements-agreement_type').val('1');
                }
            }
        });

        // $(document).on('click','.confirm_close',function(){
        // var r=confirm("There is unsaved data on the page which will not be saved if you continue. Do you want to navigate to property owners lead page. Yes / No ");
        // if(r==true){
        // window.location.href='ownerssdetails?id=<?php echo base64_encode($lead_id) ?>';
        // }
        // })

        /*$(document).on('click','.verify_close1',function(){
         
         var id=$('#propertyagreements-property_id').val();
         if(id.trim()==''){
         id=0;
         }
         console.log(id);
         /*var formdata=new FormData($('#ownerform')[0]);
         $.ajax({
         url: 'verifyPropertyDetail?id=',
         type: 'POST',
         data: formdata,
         success: function(response){
         
         }
         })
         })
         
         $(document).on('click','.verify_close2',function(){
         
         })
         
         $(document).on('click','.verify_close3',function(){
         
         })
         
         $(document).on('click','.verify_close4',function(){
         
         })
         
         */

        /* $(document).on('click', '.confirm_close2', function () {
         if ($('#redirection').val() == '1') {
         
         window.location.href = 'properties';
         } else {
         window.location.href = 'ownerssdetails?id=<?php echo base64_encode($lead_id) ?>';
         }
         })
         $(document).on('click', '.confirm_close1', function () {
         window.location.href = 'ownerssdetails?id=<?php echo base64_encode($lead_id) ?>';
         })*/
        $(document).ready(function () {
            $('.savechanges_submit_previous1').click(function () {
                var attr = $(this).attr('data-click');
                $('[href="' + attr + '"]').click();
            })
        })


        $(document).ready(function () {
            $('.isDisabledd').each(function () {
                $(this).attr('disabled', true)
            })
        })
        // $(document).on('click','.data_submit',function(){
        // 	var class_id=$(this).attr('data-submit');
        // 	if(class_id=='p_image'){

        // 	}
        // 	else{
        // 		if(click_enable=='1'){
        // 			$('#'+class_id).find('form').find('button[type="submit"]').click();
        // 			click_enable=0;
        // 		}


        // 	}

        // });
        // function myFunction() {
        // location.reload();
        // }
        $(document).on("click", ".cancel_confirm_all", function (e) {

            var element = $(this);
            var confirm_var = confirm('Are you sure you want to cancel ?');
            if (confirm_var == true) {
                window.history.back();
            } else {
                e.preventDefault();
            }
        });
    </script>

    <style type="text/css">
        .thumbnails li {
            height: 195px;
        }
        .savechanges_submit_previous.activate_property{
            margin-left:6px;
        }
    </style>

    <script>
        $(function () {
            $('#property_lead_assignment').submit(function (evt) {
                evt.preventDefault();
                var assignedLevel = $('input[name=assigned_level]:checked').val();
                var assignedOperation = $('#assigned_operation').val();
                var assignedSale = $('#assigned_sale').val();
                var assignedBranch = $('#assigned_branch').val();
                var assignedCity = $('#assigned_city').val();

                if (assignedLevel == '' || assignedLevel == null || assignedLevel == undefined) {
                    alert('Please select "Level"');
                    return false;
                }

                if (assignedLevel == 1) {
                    if (assignedCity == '' || assignedCity == null || assignedCity == undefined) {
                        alert('Please select "City"');
                        return false;
                    }

                    if (assignedOperation == '' || assignedOperation == null || assignedOperation == undefined) {
                        alert('Please select "Operation member"');
                        return false;
                    }

                    if (assignedSale == '' || assignedSale == null || assignedSale == undefined) {
                        alert('Please select "Sale member"');
                        return false;
                    }
                } else if (assignedLevel == 2) {
                    if (assignedCity == '' || assignedCity == null || assignedCity == undefined) {
                        alert('Please select "City"');
                        return false;
                    }

                    if (assignedBranch == '' || assignedBranch == null || assignedBranch == undefined) {
                        alert('Please select "Branch"');
                        return false;
                    }

                    if (assignedOperation == '' || assignedOperation == null || assignedOperation == undefined) {
                        alert('Please select "Operation member"');
                        return false;
                    }

                    if (assignedSale == '' || assignedSale == null || assignedSale == undefined) {
                        alert('Please select "Sale member"');
                        return false;
                    }
                }
                //else if (assignedLevel == 3) {
                //return true;
                //}

                var formData = $('#property_lead_assignment').serialize();
                $.ajax({
                    type: "POST",
                    url: 'addleadassignmentproperty',
                    data: formData, //only input
                    dataType: 'json',
                    timeout: 15000,
                    success: function (response) {
                        if (parseInt(response) == 1) {
                            $('#agreement-tab').click();
                        } else {
                            alert('Unable to save, something seems wrong!');
                        }
                    },
                    error: function (x, textStatus, m) {
                        alert(textStatus + ', something seems wrong!');
                    }
                });
            });

            $('#assigned_city').change(function () {
                var assignedCity = $(this).val();
                var assignedLevel = $('input[name=assigned_level]:checked').val();
                var csrfToken = $('meta[name="csrf-token"]').attr("content");
                var url = null;
                if (assignedLevel == 1) {
                    $.ajax({
                        url: 'getopsbycitycode',
                        type: 'POST',
                        data: {city_code: assignedCity, _csrf: csrfToken},
                        success: function (data) {
                            $('#assigned_operation').html(data);
                        },
                        error: function (data) {
                            alert('Something went wrong.');
                        }
                    });

                    $.ajax({
                        url: 'getsalebycitycode',
                        type: 'POST',
                        data: {city_code: assignedCity, _csrf: csrfToken},
                        success: function (data) {
                            $('#assigned_sale').html(data);
                        },
                        error: function (data) {
                            alert('Something went wrong.');
                        }
                    });
                } else if (assignedLevel == 2) {
                    $.ajax({
                        url: 'getbranchbycitycode',
                        type: 'POST',
                        data: {city_code: assignedCity, _csrf: csrfToken},
                        success: function (data) {
                            $('#assigned_branch').html(data);
                        },
                        error: function (data) {
                            alert('Something went wrong.');
                        }
                    });
                } else {
                    return;
                }
            });

            $('#assigned_branch').change(function () {
                var assignedBranch = $(this).val();
                var assignedLevel = $('input[name=assigned_level]:checked').val();
                var assignedCity = $('#assigned_city').val();
                var csrfToken = $('meta[name="csrf-token"]').attr("content");
                $.ajax({
                    url: 'getopsbybranchcode',
                    type: 'POST',
                    data: {city_code: assignedCity, branch: assignedBranch, _csrf: csrfToken},
                    success: function (data) {
                        $('#assigned_operation').html(data);
                    },
                    error: function (data) {
                        alert('Something went wrong.');
                    }
                });

                $.ajax({
                    url: 'getsalesbybranchcode',
                    type: 'POST',
                    data: {city_code: assignedCity, branch: assignedBranch, _csrf: csrfToken},
                    success: function (data) {
                        $('#assigned_sale').html(data);
                    },
                    error: function (data) {
                        alert('Something went wrong.');
                    }
                });
            });

            $('.assigned_level').click(function () {
                var assignedLevel = $('input[name=assigned_level]:checked').val();

                $('#assigned_city').removeAttr('disabled');
                $('#assigned_branch').removeAttr('disabled');
                $('#assigned_operation').removeAttr('disabled');
                $('#assigned_sale').removeAttr('disabled');

                if (assignedLevel == 1) {
                    $('#assigned_city').removeProp('disabled');
                    $('#assigned_branch').prop('disabled', 'disabled');
                    $('#assigned_operation').removeProp('disabled');
                    $('#assigned_sale').removeProp('disabled');

                    $('#assigned_city').val('');
                    $('#assigned_branch').html('');
                    $('#assigned_operation').html('');
                    $('#assigned_sale').html('');
                }

                if (assignedLevel == 2) {
                    $('#assigned_branch').removeProp('disabled');
                    $('#assigned_city').removeProp('disabled');
                    $('#assigned_operation').removeProp('disabled');
                    $('#assigned_sale').removeProp('disabled');

                    $('#assigned_city').val('');
                    $('#assigned_branch').html('');
                    $('#assigned_operation').html('');
                    $('#assigned_sale').html('');
                }

                if (assignedLevel == 3) {
                    $('#assigned_branch').prop('disabled', 'disabled');
                    $('#assigned_city').prop('disabled', 'disabled');
                    $('#assigned_operation').prop('disabled', 'disabled');
                    $('#assigned_sale').prop('disabled', 'disabled');

                    $('#assigned_city').html('');
                    $('#assigned_branch').html('');
                    $('#assigned_operation').html('');
                    $('#assigned_sale').html('');
                }
            });

            $("#tags").autocomplete({
                source: "getsalesname",
                select: function (event, ui) {
                    event.preventDefault();
                    //	console.log(ui.item);
                    $("#sales_name").val(ui.item.label);
                    $("#ownerprofile-sales_id").val(ui.item.value);
                    $("#tags").val(ui.item.label);
                },
            });

<?php if (!empty($_GET['t'])) { ?>
                $('#agreement-tab').click();
<?php } ?>
        });

    </script>