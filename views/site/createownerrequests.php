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
?>
<!-- Modal content-->
<div>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <div class= "changepassword" style= "padding: 0px 8px;">
            <h5 class="modal-title text-center" style="text-transform: uppercase;font-size: 14px;letter-spacing: 0.9px;font-weight: 600;">Create Request</h5>
        </div>
    </div>
    <div class="modal-body">



        <div class="job-search">

            <?php
            $form = ActiveForm::begin([
                        'options' => ['id' => 'dynamic-form111', 'enctype' => 'multipart/form-data']
            ]);
            ?>

            <?php
            $bookings = TenantAgreements::find()->where(['tenant_id' => Yii::$app->user->id, 'status' => 1])->all();


            $listData = ArrayHelper::map($bookings, 'parent_id', 'name');

            $requestType = \app\models\RequestType::find()->all();
            $listRequests = ArrayHelper::map($requestType, 'id', 'name');
            ?>
            <?php //$form->field($propertyServiceRequest, 'property_id')->hiddenInput(['maxlength' => true, 'value' => Yii::$app->request->get('id')])->label(false) ?>
            <?= $form->field($propertyServiceRequest, 'title')->textInput(['maxlength' => true, 'placeholder' => 'Title*', 'class' => 'form-control txt-bod']) ?>
            <?= $form->field($propertyServiceRequest, 'description')->textInput(['maxlength' => true, 'placeholder' => 'Description*', 'class' => 'form-control txt-bod']) ?>
            <?= $form->field($propertyServiceRequest, 'request_type')->dropDownList($listRequests, ['prompt' => 'Select Request Type*', 'class' => 'form-control txt-bod']); ?>
            <?= $form->field($attachment, 'attachment[]')->fileInput(['multiple' => true, 'accept' => '*']) ?>


            <div class="form-group cust-idgrp">
                <?php echo Html::submitButton('Save', ['class' => 'btn btn-info sbu_btn', 'id' => 'submit_id']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>


    </div>
</div>

<style>

    @media screen and (max-width: 768px){
        .form-group.field-servicerequest-title{
            width: 200% !important;
        }
        .form-group.field-servicerequest-description{
            width: 200% !important;
        }
        .form-group.field-servicerequest-request_type{
            width: 200% !important;
        }
        .cust-idgrp{
            width: 200% !important;
        }
        .cust-nopad{
            width: 100%;
        }
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
    .form-group.field-servicerequest-title,.form-group.field-servicerequest-description,.form-group.field-servicerequest-request_type,.form-group.field-servicerequestattachment-attachment,.form-group {
        width: 414px;
        margin-left: 23px;
        font-size: 12px;
    }
    input#servicerequest-description, input#servicerequest-title, select#servicerequest-request_type {
        height: 45px;
        padding: 10px;
        background: white;
    }
</style>