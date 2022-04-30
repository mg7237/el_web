<?php
$this->title = 'PMS- Create New Request';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
?>
<div id="page-wrapper">

    <div class="col-lg-10 col-sm-10 col-xs-offset-1">
        <h2>Service request details</h2>
    </div>
    <div class="row">
        <div class="col-lg-8 col-sm-8 col-xs-offset-1 formtextnew">
            <?php
            $form = ActiveForm::begin([
                        'options' => ['id' => 'dynamic-form111', 'enctype' => 'multipart/form-data']
            ]);
            ?>
            <?php echo $form->field($model, 'client_type')->dropDownList(['4' => 'Owner', '3' => 'Tenant'], ['disabled' => 'disabled']); ?>
            <?= $form->field($model, 'property_id')->hiddenInput(['maxlength' => true])->label(false) ?>
            <div class="form-group">
                <label for="exampleInputEmail1">Property Name</label>&nbsp;&nbsp;
                <!--<span class="glyphicon glyphicon-search" data-toggle="modal" data-target=".bs-modal-sm"></span>-->
                <span class="glyphicon glyphicon-search" ></span>
                <input disabled="disabled" value="<?= Yii::$app->userdata->getPropertyNameById($model->property_id); ?>" type="text" class="form-control" placeholder="Property Name" id="tags1">
            </div>
            <?php
            $typeData = ArrayHelper::map($requestType, 'id', 'name');
            ?>

            <div class="form-group field-propertyservicerequest-owner_tenant_name required">
                <label class="control-label" for="propertyservicerequest-owner_tenant_name">Owner / Tenant Name</label>
                <select disabled="disabled" id="propertyservicerequest-owner_tenant_name" class="form-control" name="PropertyServiceRequest[owner_tenant_name]" aria-required="true">
                    <option><?= Yii::$app->userdata->getUserNameById($model->client_id); ?></option>
                </select>
                <div class="help-block"></div>
            </div>
            <?php
            $typeData = ArrayHelper::map($requestType, 'id', 'name');
            ?>
            <?= $form->field($model, 'request_type')->dropDownList($typeData, ['prompt' => 'Select Request Type'])->label('Request Type'); ?>
            <?php
            $statusData = ArrayHelper::map($status, 'id', 'name');
            ?>
            <?= $form->field($model, 'status')->dropDownList($statusData, ['prompt' => 'Select Request Type'])->label('Status'); ?>
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'description')->textArea(['rows' => 4]); ?>


                <!--<?//= $form->field($model, 'charges')->textInput(['class'=>'form-control isNumber']);  ?>-->


            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="col-lg-9 col-md-9 col-sm-9">
                    <div class="form-group field-propertyservicecomment-message required has-success">
                        <label class="control-label" for="propertyservicecomment-message">Add Comment</label>
                        <textarea id="property-message" class="form-control" name="PropertyServiceComment[message]" rows="1" style="max-width:100%;" aria-required="true" aria-invalid="false"></textarea>

                        <div class="help-block"></div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3" style="text-align: right;">
                    <a id="add_comment" class="btn btn-default" style="margin-top: 24px">Add Comment</a>
                </div>
                <div class="lorembox" id="dataComments">
                    <?php
                    if ($comments) {
                        foreach ($comments as $comment) {
                            ?>
                            <p><?= $comment->message; ?>,
                                <span>  -<?= Yii::$app->userdata->getFullNameById($comment->user_id); ?> on  <?= date('d-M-Y', strtotime($comment->created_datetime)); ?> At  <?= date('h:i a', strtotime($comment->created_datetime)); ?></span>
                            </p>
                            <?php
                        }
                    } else {
                        echo "<p> No comments</p>";
                    }
                    ?>
                </div>
            </div>

            <?php // echo $form->field($model, 'created_date')->textInput(['disabled' => 'disabled', 'maxlength' => true])  ?>


            <div id="attach-box" class="col-lg-12 col-md-12 col-sm-12">
                <?php
                if (/* $model->attachment2 != '' */false) {
                    ?>
                    <div class="col-md-6">
                        <img src="<?= "../" . $model->attachment2; ?>" style="
                             width: 90%;
                             height: auto;
                             margin-top: 10px;
                             ">

                    </div>
                <?php } if (count($attachModel) > 0) {
                    ?><p></p><?php
                    foreach ($attachModel as $row) {
                        ?>
                        <p>
                            <a href="<?= Url::home(true) . $row['attachment']; ?>" title="Attached File, Click to view." target="_blank" >
                                <?php
                                $fileNameArr = explode('/', $row['attachment']);
                                echo $fileNameArr[(count($fileNameArr) - 1)];
                                ?>
                            </a>
                            <a style="text-decoration: none;" href="requestattachremove?id=<?= base64_encode($row['a_id']); ?>&rid=<?= Yii::$app->request->get('id'); ?>" title="Remove Attachment">&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-remove"></span></a>
                        </p>


                        <?php
                    }
                }
                ?></div>

            <!--?= $form->field($model, 'attachment')->fileInput() ?-->
            <div class="col-lg-12 col-md-12 col-sm-12">
                <!--                <div class="col-md-6">
                <?php // echo $form->field($model, 'attachment2')->fileInput(['accept' => 'image/*', 'onchange' => "readURL(this);"])->label('Offer Letter')    ?>
                                    ?= $form->field($model, 'attachment')->fileInput() ?
                                    <img id="blah" src="#" alt="your image" />
                                </div>-->

                <?= $form->field($attachment, 'attachment[]')->fileInput(['multiple' => true, 'accept' => '*']) ?>
                <!--?= $form->field($model, 'attachment2')->fileInput() ?-->
