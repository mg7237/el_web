 
<?php
$this->title = 'Edit Property ';

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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<div id="page-wrapper">
    <script>

        $(function () {
            $(".datepicker").datepicker({
                minDate: 0,
                changeMonth: true,
                changeYear: true,
                // yearRange: "-0:+1",
                minDate: '0',
                numberOfMonths: 1,
                dateFormat: 'dd-mm-yy',

            });
        });


    </script>
    <div class="row">

        <ul class="nav nav-pills">
            <li class="active"><a data-toggle="pill" href="#p_info">Property Information</a></li>
            <li><a data-toggle="pill" href="#p_features">Property & Complex Amenities</a></li>
            <li><a data-toggle="pill" href="#p_image">Property Images</a></li>
            <li><a data-toggle="pill" href="#p_contract">Property Agreement</a></li>
        </ul>

        <div class="tab-content">
            <div id="p_info" class="tab-pane fade in active">
                <div class= "row">
                    <div class= "col-md-12"><h3>Property information</h3> 

                        <?php if (Yii::$app->session->hasFlash('success')) { ?>
                            <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                                <?= Yii::$app->session->getFlash('success'); ?>
                            </div>   
                        <?php } ?>
                    </div> 

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
                                    'enableAjaxValidation' => false]);
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
                            <?= $form->field($model, 'beds_room')->textInput(['value' => $roomsCountOne]) ?>
                            <div id="newFields">

                                <?php
                                foreach ($roomsOne as $key => $data) {
                                    $roomsChild = \app\models\ChildProperties::find()->where(['parent' => $data->id, 'type' => 2])->all();
                                    $roomsChildOne = count($roomsChild);
                                    echo '<label> Rooms ' . ($key + 1) . ' </label> <input type="text" placeholder="Beds*" value="' . $roomsChildOne . '" name="Properties[beds][]" class="form-control">';
                                }
                                ?>

                            </div>
                        </div>
                        <div id="no_of_rooms" style="display:none;">
                            <?php
                            $rooms = \app\models\ChildProperties::find()->where(['main' => $model->id, 'type' => 1])->all();
                            $roomsCount = count($rooms);
                            ?>	
                            <?= $form->field($model, 'rooms')->textInput(['value' => $roomsCount]) ?>
                        </div>

                        <div id="flat" style="display:none;">
                            <?= $form->field($model, 'flat_bhk')->textInput() ?>

                            <?= $form->field($model, 'flat_area')->textInput() ?>

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
                        <?= $form->field($model, 'region')->dropDownList($cityData, ['prompt' => 'Select region']); ?>

                        <?= $form->field($model, 'lat')->hiddenInput(['maxlength' => true, 'placeholder' => 'Latitude*'])->label(false); ?>
                        <?= $form->field($model, 'lon')->hiddenInput(['maxlength' => true, 'placeholder' => 'Longitude*'])->label(false); ?>
                        <?= $form->field($model, 'pincode')->textInput(['maxlength' => true, 'placeholder' => 'Pincode*']); ?>
                        <?= $form->field($model, 'status')->dropDownList([0 => 'InActive property', 1 => 'Activate Property'], ['prompt' => 'Select status']); ?>
                        <?= $form->field($model, 'flat_type')->dropDownList([0 => 'Semi-furnished', 1 => 'Furnishsed'], ['prompt' => 'Select Type']); ?>

                        <div style="padding-top:20px;" class="row pull-right col-lg-8">
                            <div class="col-lg-6">
                                <?= Html::submitButton('Save', ['class' => 'btn btn savechanges_submit_contractaction']) ?>
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
                                                        <input class="check_click"  <?= in_array($propertyAttrFac->id, $dataProper) ? 'checked' : '' ?>  type="checkbox" value="<?= $propertyAttrFac->id; ?>">
                                                    </label>
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
                                                        <input class="check_click" <?= in_array($propertyAttrCom->id, $dataProper) ? 'checked' : '' ?>   type="checkbox" value="<?= $propertyAttrCom->id; ?>">
                                                    </label>
                                                </div>

                                            </div>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>



                        <div style="padding-top:20px;" class="row pull-right col-lg-8">
                            <div class="col-lg-6">
                                <form method="POST" id="formfaclities">

                                    <input type="hidden" id="attri_poperty_id" name="attri_poperty_id" value="<?= $model->id; ?>"> 
                                    <input type="hidden" id="attributes" name="attributes" value="<?= $dataIdsAttri; ?>"> 

                                    <?= Html::Button('Save', ['id' => 'save_features', 'class' => 'btn btn savechanges_submit_contractaction']) ?>
                                </form>

                            </div>
                            <div class="col-lg-6">
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
                                return false;"  class= "btn savechanges_submit1">Add New Image</button>

                    </form> 
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
                        $form = ActiveForm::begin(['id' => 'property_agreement', 'action' => 'addnewagreement?id=' . $model->id,
                                    'options' => [
                                        'class' => 'form-inline'
                                    ],
                                    'fieldConfig' => [
                                        'template' => "{label}{input}{error}",
                                        'options' => [
                                            'tag' => 'false'
                                        ]
                                    ],
                                    'enableAjaxValidation' => false]);
                        ?>	
                        <?= $form->field($modelPropertyAgreements, 'rent')->textInput(['maxlength' => true, 'placeholder' => 'Rent*']) ?>
                        <?= $form->field($modelPropertyAgreements, 'property_id')->hiddenInput(['value' => $model->id, 'placeholder' => 'Rent*'])->label(false); ?>
                        <?= $form->field($modelPropertyAgreements, 'owner_id')->hiddenInput(['value' => $model->owner_id, 'placeholder' => 'Rent*'])->label(false); ?>
                        <?= $form->field($modelPropertyAgreements, 'manteinance')->textInput(['maxlength' => true, 'placeholder' => 'maintenance*'])->label('maintenance') ?>
                        <?= $form->field($modelPropertyAgreements, 'deposit')->textInput(['maxlength' => true, 'placeholder' => 'Deposit*']) ?>
                        <?= $form->field($modelPropertyAgreements, 'rent_start_date')->textInput(['maxlength' => true, 'placeholder' => 'Rent start date*', 'class' => 'datepicker']) ?>
                        <?= $form->field($modelPropertyAgreements, 'contract_start_date')->textInput(['maxlength' => true, 'placeholder' => 'Contract start date*', 'class' => 'datepicker']) ?>
                        <?= $form->field($modelPropertyAgreements, 'contract_end_date')->textInput(['maxlength' => true, 'placeholder' => 'Contract end date*', 'class' => 'datepicker']) ?>
                        <?= $form->field($modelPropertyAgreements, 'furniture_rent')->textInput(['maxlength' => true, 'placeholder' => 'Furniture*', 'class' => 'form-control isNumber1']) ?>
                        <?= $form->field($modelPropertyAgreements, 'notice_peroid')->textInput(['maxlength' => true, 'placeholder' => 'Notice peroid*', 'class' => 'form-control isNumber1']) ?>
                        <?= $form->field($modelPropertyAgreements, 'min_contract_peroid')->textInput(['maxlength' => true, 'placeholder' => 'Minnimum contract peroid*']) ?>
                        <?= $form->field($modelPropertyAgreements, 'agreement_url')->fileInput() ?> 



                        <div style="padding-top:20px;" class="row pull-right col-lg-8">
                            <div class="col-lg-6">
                                <?= Html::submitButton('Save', ['class' => 'btn btn savechanges_submit_contractaction']) ?>
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
        $('#' + str).click();
        return false;
    }
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
        $.ajax({
            url: 'saveattributes',
            type: 'POST',
            data: {property_id: $("#attri_poperty_id").val(), attributes: $("#attributes").val()},
            success: function (data) {
                $('a[href="#p_image"]').click();
                //$('#featuresComplex').load(' #featuresComplex') ;
            },
            error: function (data) {
                //    console.log(data.responseText);
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

    $('#add_image').on('change', function () {

        var fd = new FormData();
        fd.append("PropertyImages[image_url]", $('#add_image')[0].files[0]);
        fd.append("property_id", $('#property_id').val());
        $.ajax({
            url: "saveimage",
            type: "POST",
            data: fd,
            processData: false,
            contentType: false,
            success: function (data) {
                if (data == 'done') {
                    $('#dataimages').load('<?= Url::home(true) ?>sales/editproperties?id=<?= base64_encode($model->id); ?> #dataimages');
                }
            }

        });
    });
</script>

