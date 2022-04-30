
<?php
$this->title = 'PMS- Create New Request';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
?>
<div id="page-wrapper">

    <div class="col-lg-10 col-xs-offset-1">
        <ol class="breadcrumb">
            <li class="active"><a href="#">Service request details </a></li>
        </ol>
    </div>
    <div class="row">
        <div class="col-lg-10 col-xs-offset-1">
            <?php
            $form = ActiveForm::begin([
                        'options' => ['id' => 'dynamic-form111', 'enctype' => 'multipart/form-data']
            ]);
            ?>
            <?php echo $form->field($model, 'client_type')->dropDownList(['4' => 'Owner', '3' => 'Tenant']); ?>
            <?= $form->field($model, 'property_id')->hiddenInput(['maxlength' => true])->label(false) ?>
            <div class="form-group">
                <label for="exampleInputEmail1">Property Name</label>&nbsp;&nbsp;
                <span class="glyphicon glyphicon-search" data-toggle="modal" data-target=".bs-modal-sm"></span>
                <!--<span class="glyphicon glyphicon-search" ></span>-->
                <input type="text" class="form-control" placeholder="Property Name" id="tags1">
            </div>
            <?php
            $typeData = ArrayHelper::map($requestType, 'id', 'name');
            ?>

            <div class="form-group field-propertyservicerequest-owner_tenant_name required">
                <label class="control-label" for="propertyservicerequest-owner_tenant_name">Owner / Tenant Name</label>
                <select id="propertyservicerequest-owner_tenant_name" class="form-control" name="PropertyServiceRequest[owner_tenant_name]" aria-required="true">

                </select>
                <div style="color: darkred; display: none;" class="owner-tenant-name-help-block help-block">Owner/Tenant name cannot be blank.</div>
            </div>
            <?php
            $typeStatus = ArrayHelper::map($status, 'id', 'name');
            //print_r($typeStatus); exit;
            ?>
            <?= $form->field($model, 'request_type')->dropDownList($typeData, ['prompt' => 'Select Request Type*'])->label('Request Type'); ?>
            <?= $form->field($model, 'status')->dropDownList($typeStatus)->label('Status'); ?>
            <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'placeholder' => 'Title*']) ?>
            <?= $form->field($model, 'description')->textArea(['rows' => 4, 'placeholder' => 'Description*']); ?>

            <?= $form->field($comment, 'message')->textArea(['rows' => 2, 'placeholder' => 'Comment*'])->label('Comment'); ?>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($attachment, 'attachment[]')->fileInput(['multiple' => true, 'accept' => '*'])->label('Attachment') ?>
                    <!--?= $form->field($model, 'attachment')->fileInput() ?-->
                    <!--<img id="blah" src="#" alt="your image" />-->
                </div>
                <div class="col-md-6">
                    <?php //echo $form->field($model, 'attachment2')->fileInput(['accept' => 'image/*', 'onchange' => "readURL1(this);"])->label('Employee ID') ?>
                    <!--?= $form->field($model, 'attachment2')->fileInput() ?-->
                    <!--<img id="blah1" src="#" alt="your image" />-->
                </div>
            </div>

            <?= $form->field($model, 'updated_date')->textInput(['disabled' => 'disabled'])->label('Updated Date'); ?>

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
                                    <input required="" id="tags2" name="userid" type="text" class="form-control" placeholder="Search for Property" class="input-medium" required="">
                                    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
                                    <script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
                                    <style>
                                        ul.ui-autocomplete {
                                            z-index: 1100;
                                        }
                                    </style>
                                    <script type="text/javascript">
                                        function readURL(input) {
                                            if (input.files && input.files[0]) {
                                                var reader = new FileReader();

                                                reader.onload = function (e) {
                                                    $('#blah').attr('src', e.target.result)
                                                            .width(290)
                                                            .height(200);
                                                }

                                                reader.readAsDataURL(input.files[0]);
                                            }
                                        }
                                        function readURL1(input) {
                                            if (input.files && input.files[0]) {
                                                var reader = new FileReader();

                                                reader.onload = function (e) {
                                                    $('#blah1').attr('src', e.target.result)
                                                            .width(290)
                                                            .height(200);
                                                }

                                                reader.readAsDataURL(input.files[0]);
                                            }
                                        }
                                    </script>
                                    <script>

                                        $(function () {
                                            $("#tags1").autocomplete({
                                                source: "getproperty1",
                                                select: function (event, ui) {
                                                    getOwnerTenantName(ui);
                                                    event.preventDefault();
                                                    //  console.log(ui.item);
                                                    $("#property_name").val(ui.item.label);
                                                    $("#propertyservicerequest-property_id").val(ui.item.value);
                                                    $("#tags1").val(ui.item.name);
                                                },
                                            });
                                        });

                                        $(function () {
                                            $("#tags2").autocomplete({
                                                source: "getproperty1",
                                                select: function (event, ui) {
                                                    getOwnerTenantName(ui);
                                                    event.preventDefault();
                                                    // console.log(ui.item);
                                                    $("#property_name").val(ui.item.label);
                                                    $("#propertyservicerequest-property_id").val(ui.item.value);
                                                    $("#tags1").val(ui.item.name);
                                                    $("#tags2").val(ui.item.name);
                                                },
                                            });
                                        });

                                        $(document).ready(function () {
                                            $('#propertyservicerequest-client_type').change(function () {
                                                $('#tags1').val('');
                                                $('#tags2').val('');
                                                $('#propertyservicerequest-owner_tenant_name').html('');
                                            })

                                            $('.savechanges_submit_contractaction').click(function (e) {
                                                var ownerTenantId = $('#propertyservicerequest-owner_tenant_name').val();
                                                if (ownerTenantId == '' || ownerTenantId == null) {
                                                    e.preventDefault();
                                                    $('#propertyservicerequest-owner_tenant_name').css('border-color', 'darkred');
                                                    $('.owner-tenant-name-help-block').show();
                                                } else {
                                                    $('#propertyservicerequest-owner_tenant_name').css('border-color', 'lightgray');
                                                    $('.owner-tenant-name-help-block').hide();
                                                }
                                            });
                                        });

                                        function getOwnerTenantName(ele) {
                                            var url = '';
                                            var clientType = $('#propertyservicerequest-client_type').val();
                                            if (clientType == 4) {
                                                url = 'getownernamebypropertyid';
                                            } else if (clientType == 3) {
                                                url = 'gettenantnamebypropertyid';
                                            }
                                            $.ajax({
                                                url: url,
                                                type: "POST",
                                                dataType: 'json',
                                                data: ele,
                                                success: function (data) {
                                                    var html = '';
                                                    $('#propertyservicerequest-owner_tenant_name').html('');
                                                    data.forEach(function (element) {
                                                        html = '<option value="' + element.id + '">' + element.full_name + '</option>';
                                                        $('#propertyservicerequest-owner_tenant_name').append(html);
                                                    });
                                                }

                                            });
                                        }

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
