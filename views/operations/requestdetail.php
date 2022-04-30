
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
                <input type="text" class="form-control" placeholder="Property Name" id="property_name" value="<?= ($model->property_id != 0) ? Yii::$app->userdata->getPropertyNameById($model->property_id) : ''; ?>">
            </div>
            <?php
            $statusData = ArrayHelper::map($status, 'id', 'name');
            ?>
            <?= $form->field($model, 'status')->dropDownList($statusData, ['prompt' => 'Select Request Type'])->label('Request Type'); ?>


            <div class="col-lg-12 col-md-12">
                <div class="col-lg-9 col-md-9">
                    <div class="form-group field-propertyservicecomment-message required has-success">
                        <label class="control-label" for="propertyservicecomment-message">Add Comment</label>
                        <textarea id="property-message" class="form-control" name="PropertyServiceComment[message]" rows="2" style="max-width:100%;" aria-required="true" aria-invalid="false"></textarea>

                        <div class="help-block"></div>
                    </div>    
                </div>
                <div class="col-lg-3 col-md-3" style="text-align: right;">
                    <a id="add_comment" class="btn btn-default" style="margin-top: 29px">Add Comment</a>
                </div>
                <div class="lorembox" id="dataComments">
                    <?php
                    if ($comments) {
                        foreach ($comments as $comment) {
                            ?>
                            <p><?= $comment->message; ?>, 
                                <span>  -<?= Yii::$app->userdata->getFullNameById($comment->user_id); ?> on  <?= date('m/d/Y', strtotime($comment->created_datetime)); ?> At  <?= date('h:i a', strtotime($comment->created_datetime)); ?></span>
                            </p>
                            <?php
                        }
                    } else {
                        echo "<p> No comments</p>";
                    }
                    ?>            
                </div>
            </div>

            <?php
            if ($model->attachment != '') {
                ?>
                <img src="<?= $model->attachment; ?>" style="
                     width: 250px;
                     height: 223px;
                     margin-top: 10px;
                     ">

                <?php
            }
            ?>
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

<script type="text/javascript">
    $(document).on('click', '#add_comment', function (e) {
        e.preventDefault();
        var message = $('#property-message').val();
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        $.ajax({
            url: "addservicecomment",
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
            }

        });
    })
</script>