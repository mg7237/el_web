<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\UserTypes;
use app\models\Roles;
use app\models\States;
use app\models\Cities;
use app\models\Branches;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model app\models\Users */

$this->title = 'Update Admin User';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="users-create">

    <h1><?= Html::encode($this->title) ?></h1>

<div class="users-form">

    <?php $form = ActiveForm::begin(); 
    echo  $form->errorSummary($model);
    ?>
    <?php
    $countries = UserTypes::find()->where(['IN', 'id', ['6', '7']])->all();
    $listData = ArrayHelper::map($countries, 'id', 'user_type_name');
    ?>
    <?= $form->errorSummary($model); ?>
    <?= $form->field($model, 'user_type')->dropDownList($listData, ['prompt' => 'Select user type', 'url' => \Yii::$app->getUrlManager()->createUrl('admin/users/getroles')], []); ?>	
	
    <?php
    $roles = Roles::find()->where(['user_type' => $model->user_type])->all();
    $listData = ArrayHelper::map($roles, 'role_code', 'role_name');
    ?>
    
    <?= $form->field($model, 'role')->dropDownList($listData, ['prompt' => 'Select role', 'url' => \Yii::$app->getUrlManager()->createUrl('admin/users/getstates')], []); ?>
    
    <?php   
    //$state = States::find()->where(['code' => $profile->role_value])->all();
    $state = States::find()->all();
    $listData = ArrayHelper::map($state, 'code', 'name');
    
    ?>
    
    <div class="form-group field-Usersupdate-state">
        <label class="control-label" for="Usersupdate-state">State</label>
        <select id="usersupdate-state" class="form-control" name="Usersupdate[state]" url="/pms/web/admin/users/getcities">
            <option value="">Select state</option>
            <?php foreach ($listData as $key => $value) { ?>
                <option <?php if ($profile->state == $key) { echo 'selected';} ?> value="<?= $key ?>"><?= $value ?></option>
            <?php } ?>
        </select>
        <div class="help-block"></div>
    </div>
    
    <div class="form-group field-Usersupdate-city">
        <label class="control-label" for="Usersupdate-city">City</label>
        <select id="usersupdate-city" class="form-control" name="Usersupdate[city]" url="/pms/web/admin/users/getbranches">
            <option value="">Select city</option>
            <option selected="selected" value="<?= ($profile->city) ?>"><?= Yii::$app->userdata->getCityNameByCode($profile->city) ?></option>
        </select>
        <div class="help-block"></div>
    </div>
    
    <?php
        $branchModel = \app\models\Branches::find()->where(['state_code' => $profile->state, 'city_code' => $profile->city])->one();
    ?>
    
    <?php if ($profile->role_code == 'SLEXE' || $profile->role_code == 'SLBRMG') { ?>
        <div id="usersupdate-branch-block" style="">
            <div class="form-group field-Usersupdate-branch">
                <label class="control-label" for="users-branch">Branch</label>
                <select id="usersupdate-branch" class="form-control" name="Usersupdate[branch]">
                    <option value="">Select branch</option>
                    
                    <option selected="" value="<?= $profile->role_value ?>"><?= $branchModel->name; ?></option>
                </select>
                <div class="help-block"></div>
            </div>
        </div>
    <?php } else if ($profile->role_code == 'OPEXE' || $profile->role_code == 'OPBRMG') { ?>
        <div id="usersupdate-branch-block" style="">
            <div class="form-group field-Usersupdate-branch">
                <label class="control-label" for="users-branch">Branch</label>
                <select id="usersupdate-branch" class="form-control" name="Usersupdate[branch]">
                    <option value="">Select branch</option>
                    <option selected="" value="<?= $profile->role_value ?>"><?= $branchModel->name; ?></option>
                </select>
                <div class="help-block"></div>
            </div>
        </div>
    <?php } else { ?>

        <div id="usersupdate-branch-block" style="display: none;">
            <div class="form-group field-Usersupdate-branch">
                <label class="control-label" for="users-branch">Branch</label>
                <select id="usersupdate-branch" class="form-control" name="Usersupdate[branch]">
                    <option value="">Select branch</option>
                </select>
                <div class="help-block"></div>
            </div>
        </div>
    <?php } ?>
    
<!--    <div class="form-group field-usersupdate-role required">
        <label class="control-label" for="usersupdate-role">Role</label>
        <select id="usersupdate-role" class="form-control" name="Usersupdate[role]" url="/pms/web/admin/users/getstates" aria-required="true">
            <option value="">Select role</option>
            <option value="OPEXE">Operations Executive</option>
            <option value="OPBRMG">Operations Branch Manager</option>
            <option value="OPCTMG">Operations City Manager</option>
            <option value="OPSTMG" selected="">Operations State Manager</option>
        </select>

        <div class="help-block"></div>
    </div>-->
    
    <?= $form->field($model, 'full_name')->textInput(['maxlength' => true,'class'=>'isCharacter form-control']) ?>

    <?= $form->field($model, 'login_id')->textInput(['maxlength' => true]) ?>
    <?= $form->field($modelSales, 'phone')->textInput(['maxlength' => true,'class'=>'isPhone form-control']) ?>

    <?= $form->field($model, 'address_line_1')->textInput(['value' => $profile->address_line_1, 'maxlength' => true]) ?>

    <?= $form->field($model, 'address_line_2')->textInput(['value' => $profile->address_line_2, 'maxlength' => true]) ?>
    <div style="width: 100%">
        <img style="width: 200px;height: 200px;" src="<?php echo yii\helpers\Url::home(true).$modelSales->profile_image; ?>" />
    </div>
    <?= $form->field($model, 'profile_image')->fileInput([]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


</div>
