<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use app\models\Properties;
use app\models\Bookings;
use app\models\TenantAgreements;
use app\models\PropertiesAddress;

/* @var $this yii\web\View */
/* @var $model backend\models\search\JobSearch */
/* @var $form yii\bootstrap\ActiveForm */
//echo "<pre>";print_r($allbookings);echo "</pre>";die;
?>
<!-- Modal content-->
<?php //print_r($allbookings);die;?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> 
<div>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <div class= "changepassword" style= "padding: 8px 8px;">
            <h5 class="modal-title text-center" style="text-transform: uppercase;font-size: 14px;letter-spacing: 0.9px;font-weight: 600;">Create Request</h5>
        </div>
    </div>
    <div class="modal-body" style="height:auto;">


        <div class="job-search">

            <?php
            $form = ActiveForm::begin([
                        'options' => ['id' => 'dynamic-form111', 'enctype' => 'multipart/form-data']
            ]);
            ?>

            <?php
            $bookings = TenantAgreements::find()->where(['tenant_id' => Yii::$app->user->id, 'status' => 1])->all();
            $listData = ArrayHelper::map($bookings, 'parent_id', 'name');
            ?>
            <!--?= $form->field($propertyServiceRequest, 'property_id')->label('Property Name')->dropDownList($listData, ['prompt'=>'Select Property']); ?-->
            <?= $form->field($propertyServiceRequest, 'request_type')->dropDownList(ArrayHelper::map($requestType, 'id', 'name'), ['prompt' => 'Select request type']); ?>
            <?= $form->field($propertyServiceRequest, 'title')->textInput(['maxlength' => true]) ?>
            <?= $form->field($propertyServiceRequest, 'description')->textInput(['maxlength' => true]) ?>
            <div class="form-group field-servicerequest-Property">
                <label class="control-label" for="servicerequest-Property">Property</label>
                <select id="property_id" name="property_id" class="form-control">

                    <option value="">Select Property</option>
                    <?php
                    $allb = count($allbookings);

                    foreach ($allbookings as $book) {
                        ?>
                        <option value="<?php echo $book['property_id']; ?>" <?php if ($allb == 1) { ?>selected<?php } ?>><?php echo $book['property_name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <?= $form->field($attachment, 'attachment[]')->fileInput(['multiple' => true, 'accept' => '*']) ?>


            <div class="form-group">
                <?php echo Html::submitButton('Save', ['class' => 'btn btn-info btn-lg cust_btn1', 'id' => 'submit_id']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>  


    </div>
</div>

<style>
    #submit_id
    {
        width:100%;
    }
    .txt-bod{
        border: solid 1px #e9ebf0 !important;
    }
    .sbu_btn {
        width: 100% !important;
        background: #e24c3f !important;
        text-shadow: 0 0 white !important;
        border-color: #ffffff !important;
        height: 48px;
    }
    .form-group.field-servicerequest-title,.form-group.field-servicerequest-description,.form-group.field-servicerequest-request_type,.form-group.field-servicerequest-Property,.form-group.field-servicerequestattachment-attachment,.form-group {
        width: 290px;
        margin-left: 20px;
        font-size: 12px;

    }
    select.form-control,input.form-control
    {
        border: 1px solid #ccc;
        background-color:white !important;
    }
    input#servicerequest-description, input#servicerequest-title, select#servicerequest-request_type, select#property_id {
        height: 45px;
        padding: 10px;
        background: white;
    }
</style>




