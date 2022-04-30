<?php
$this->title = 'PG On Boarding';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
?>


<div class="container">
    <?php $form = ActiveForm::begin(['id' => 'tenant-registration', 'options' => ['class' => 'forminputpopup']]); ?>
    <div class="row">
        <div class="col-12">
            <h3>PG Onboarding</h3>
        </div>
        <div class="col-md-6 col-sm-12">
            <?= $form->field($model, 'full_name')->textInput(['maxlength' => true, 'placeholder' => 'Full Name'])->label('Full Name'); ?>
        </div>
        <div class="col-md-6 col-sm-12">
            <?= $form->field($model, 'address_line_1')->textInput(['maxlength' => true, 'placeholder' => 'Address Line 1'])->label('Address Line 1'); ?>
        </div>
        <div class="col-md-6 col-sm-12">
            <?= $form->field($model, 'address_line_2')->textInput(['maxlength' => true, 'placeholder' => 'Address Line 2'])->label('Address Line 2'); ?>
        </div>
        <div class="col-md-6 col-sm-12">
            <?= $form->field($model, 'city_name')->textInput(['maxlength' => true, 'placeholder' => 'City Name'])->label('City Name'); ?>
        </div>
        <div class="col-md-6 col-sm-12">
            <?php
            $states = \app\models\States::find()->all();
            $stateData = \yii\helpers\ArrayHelper::map($states, 'code', 'name');
            ?>
            <?= $form->field($model, 'state_code')->dropDownList($stateData, ['prompt' => 'Select State*'])->label('Select State'); ?>
        </div>
        <div class="col-md-6 col-sm-12">
            <?= $form->field($model, 'pin_code')->textInput(['maxlength' => true, 'placeholder' => 'Pincode'])->label('Pincode'); ?>
        </div>
        <div class="col-md-6 col-sm-12">
            <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => 'Email'])->label('Email'); ?>
        </div>
        <div class="col-md-6 col-sm-12">
            <?= $form->field($model, 'mobile')->textInput(['maxlength' => true, 'placeholder' => 'Mobile'])->label('Mobile'); ?>
        </div>
        <div class="col-md-6 col-sm-12">
            <?= $form->field($model, 'rent')->textInput(['maxlength' => true, 'placeholder' => 'Rent'])->label('Rent'); ?>
        </div>
        <div class="col-md-6 col-sm-12">
            <?= $form->field($model, 'maintenance')->textInput(['maxlength' => true, 'placeholder' => 'Maintenance'])->label('Maintenance'); ?>
        </div>
        <div class="col-md-6 col-sm-12">
            <?= $form->field($model, 'deposit')->textInput(['maxlength' => true, 'placeholder' => 'Deposit'])->label('Deposit'); ?>
        </div>
        <div class="col-md-6 col-sm-12">
            <?= $form->field($model, 'check_in_date')->textInput(['maxlength' => true, 'placeholder' => 'Check In Date', 'class' => 'datepicker form-control'])->label('Check In Date'); ?>
        </div>
        <div class="col-md-6 col-sm-12">
            <?= $form->field($model, 'exit_date')->textInput(['maxlength' => true, 'placeholder' => 'Exit Date', 'class' => 'datepicker form-control'])->label('Exit Date'); ?>
        </div>
        <div class="col-md-6 col-sm-12">
            <?= $form->field($model, 'emergency_contact_name')->textInput(['maxlength' => true, 'placeholder' => 'Emergency Contact Name'])->label('Emergency Contact Name'); ?>
        </div>
        <div class="col-md-6 col-sm-12">
            <?= $form->field($model, 'emergency_contact_email')->textInput(['maxlength' => true, 'placeholder' => 'Emergency Contact Email'])->label('Emergency Contact Email'); ?>
        </div>
        <div class="col-md-6 col-sm-12">
            <?= $form->field($model, 'emergency_contact_number')->textInput(['maxlength' => true, 'placeholder' => 'Emergency Contact Number'])->label('Emergency Contact Number'); ?>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="uploadbox">	
                <div class="col-md-6 col-sm-6">
                    <select class="selectpicker IDproof cus_show" id="id_proof">
                        <option value="">Select Emergency Proof</option>
                        <option value="1">Driving License</option>
                        <option value="2">Aadhaar Card</option>
                        <option value="3">Passport</option>
                        <option value="4">Voter ID</option>
                        <option value="5">Utility Bill (Gas, Water, Electricity, Landline)</option>
                        <option value="6">Letter from Employer</option>
                        <option value="7">Bank Statement or Passbook</option>
                        <option value="8">Not Available</option>
                        <option value="9">PAN Card</option>
                    </select>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12 cust-idproof" id="IDproof">
                    <div class=" identity_file_0 identity_box box col-md-12 col-sm-12"><input type="file" attr-class="identity_file_0" name="address_proof[1][]" attr-type="Driving License" class="identity_files" id="fileupload" accept="image/*"></div></div>
                <div class="clearfix"></div>
                <div class="row identity_image_preview"></div>
            </div>
        </div>
    </div>
    <div class="row col-lg-9">
        <br>
        <div class="col-lg-6">
            <button type="submit" id="ownerCreateBtn" class="btn savechanges_submit_contractaction">Save</button>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>