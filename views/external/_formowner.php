 
<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>
<style>
    .state {
        float: left;
        width: 33%;
    }

    .states {
        float: left;
        width: 50%;
    }
</style>
<div class="modal-header">
    <button type="button" class="close closetext" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <div class= "modal-header">Create Property Owner Lead</div>

</div>
<div class="modal-dialog modal-dialognew">

    <div class="modal-body loginbody signupbody">		
        <?php
        $form = ActiveForm::begin(['id' => 'ownerform', 'options' => [
                        'class' => 'forminputpopup'
                    ], 'enableAjaxValidation' => true]);
        ?>

        <?= $form->field($model, 'full_name')->textInput(['maxlength' => true, 'placeholder' => 'Property Owner Name*'])->label(false); ?>

        <?= $form->field($model, 'email_id')->textInput(['maxlength' => true, 'placeholder' => 'Property Owner Email Id*'])->label(false); ?>

        <?= $form->field($model, 'contact_number')->textInput(['maxlength' => 15, 'placeholder' => 'Property Owner Contact Number*', 'class' => 'form-control'])->label(false); ?>
        <?= $form->field($model, 'communication')->textarea(['rows' => 3, 'placeholder' => 'Comments*'])->label(false); ?>

        <?= $form->field($model, 'address')->textInput(['maxlength' => true, 'placeholder' => 'Property Owner Address Line 1*'])->label(false); ?>
        <?= $form->field($model, 'address_line_2')->textInput(['maxlength' => true, 'placeholder' => 'Property Owner Address Line 2'])->label(false); ?>

        <?php
        $states = \app\models\States::find()->all();
        $stateData = \yii\helpers\ArrayHelper::map($states, 'id', 'name');
        ?>

        <div class="state">
            <?= $form->field($modelOwnerProfile, 'city_name')->textInput(['placeholder' => 'Owner city *', 'class' => ''])->label(false); ?>
        </div>

        <div class="state"> 
            <?= $form->field($model, 'lead_state')->dropDownList($stateData, ['prompt' => 'Select state*', 'url' => \Yii::$app->getUrlManager()->createUrl('users/getcity')])->label(false); ?>
        </div>

        <div class="state">
            <?= $form->field($model, 'pincode')->textInput(['maxlength' => 15, 'placeholder' => 'Pincode*'])->label(false); ?>
        </div>

        <label>City Where the Property is Located</label>
        <?php
        $userType = Yii::$app->user->identity->user_type;
        $userId = Yii::$app->user->identity->id;
        if ($userType == 7) {
            $opsModel = \app\models\OperationsProfile::findOne(['operations_id' => $userId]);
            if ($opsModel->role_code == 'OPEXE' && $opsModel->role_value == 'ELHQ') {
                $cities = \app\models\Cities::find()->where(['status' => 1])->all();
            } else if ($opsModel->role_code == 'OPSTMG') {
                $cities = \app\models\Cities::find()->where(['status' => 1, 'state_code' => $opsModel->role_value])->all();
            } else if ($opsModel->role_code == 'OPCTMG') {
                $cities = \app\models\Cities::find()->where(['status' => 1, 'code' => $opsModel->role_value])->all();
            } else if ($opsModel->role_code == 'OPBRMG') {
                $branchModel = \app\models\Branches::findOne(['branch_code' => $opsModel->role_value]);
                $cities = \app\models\Cities::find()->where(['status' => 1, 'code' => $branchModel->city_code])->all();
            } else if ($opsModel->role_code == 'OPEXE') {
                $branchModel = \app\models\Branches::findOne(['branch_code' => $opsModel->role_value]);
                $cities = \app\models\Cities::find()->where(['status' => 1, 'code' => $branchModel->city_code])->all();
            }
        } else if ($userType == 6) {
            $saleModel = \app\models\SalesProfile::findOne(['sale_id' => $userId]);
            if ($saleModel->role_code == 'SLEXE' && $saleModel->role_value == 'ELHQ') {
                $cities = \app\models\Cities::find()->where(['status' => 1])->all();
            } else if ($saleModel->role_code == 'SLSTMG') {
                $cities = \app\models\Cities::find()->where(['status' => 1, 'state_code' => $saleModel->role_value])->all();
            } else if ($saleModel->role_code == 'SLCTMG') {
                $cities = \app\models\Cities::find()->where(['status' => 1, 'code' => $saleModel->role_value])->all();
            } else if ($saleModel->role_code == 'SLBRMG') {
                $branchModel = \app\models\Branches::findOne(['branch_code' => $saleModel->role_value]);
                $cities = \app\models\Cities::find()->where(['status' => 1, 'code' => $branchModel->city_code])->all();
            } else if ($saleModel->role_code == 'SLEXE') {
                $branchModel = \app\models\Branches::findOne(['branch_code' => $saleModel->role_value]);
                $cities = \app\models\Cities::find()->where(['status' => 1, 'code' => $branchModel->city_code])->all();
            }
        }

        $cityData = \yii\helpers\ArrayHelper::map($cities, 'id', 'city_name');
        ?>
        <div class="states"> 

            <?= $form->field($model, 'lead_city')->dropDownList($cityData, ['prompt' => 'Select city where the property is located *', 'url' => \Yii::$app->getUrlManager()->createUrl('advisers/getregions')])->label(false); ?>
        </div>

        <?php
        if ($userType == 7) {
            $opsModel = \app\models\OperationsProfile::findOne(['operations_id' => $userId]);
            if ($opsModel->role_code == 'OPEXE' && $opsModel->role_value == 'ELHQ') {
                $branches = \app\models\Branches::find()->where('branch_code != :branch_code', ['branch_code' => 'NA'])->all();
            } else if ($opsModel->role_code == 'OPSTMG') {
                $branches = \app\models\Branches::find()->where('branch_code != :branch_code AND state_code = :state_code', ['branch_code' => 'NA', 'state_code' => $opsModel->role_value])->all();
            } else if ($opsModel->role_code == 'OPCTMG') {
                $branches = \app\models\Branches::find()->where('branch_code != :branch_code AND city_code = :city_code', ['branch_code' => 'NA', 'city_code' => $opsModel->role_value])->all();
            } else if ($opsModel->role_code == 'OPBRMG') {
                $branches = \app\models\Branches::find()->where('branch_code = :branch_code', ['branch_code' => $opsModel->role_value])->all();
            } else if ($opsModel->role_code == 'OPEXE') {
                $branches = \app\models\Branches::find()->where('branch_code = :branch_code', ['branch_code' => $opsModel->role_value])->all();
            }
        } else if ($userType == 6) {
            $saleModel = \app\models\SalesProfile::findOne(['sale_id' => $userId]);
            if ($saleModel->role_code == 'SLEXE' && $saleModel->role_value == 'ELHQ') {
                $branches = \app\models\Branches::find()->where('branch_code != :branch_code', ['branch_code' => 'NA'])->all();
            } else if ($saleModel->role_code == 'SLSTMG') {
                $branches = \app\models\Branches::find()->where('branch_code != :branch_code AND state_code = :state_code', ['branch_code' => 'NA', 'state_code' => $saleModel->role_value])->all();
            } else if ($saleModel->role_code == 'SLCTMG') {
                $branches = \app\models\Branches::find()->where('branch_code != :branch_code AND city_code = :city_code', ['branch_code' => 'NA', 'city_code' => $saleModel->role_value])->all();
            } else if ($saleModel->role_code == 'SLBRMG') {
                $branches = \app\models\Branches::find()->where('branch_code = :branch_code', ['branch_code' => $saleModel->role_value])->all();
            } else if ($saleModel->role_code == 'SLEXE') {
                $branches = \app\models\Branches::find()->where('branch_code = :branch_code', ['branch_code' => $saleModel->role_value])->all();
            }
        }

        $branchData = \yii\helpers\ArrayHelper::map($branches, 'branch_code', 'name');
        ?>

        <div class="states">
            <?= $form->field($model, 'branch_code')->dropDownList($branchData, ['prompt' => 'Select branch *'])->label(false); ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn savechanges_submit_contractaction']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
