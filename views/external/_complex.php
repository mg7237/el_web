 
<?php
$this->title = 'Add Property ';

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
    <div class="row">

        <ul class="nav nav-pills">
            <li ><a data-toggle="pill" href="<?= Url::home(true) ?>external/addproperty?id=<?= $id; ?>">Property Information</a></li>
            <li class="active" ><a data-toggle="pill" href="#p_features">Property & Complex Amenities</a></li>
            <li><a data-toggle="pill" href="#p_image">Property Images</a></li>
            <li><a data-toggle="pill" href="#p_contract">Property Agreement</a></li>


        </ul>

        <div class="tab-content">

            <div id="p_features" class="tab-pane fade active in">
                <div class= "row">
                    <div class= "col-md-12 col-sm-12">
                        <h3>Property & Complex Amenities</h3>
                        <?= $id ?>
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
                        $dataPropers = \app\models\PropertyAttributeMap::find()->where(['property_id' => $id])->all();
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
                                                        <img src="../<?= $propertyAttrFac->icon; ?>" alt="Icon">
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

                                    <input type="hidden" id="attri_poperty_id" name="attri_poperty_id" value="<?= $id; ?>"> 
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

        </div>

    </div>
</div>
<script>
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
        startLoader();
        $.ajax({
            url: 'saveattributes',
            type: 'POST',
            data: {property_id: $("#attri_poperty_id").val(), attributes: $("#attributes").val()},
            success: function (data) {
                //$('#featuresComplex').load(' #featuresComplex') ;
                hideLoader();
            },
            error: function (data) {
                //    console.log(data.responseText);
                hideLoader();
                // alert(data.responseText);
                alert('Failure');
            }
        });
    });

</script>




