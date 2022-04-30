 
<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\ArrayHelper;
?>
<style>
    .advisorinput1 label {
        float: left;
    }
    .datepicker {
        z-index: 100000;
    }
</style>


<script>

    $(function () {
        $(".datepicker").datepicker({
            minDate: new Date(),
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            dateFormat: 'yy-mm-dd',

        });
    });


</script>







<div id="page-wrapper">
    <div class="row">
        <div class="">
            <ul class="nav nav-pills">
                <li class="active"><a data-toggle="pill" href="#p_info">Property Information</a></li>
                <li><a data-toggle="pill" href="#p_features">Property & Complex Amenities</a></li>
                <li><a data-toggle="pill" href="#p_image">Property Images</a></li>
                <li><a data-toggle="pill" href="#p_contract">Property Agreement</a></li>


            </ul>

            <div class="tab-content">
                <div id="p_info" class="tab-pane fade in active">
                    <div class= "row">
                        <div class= "col-md-12"><h3>Property information</h3> </div> 

                    </div>
                    <div class="col-md-10">
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

                            <?= $form->field($model, 'property_name')->textInput(['maxlength' => true, 'placeholder' => 'Property Name*']) ?>

                            <?= $form->field($model, 'property_description')->textarea(['rows' => '5', 'placeholder' => 'Property Description*']) ?>


                            <div class="form-group">
                                <label for="exampleInputPassword1">Property Type</label>
                                <?php
                                $states = \app\models\PropertyTypes::find()->all();
                                $stateData = ArrayHelper::map($states, 'id', 'property_type_name');
                                ?>
                                <?= $form->field($model, 'property_type')->dropDownList($stateData, ['prompt' => 'Select Property Type'])->label(false); ?>

                            </div>
                            <?php
                            /* echo "<pre>";
                              print_r($model); */
                            ?>
                            <div id="colive" style="display:none;">

                                <?= $form->field($model, 'is_room')->dropDownList([0 => 'Beds with rooms', 1 => 'Rooms'], ['prompt' => 'Is Room']) ?>

                            </div>
                            <div id="no_of_beds" style="display:none;">
                                <?php
                                $roomsOne = \app\models\ChildProperties::find()->where(['main' => $model->id, 'type' => 1])->all();
                                $roomsCountOne = count($roomsOne);
                                ?>
                                <input type="hidden" id="beds_room" value="<?= $roomsCountOne; ?>">
                                <?= $form->field($model, 'beds_room')->textInput(['value' => $roomsCountOne, 'class' => 'form-control isNumber']) ?>
                                <div id="newFields">

                                    <?php
                                    foreach ($roomsOne as $key => $data) {
                                        $roomsChild = \app\models\ChildProperties::find()->where(['parent' => $data->id, 'type' => 2])->all();
                                        $roomsChildOne = count($roomsChild);
                                        echo '<label> Rooms ' . ($key + 1) . ' </label> <input type="text" placeholder="Beds*" value="' . $roomsChildOne . '" name="Properties[beds][]" class="form-control isNumber">';
                                    }
                                    ?>

                                </div>
                            </div>
                            <div id="no_of_rooms" style="display:none;">
                                <?php
                                $rooms = \app\models\ChildProperties::find()->where(['main' => $model->id, 'type' => 1])->all();
                                $roomsCount = count($rooms);
                                ?>	
                                <?= $form->field($model, 'rooms')->textInput(['value' => $roomsCount, 'class' => 'form-control isNumber']) ?>
                            </div>

                            <div id="flat" style="display:none;">
                                <?= $form->field($model, 'flat_bhk')->textInput(['class' => 'form-control isNumber']) ?>

                                <?= $form->field($model, 'flat_area')->textInput(['class' => 'form-control isNumber']) ?>

                            </div>  


                            <?= $form->field($model, 'address_line_1')->textInput(['maxlength' => true, 'id' => 'searchTextField', 'placeholder' => 'Address line 1*']) ?>

                            <?= $form->field($model, 'address_line_2')->textInput(['maxlength' => true, 'placeholder' => 'Address line 2*']) ?>


                            <?php
                            $states = \app\models\States::find()->all();
                            $stateData = ArrayHelper::map($states, 'id', 'name');
                            ?>
                            <?= $form->field($model, 'state')->dropDownList($stateData, ['prompt' => 'Select state', 'url' => \Yii::$app->getUrlManager()->createUrl('users/getcity')]); ?>

                            <?php
                            $cities = \app\models\Cities::find()->where(['state_id' => $model->state])->all();
                            $cityData = ArrayHelper::map($cities, 'id', 'city_name');
                            // echo $model
                            ?>
                            <?= $form->field($model, 'city')->dropDownList($cityData, ['prompt' => 'Select city', 'url' => \Yii::$app->getUrlManager()->createUrl('advisers/getregions'), $model->city => array('selected' => true)]); ?>

                            <?php
                            $regions = \app\models\Regions::find()->where(['city_id' => $model->city])->all();
                            $cityData = ArrayHelper::map($regions, 'id', 'name');
                            ?>
                            <?= $form->field($model, 'region')->dropDownList($cityData, ['prompt' => 'Select Branch'])->label('Branch'); ?>

                            <?= $form->field($model, 'lat')->textInput(['maxlength' => true, 'placeholder' => 'Latitude*', 'class' => 'form-control isLocation'])->label('Latitude'); ?>
                            <?= $form->field($model, 'lon')->textInput(['maxlength' => true, 'placeholder' => 'Longitude*', 'class' => 'form-control isLocation'])->label('Longitude'); ?>
                            <?= $form->field($model, 'pincode')->textInput(['maxlength' => true, 'placeholder' => 'Pincode*', 'class' => 'form-control isNumber']); ?>
                <!-- <?//= $form->field($model, 'status')->dropDownList([0 => 'InActive property',1 =>'Activate Property'],['prompt'=>'Select status']); ?> -->
                            <?= $form->field($model, 'flat_type')->dropDownList([0 => 'Semi-furnished', 1 => 'Furnishsed'], ['prompt' => 'Select Type']); ?>
                            <script>
                                $(function () {
                                    var input = $('<input type="text" placeholder="Beds*" name="Properties[beds][]" value="" class="form-control isNumber">');
                                    var newFields = $('');
                                    var newlabel = $('');

                                    $('#properties-beds_room').bind('keyup change', function () {

                                        var n = this.value || 0;
                                        if (n + 1) {
                                            if (n > newFields.length) {
                                                var totalRooms = $('input[name="Properties[beds][]"').length;
                                                if (totalRooms > n) {
                                                    removeFields(totalRooms - n);
                                                } else {
                                                    addFields(n - totalRooms, totalRooms);
                                                }

                                            } else {
                                                removeFields(n);
                                            }
                                        }
                                    });

                                    function addFields(n, totalRooms) {

                                        for (i = 0; i < n; i++) {
                                            var newInput = input.clone();
                                            var label = $('<label> Rooms ' + (totalRooms + i + 1) + ' </label>').clone();

                                            newFields = newFields.add(newInput);
                                            newlabel = newlabel.add(label);
                                            label.appendTo('#newFields');
                                            newInput.appendTo('#newFields');
                                        }
                                    }

                                    function removeFields(n) {
                                        var removeField = newFields.slice(n).remove();
                                        var removelabel = newlabel.slice(n).remove();
                                        newFields = newFields.not(removeField);
                                        newlabel = newlabel.not(removelabel);
                                    }
                                });

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
                            </script>

                            <div style="padding-top:20px;" class="row pull-right col-lg-8">
                                <div class="col-lg-6">
                                    <?= Html::submitButton('Next', ['class' => 'btn btn savechanges_submit_contractaction', 'id' => 'form_property_listing', 'data-click' => "#p_features"]) ?>
                                </div>
                                <div class="col-lg-6">
                                    <button type="button" class="btn savechanges_submit cancel_confirm" data-dismiss="modal">cancel</button>

                                </div>
                                &nbsp; 
                            </div>

                            <?php ActiveForm::end(); ?>

                        </div>     

                    </div>
                </div>

                <div id="p_features" class="tab-pane fade">
                    <div class= "row">
                        <div class= "col-md-12">
                            <h3>Property & Complex Amenities</h3>
                        </div>

                    </div>
                    <br>
                    <div class="col-md-10">
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
                                            <li class="col-md-2 thumbnailslide">
                                                <div class="fff">
                                                    <div class="thumbnail">
                                                        <p>
                                                            <img src="<?= $propertyAttrFac->icon; ?>" alt="Icon">
                                                            <span><?= $propertyAttrFac->name; ?></span>
                                                        </p>
                                                        <label class="checkbox-inline">
                                                            <input class="check_click"  <?= in_array($propertyAttrFac->id, $dataProper) ? 'checked' : '' ?>  type="checkbox" value="<?= $propertyAttrFac->id; ?>" data-id="text<?= $propertyAttrFac->id ?>">

                                                        </label>
                                                    </div>
                                                    <div>
                                                        <input type="text"  data-name="<?= $propertyAttrFac->id ?>" id="text<?= $propertyAttrFac->id ?>" value="<?= in_array($propertyAttrFac->id, $dataProper) ? Yii::$app->userdata->getFacilityValue($propertyAttrFac->id, $model->id) : '' ?>"  <?= in_array($propertyAttrFac->id, $dataProper) ? '' : 'style="display: none"' ?> class="attrText form-control isNumber">
                                                    </div>

                                                </div>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>


                            </div>

                            <h4>Complex Features</h4>
                            <div class="row">
                                <div class="item active">
                                    <ul class="thumbnails thumbnailslide_new">
                                        <?php foreach ($propertyAttrComplex as $keyCom => $propertyAttrCom) { ?>
                                            <li class="col-md-2 thumbnailslide">
                                                <div class="fff">
                                                    <div class="thumbnail">
                                                        <p>
                                                            <img src="<?= $propertyAttrCom->icon; ?>" alt="Icon">
                                                            <span><?= $propertyAttrCom->name; ?></span>
                                                        </p>
                                                        <label class="checkbox-inline">
                                                            <input class="check_click" <?= in_array($propertyAttrCom->id, $dataProper) ? 'checked' : '' ?>   type="checkbox" value="<?= $propertyAttrCom->id; ?>" data-id="text<?= $propertyAttrCom->id ?>">

                                                        </label>
                                                    </div>
                                                    <div>
                                                            <!-- <input type="text"  data-name="<?//= $propertyAttrCom->id ?>" id="text<?//= $propertyAttrCom->id ?>" value="<?//= in_array($propertyAttrCom->id  ,$dataProper) ? Yii::$app->userdata->getFacilityValue($propertyAttrCom->id,$model->id):''  ?>"  <?//= in_array($propertyAttrCom->id  ,$dataProper) ? '':'style="display: none"'  ?> class="attrText form-control isNumber"> -->
                                                    </div>

                                                </div>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>



                            <div style="padding-top:20px;" class="row pull-right col-lg-8">
                                <div class="col-lg-4">
                                    <button type="button" class="btn savechanges_submit_previous previous_btn" data-click="#p_info">Previous</button>

                                </div>
                                <div class="col-lg-4">
                                    <form method="POST" id="formfaclities">

                                        <input type="hidden" id="attri_poperty_id" name="attri_poperty_id" value="<?= $model->id; ?>"> 
                                        <input type="hidden" id="attributes" name="attributes" value="<?= $dataIdsAttri; ?>"> 

                                        <?= Html::Button('Next', ['id' => 'save_features', 'class' => 'btn btn savechanges_submit_contractaction']) ?>
                                    </form>

                                </div>
                                <div class="col-lg-4">
                                    <button type="button" class="btn savechanges_submit cancel_confirm" data-dismiss="modal">cancel</button>

                                </div>
                                &nbsp; 
                            </div>

                        </div>
                    </div>
                </div>

                <div id="p_image" class="tab-pane fade">

                    <div class= "col-md-12">
                        <h3>Property Image</h3>
                    </div>

                    <br>
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
                    <table id="dataimages" class="table table-bordered">
                        <thead>
                            <tr>
                                <th style= "width: 40%">Image (Thumbnail)</th>
                                <th>Default?</th>
                                <th style= "width : 20%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($modelImages as $key => $images) { ?>
                                <tr>
                                    <td><img src="<?php echo ($images) ? $images->image_url : Url::home(true) . 'images/gallery_imgone.jpg'; ?>" alt="Image" class="mCS_img_loaded" width="100%"></td>
                                    <td><div class="radio">
                                            <label><input value="<?= $images->id; ?>" type="radio" name="optradio" class="" <?= $images->is_main == 1 ? 'checked' : ''; ?> ></label>
                                        </div></td>
                                    <td><span><i data-id="<?= $images->id; ?>"  class="fa fa-trash fa-3x deleteimg" aria-hidden="true"></i></span></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <div class="row" style= "width: 30%; padding: 10px;">
                        <form id="imageform" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="property_id" value="<?= $model->id; ?>" id="property_id" />
                            <input type= "file" name= "PropertyImages['image_url']" id= "add_image"  style="display: none;">
                            <button onclick="apply_image('add_image');
                                    return false;
                                    "  class= "btn savechanges_submit1">Add New Image</button>
                            <div class="imageList" style="display: none">

                            </div>
                        </form> 
                    </div>
                    <div style="padding-top:20px;" class="row pull-right col-lg-8">
                        <div class="col-lg-4">
                            <button type="button" class="btn savechanges_submit_previous previous_btn" data-click="#p_features">Previous</button>

                        </div>
                        <div class="col-lg-4">	      
                            <a data-toggle="pill"  class="btn savechanges_submit_contractaction click_window setAsDefault" data-click="#p_contract">Next</a>
                        </div>
                        <div class="col-lg-4">
                            <button type="button" class="btn savechanges_submit cancel_confirm" data-dismiss="modal">cancel</button>
                        </div>
                        <div class="col-lg-4">
                        </div>
                        &nbsp; 
                    </div>
                </div>



                <div id="p_contract" class="tab-pane fade">

                    <div class= "row">
                        <div class= "col-md-12">
                            <h3>Property Agreement</h3>
                        </div>
                    </div>
                    <br>
                    <div class="col-md-10">
                        <div class="parmentaddress parmentaddressnew">
                            <?php
                            $form = ActiveForm::begin(['id' => 'property_agreement', 'action' => 'addagreement?id=' . $model->id,
                                        'options' => [
                                            'enctype' => 'multipart/form-data',
                                            'class' => 'form-inline',
                                        ],
                                        'fieldConfig' => [
                                            'template' => "{label}{input}{error}",
                                            'options' => [
                                                'tag' => 'false'
                                            ]
                                        ],
                                        'enableAjaxValidation' => true]);
                            ?>	
                            <?= $form->field($modelPropertyAgreements, 'agreement_type')->dropDownList([1 => 'Normal Leasing', 2 => 'Guaranteed Leasing', 3 => 'Co-living'], ['prompt' => 'Select Agreement Type']); ?>
                            <?= $form->field($modelPropertyAgreements, 'property_id')->hiddenInput(['value' => $model->id, 'placeholder' => 'Rent*'])->label(false); ?>
                            <?= $form->field($modelPropertyAgreements, 'owner_id')->hiddenInput(['value' => $model->owner_id])->label(false); ?>
                            <?= $form->field($modelPropertyAgreements, 'rent')->textInput(['maxlength' => true, 'placeholder' => 'Rent*', 'class' => 'form-control isNumber isDisabled']) ?>
                            <?= $form->field($modelPropertyAgreements, 'manteinance')->textInput(['maxlength' => true, 'placeholder' => 'maintenance*', 'class' => 'form-control isNumber isDisabled'])->label('maintenance') ?>
                            <?= $form->field($modelPropertyAgreements, 'deposit')->textInput(['maxlength' => true, 'placeholder' => 'Deposit*', 'class' => 'form-control isNumber isDisabled']) ?>
                            <?= $form->field($modelPropertyAgreements, 'rent_start_date')->textInput(['maxlength' => true, 'placeholder' => 'Rent start date*', 'class' => 'datepicker isDisabled']) ?>
                            <?= $form->field($modelPropertyAgreements, 'notice_peroid')->textInput(['maxlength' => true, 'placeholder' => 'Notice peroid*']) ?>
                            <?= $form->field($modelPropertyAgreements, 'min_contract_peroid')->textInput(['maxlength' => true, 'placeholder' => 'Minnimum contract peroid*', 'class' => 'form-control isNumber']) ?>
                            <?= $form->field($modelPropertyAgreements, 'furniture_rent')->textInput(['maxlength' => true, 'placeholder' => 'Furniture*', 'class' => 'form-control isNumber1']) ?>
                            <?= $form->field($modelPropertyAgreements, 'pms_commission')->textInput(['maxlength' => true, 'placeholder' => 'PMS Fees*', 'class' => 'form-control isNumber1'])->label('PMS Fees') ?>
                            <?= $form->field($modelPropertyAgreements, 'contract_start_date')->textInput(['maxlength' => true, 'placeholder' => 'Contract start date*', 'class' => 'datepicker']) ?>
                            <?= $form->field($modelPropertyAgreements, 'contract_end_date')->textInput(['maxlength' => true, 'placeholder' => 'Contract end date*', 'class' => 'datepicker']) ?>

                            <?= $form->field($modelPropertyAgreements, 'agreement_url')->fileInput() ?> 

                            <div style="padding-top:20px;" class="row pull-right col-lg-12">
                                <div class="col-lg-4">
                                    <button type="button" class="btn savechanges_submit_previous activate_property"  style="<?= $model->status == 1 ? 'font-size:20px;padding:12.5px' : 'font-size:22px;padding:11px' ?>" data-status="<?= $model->status == 1 ? '1' : '0' ?>"><?= $model->status == 1 ? 'Deactivate Property' : 'Activate Property' ?></button>

                                </div>
                                <div class="col-lg-4">
                                    <?= Html::submitButton('Save', ['class' => 'btn btn savechanges_submit_contractaction']) ?>
                                </div>
                                <div class="col-lg-4">
                                    <button type="button" class="btn savechanges_submit cancel_confirm" data-dismiss="modal">cancel</button>

                                </div>
                                &nbsp; 
                            </div>       

                            <?php ActiveForm::end(); ?>


                        </div>
                    </div>
                </div>


            </div>
        </div>

    </div> 



</div>


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

    function readURL(input, data) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                var length = $('#dataimages tbody tr').length;
                if (length == 1) {
                    $('#dataimages tbody').append('<tr><td><img src="' + e.target.result + '" alt="Image" class="mCS_img_loaded" width="100%"></td><td><div class="radio"><label><input value="' + data + '" name="optradio" class="" type="radio" checked="true"></label></div></td><td><span><i data-id="' + $(input).attr('data-id') + '" class="fa fa-trash fa-3x deleteimg" aria-hidden="true" checked="true"></i></span></td></tr>');

                } else {
                    $('#dataimages tbody').append('<tr><td><img src="' + e.target.result + '" alt="Image" class="mCS_img_loaded" width="100%"></td><td><div class="radio"><label><input value="' + data + '" name="optradio" class="" type="radio"></label></div></td><td><span><i data-id="' + $(input).attr('data-id') + '" class="fa fa-trash fa-3x deleteimg" aria-hidden="true"></i></span></td></tr>');
                }


            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $(document).on('change', '.imageList input[type="file"]', function (e) {
        var fd = new FormData();
        fd.append("PropertyImages[image_url]", $(this)[0].files[0]);
        fd.append("property_id", $('#propertyagreements-property_id').val());
        var thiselement = this;
        $.ajax({
            url: "saveimage",
            type: "POST",
            data: fd,
            processData: false,
            contentType: false,
            success: function (data) {

                readURL(thiselement, data);

            }

        });
    });

    $(document).ready(function () {
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
    $(document).on("click", "#save_features", function (e) {
        var str = ''
        $('#p_features .check_click:checked').each(function () {
            str = str + ',' + $('#' + $(this).attr('data-id')).attr('data-name') + "-" + $('#' + $(this).attr('data-id')).val()
        })
        $.ajax({
            url: 'saveattributes',
            type: 'POST',
            data: {property_id: $("#attri_poperty_id").val(), attributes: $("#attributes").val(), property_map: str},
            success: function (data) {
                $('a[href="#p_image"]').click();
            },
            error: function (data) {
                alert(data.responseText);
            }
        });
    });
    $(document).on("click", ".deleteimg", function (e) {
        e.preventDefault();
        $.ajax({
            url: 'deleteimage',
            type: 'POST',
            data: {id: $(this).data('id')},
            success: function (data) {
                $('#dataimages').load('<?= Url::home(true) ?>sales/editproperty?id=<?= $model->id; ?> #dataimages');

            },
            error: function (data) {
                //    console.log(data.responseText);
                alert(data.responseText);
            }
        });

    });

    $(document).on('click', '#form_property_listing', function (e) {

        e.preventDefault();
        var property = $('#propertyagreements-property_id').val();

        var serialize = $('#ownerform').serialize();
        if (property.trim() !== '') {
            serialize += '&property_id=' + property;
        }
        var action = $('#ownerform').attr('action');
        $.ajax({
            url: 'editpropertyajax?id=' + property,
            type: 'POST',
            data: serialize,
            success: function (data) {
                if (data !== 'error') {
                    $('a[href="#p_features"]').click();
                }
            },
            error: function () {
                alert('Error Occured');
            }
        })
    });

    $(document).on('click', 'a[data-click="#p_contract"]', function () {
        var length = $('#dataimages tbody tr').length;
        var property = $('#propertyagreements-property_id').val();

        if (length > 0) {
            var check = $('input[type="radio"][name="optradio"]:checked').length;
            if (check == 0) {
                var id = $('input[type="radio"][name="optradio"]:first-child').val();
            } else {
                var id = $('input[type="radio"][name="optradio"]:checked').val();
            }
            $.ajax({
                url: 'setasdefault?id=' + id,
                type: 'POST',
                data: 'property_id=' + property,
                success: function (data) {
                    $('a[href="#p_contract"]').click();
                },
                error: function () {
                    alert('Error Occured');
                }
            })
        } else {
            $('a[href="#p_contract"]').click();
        }


    });

    $(document).on('change', '#propertyagreements-agreement_type', function () {
        if ($(this).val() != '2') {
            $('.isDisabled').closest('false').hide();
            $('.isDisabled').val('0');
        } else {
            $('.isDisabled').closest('false').show();
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


</script>

