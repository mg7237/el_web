
<?php
$this->title = 'PMS- Create New Request';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
?>
<div id="page-wrapper">

    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li class="active"><a href="#"> request details </a></li>
        </ol>
    </div>
    <div class="row">
        <div class="col-lg-10 col-xs-offset-1 formtextnew">
            <?php
            $form = ActiveForm::begin([
                        'options' => ['id' => 'dynamic-form111', 'enctype' => 'multipart/form-data']
            ]);
            ?>
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'description')->textArea(['rows' => 4]); ?>
            <?php
            $typeData = ArrayHelper::map($requestType, 'id', 'name');
            ?>
            <?= $form->field($model, 'request_type_id')->dropDownList($typeData, ['prompt' => 'Select Request Type'])->label('Request Type'); ?>

            <?= $form->field($model, 'charges')->textInput(['class' => 'form-control isNumber']); ?>

            <?= $form->field($model, 'property_id')->hiddenInput(['maxlength' => true])->label(false) ?>
            <div class="form-group">
                <label for="exampleInputEmail1">Property Name</label>&nbsp;&nbsp;<span class="glyphicon glyphicon-search" data-toggle="modal" data-target=".bs-modal-sm"></span>
                <input type="text" class="form-control" placeholder="Property Name" id="property_name">
            </div>



            <?= $form->field($comment, 'message')->textArea(['rows' => 2]); ?>

            <?= $form->field($model, 'attachment')->fileInput() ?>

            <div class="row">
                <div class="row col-lg-12 col-sm-12" style="padding-top:20px; padding-bottom: 20px;">
                    <div class="col-lg-6 col-sm-6">
                        <?= Html::submitButton('Save', ['class' => 'btn btn savechanges_submit_contractaction']) ?>
                    </div>
                    <div class="col-lg-6 col-sm-6">
                        <a type="button" class="btn savechanges_submit cancel_confirm" href="servicerequests">Cancel</a> 
                    </div>

                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>


        <!-- /.col-lg-12 -->
    </div>
</div>


<div class="modal fade bs-modal-sm" id="modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-body">
                <div id="myTabContent" class="tab-content">
                    <div class="tab-pane fade active in" id="signin">
                        <fieldset>
                            <!-- Sign In Form -->
                            <!-- Text input-->
                            <div class="">
                                <div class="controls">
                                    <label> Search Property </label>  
                                    <input required="" id="tags1" name="userid" type="text" class="form-control" placeholder="Search for Property" class="input-medium" required="">
                                    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
                                    <script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
                                    <style>
                                        ul.ui-autocomplete {
                                            z-index: 1100;
                                        }
                                    </style>
                                    <script>

                                        $(function () {
                                            $("#tags1").autocomplete({
                                                source: "getproperty1",
                                                select: function (event, ui) {
                                                    event.preventDefault();
                                                    //  console.log(ui.item);
                                                    $("#property_name").val(ui.item.label);
                                                    $("#propertyservicerequest-property_id").val(ui.item.value);
                                                    $("#tags1").val(ui.item.label);
                                                },
                                            });
                                        });

                                    </script>
                                </div>
                            </div>
                            <div class="">
                                <label class="control-label" for="signin"></label>
                                <center>
                                    <div class="controls">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </center>
                            </div>
                        </fieldset>
                    </div>


                </div>

            </div>
        </div>
    </div></div> 