<!--                    <img id="blah1" src="#" alt="your image" />-->

            </div>

            <div class="col-lg-12 col-md-12 col-sm-12">
                <?= $form->field($model, 'updated_date')->textInput(['class' => 'datepicker12 form-control', 'maxlength' => true, 'disabled' => 'disabled'])->label('Update Date / Time') ?>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12">
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
                                    <script type="text/javascript" src="../js/moment.js"></script>	
                                    <script type="text/javascript" src="../js/timepicker.js"></script>
                                    <link rel="stylesheet" href="../css/timepicker.css" />
                                    <style>
                                        ul.ui-autocomplete {
                                            z-index: 1100;
                                        }
                                        .formtextnew {
                                            margin-top: 10px;
                                        }
                                        .col-lg-10 h2 {
                                            font-size: 19px;
                                            color: #34a506;
                                        }
                                        .lorembox {
                                            background: #f7f6f6 none repeat scroll 0 0;
                                            border: 1px solid #cccccc;
                                            float: left;
                                            padding: 20px;
                                            width: 100%;
                                            font-family: "Open Sans",sans-serif;
                                        }
                                        .lorembox p {
                                            color: #919191;
                                            font-size: 14px;
                                            padding-bottom: 20px;
                                            width: 57%;
                                            clear: both;
                                        }
                                        .lorembox > p:nth-child(2) {
                                            float: right;
                                            text-align: right;
                                        }
                                        .lorembox > p:nth-child(3) {
                                            clear: both;
                                        }
                                        .lorembox > p:nth-child(4) {
                                            float: right;
                                            text-align: right;
                                        }
                                        .lorembox span {
                                            color: #4b0081;
                                            float: left;
                                            font-size: 13px;
                                            width: 100%;
                                        }
                                        input#property_name {
                                            background: #ffffff;
                                        }
                                    </style>
                                    <script>
                                        $(function () {
                                            $(".datepicker12").datetimepicker({
                                                useCurrent: true,
                                                format: 'DD-MM-YYYY / HH:mm',
                                                sideBySide: true,
                                            });
                                        });
                                    </script>
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
    </div>
</div>

<script type="text/javascript">
    $(document).on('click', '#add_comment', function (e) {
        e.preventDefault();
        var message = $('#property-message').val();
        if (message.trim() == '') {
            return false;
        }
        startLoader();
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        $.ajax({
            url: "addservicecomment?id=<?= Yii::$app->request->get('id'); ?>",
            type: "POST",
            dataType: 'json',
            data: 'message=' + message + '&service_id="<?= $_GET['id']; ?>"&_csrf=' + csrfToken,
            success: function (data) {
                var html = '';
                $(data).each(function (key, value) {
                    html = html + "<p>" + value.message + ",<span>  -" + value.username + " on  " + value.created_date + " At  " + value.created_time + "</span></p>";
                });
                $('#dataComments').html(html);
                $('#property-message').val('');
                $('#propertyservicerequest-updated_date').val(data[0].updated_date);
                hideLoader();
            }

        });
    })

    $(document).ready(function () {
        $('#servicerequestattachment-attachment').change(function () {
            var form = new FormData(document.getElementById('dynamic-form111'));
            form.append('upload_via_ajax', '1');
            form.append('id', '<?= Yii::$app->request->get('id') ?>');
            startLoader();
            $.ajax({
                url: "requestdetails?id=<?= Yii::$app->request->get('id') ?>",
                type: "POST",
                dataType: 'json',
                contentType: false,
                data: form,
                processData: false,
                success: function (data) {
                    hideLoader();
                    location.reload();
                },
                error: function (a, b, c) {
                    console.log('Error from server');
                    hideLoader();
                }
            });
        });
    });
</script>