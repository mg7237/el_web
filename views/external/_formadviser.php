
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
</style>
<div class="modal-header">
    <button type="button" class="close closetext" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title modaltitletextnew">Create Property Advisor Lead</h4>

</div>
<div class="modal-dialog modal-dialognew">

    <div class="modal-body loginbody signupbody">
        <?php
        $form = ActiveForm::begin(['id' => 'adviserform', 'options' => [
                        'class' => 'forminputpopup'
                    ], 'enableAjaxValidation' => true]);
        ?>

        <?= $form->field($model, 'full_name')->textInput(['maxlength' => true, 'placeholder' => 'Advisor Name*'])->label(false); ?>

        <?= $form->field($model, 'email_id')->textInput(['maxlength' => true, 'placeholder' => 'Advisor Email Id*'])->label(false); ?>

        <?= $form->field($model, 'contact_number')->textInput(['maxlength' => 15, 'placeholder' => 'Advisor Contact Number*', 'class' => 'form-control '])->label(false); ?>

        <?= $form->field($model, 'communication')->textarea(['rows' => 3, 'placeholder' => 'Comments*'])->label(false); ?>

        <?= $form->field($model, 'address')->textInput(['maxlength' => true, 'placeholder' => 'Address line 1*'])->label(false); ?>
        <?= $form->field($model, 'address_line_2')->textInput(['maxlength' => true, 'placeholder' => 'Address line 2*'])->label(false); ?>





        <?php
        $userType = Yii::$app->user->identity->user_type;
        $userId = Yii::$app->user->identity->id;
        if ($userType == 7) {
            $opsModel = \app\models\OperationsProfile::findOne(['operations_id' => $userId]);
            if ($opsModel->role_code == 'OPEXE' && $opsModel->role_value == 'ELHQ') {
                $states = \app\models\States::find()->where(['status' => 1])->all();
            } else if ($opsModel->role_code == 'OPSTMG') {
                $states = \app\models\States::find()->where(['status' => 1, 'code' => $opsModel->role_value])->all();
            } else if ($opsModel->role_code == 'OPCTMG') {
                $states = \app\models\States::find()->where(['status' => 1, 'code' => Yii::$app->userdata->getStateCodeByCityCode($opsModel->role_value)])->all();
            } else if ($opsModel->role_code == 'OPBRMG') {
                $branchModel = \app\models\Branches::findOne(['branch_code' => $opsModel->role_value]);
                $states = \app\models\States::find()->where(['status' => 1, 'code' => Yii::$app->userdata->getStateCodeByCityCode($branchModel->city_code)])->all();
            } else if ($opsModel->role_code == 'OPEXE') {
                $branchModel = \app\models\Branches::findOne(['branch_code' => $opsModel->role_value]);
                $states = \app\models\States::find()->where(['status' => 1, 'code' => Yii::$app->userdata->getStateCodeByCityCode($branchModel->city_code)])->all();
            }
        } else if ($userType == 6) {
            $saleModel = \app\models\SalesProfile::findOne(['sale_id' => $userId]);
            if ($saleModel->role_code == 'SLEXE' && $saleModel->role_value == 'ELHQ') {
                $states = \app\models\States::find()->where(['status' => 1])->all();
            } else if ($saleModel->role_code == 'SLSTMG') {
                $states = \app\models\States::find()->where(['status' => 1, 'code' => $saleModel->role_value])->all();
            } else if ($saleModel->role_code == 'SLCTMG') {
                $states = \app\models\States::find()->where(['status' => 1, 'code' => Yii::$app->userdata->getStateCodeByCityCode($saleModel->role_value)])->all();
            } else if ($saleModel->role_code == 'SLBRMG') {
                $branchModel = \app\models\Branches::findOne(['branch_code' => $saleModel->role_value]);
                $states = \app\models\States::find()->where(['status' => 1, 'code' => Yii::$app->userdata->getStateCodeByCityCode($branchModel->city_code)])->all();
            } else if ($saleModel->role_code == 'SLEXE') {
                $branchModel = \app\models\Branches::findOne(['branch_code' => $saleModel->role_value]);
                $states = \app\models\States::find()->where(['status' => 1, 'code' => Yii::$app->userdata->getStateCodeByCityCode($branchModel->city_code)])->all();
            }
        }

        $stateData = \yii\helpers\ArrayHelper::map($states, 'code', 'name');
        ?>
        <div class="state">

            <?= $form->field($model, 'lead_state')->dropDownList($stateData, ['prompt' => 'Select State*', 'url' => \Yii::$app->getUrlManager()->createUrl('users/getcity')])->label(false); ?>
        </div>
        <?php
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

        $cityData = \yii\helpers\ArrayHelper::map($cities, 'code', 'city_name');
        ?>
        <div class="state">

            <?= $form->field($model, 'lead_city')->dropDownList($cityData, ['prompt' => 'Select City*', 'url' => \Yii::$app->getUrlManager()->createUrl('advisers/getregions')])->label(false); ?>
        </div>

        <?php
        if ($userType == 7) {
            $opsModel = \app\models\OperationsProfile::findOne(['operations_id' => $userId]);
            if ($opsModel->role_code == 'OPEXE' && $opsModel->role_value == 'ELHQ') {
                $branches = \app\models\Branches::find()->where(['status' => 1])->all();
            } else if ($opsModel->role_code == 'OPSTMG') {
                $branches = \app\models\Branches::find()->where(['status' => 1, 'state_code' => $opsModel->role_value])->all();
            } else if ($opsModel->role_code == 'OPCTMG') {
                $branches = \app\models\Branches::find()->where(['status' => 1, 'city_code' => $opsModel->role_value])->all();
            } else if ($opsModel->role_code == 'OPBRMG') {
                $branches = \app\models\Branches::find()->where(['status' => 1, 'branch_code' => $opsModel->role_value])->all();
            } else if ($opsModel->role_code == 'OPEXE') {
                $branches = \app\models\Branches::find()->where(['status' => 1, 'branch_code' => $opsModel->role_value])->all();
            }
        } else if ($userType == 6) {
            $saleModel = \app\models\SalesProfile::findOne(['sale_id' => $userId]);
            if ($saleModel->role_code == 'SLEXE' && $saleModel->role_value == 'ELHQ') {
                $branches = \app\models\Branches::find()->where(['status' => 1])->all();
            } else if ($saleModel->role_code == 'SLSTMG') {
                $branches = \app\models\Branches::find()->where(['status' => 1, 'state_code' => $saleModel->role_value])->all();
            } else if ($saleModel->role_code == 'SLCTMG') {
                $branches = \app\models\Branches::find()->where(['status' => 1, 'city_code' => $saleModel->role_value])->all();
            } else if ($saleModel->role_code == 'SLBRMG') {
                $branches = \app\models\Branches::find()->where(['status' => 1, 'branch_code' => $saleModel->role_value])->all();
            } else if ($saleModel->role_code == 'SLEXE') {
                $branches = \app\models\Branches::find()->where(['status' => 1, 'branch_code' => $saleModel->role_value])->all();
            }
        }

        $branchData = \yii\helpers\ArrayHelper::map($branches, 'branch_code', 'name');
        ?>

        <div class="state">

            <?= $form->field($model, 'branch_code')->dropDownList($branchData, ['prompt' => 'Select Branch*'])->label(false); ?>
        </div>

        <?= $form->field($model, 'pincode')->textInput(['maxlength' => 15, 'placeholder' => 'Pincode*'])->label(false); ?>



        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn savechanges_submit_contractaction']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
