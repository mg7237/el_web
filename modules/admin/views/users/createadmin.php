<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\UserTypes;
use app\models\Roles;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

$this->title = 'Create Internal User';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="users-create">

    <h1><?= Html::encode($this->title) ?></h1>


    <script src="//maps.googleapis.com/maps/api/js?key=AIzaSyDCFkcmtCqHONWxjbZn3bftmAqQ0A2YSKw&amp;libraries=places" type="text/javascript"></script>
    <script type="text/javascript">
        function initialize() {
            var input = document.getElementById('searchTextField');
            var autocomplete = new google.maps.places.Autocomplete(input);
            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                var place = autocomplete.getPlace();
                document.getElementById('users-lat').value = place.geometry.location.lat();
                document.getElementById('users-lon').value = place.geometry.location.lng();
            });
        }
        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
    <div class="users-form">

        <?php $form = ActiveForm::begin(); ?>
        <?php
        $countries = UserTypes::find()->where(['IN', 'id', ['6', '7']])->all();
        $listData = ArrayHelper::map($countries, 'id', 'user_type_name');
        ?>
        <?= $form->errorSummary($model); ?>
        <?= $form->field($model, 'user_type')->dropDownList($listData, ['prompt' => 'Select user type', 'url' => \Yii::$app->getUrlManager()->createUrl('admin/users/getroles')], []); ?>

        <?php
        $roles = Roles::find()->where(['user_type' => $model->user_type])->all();
        $listData = ArrayHelper::map($roles, 'id', 'role_name');
        ?>
        
        <?= $form->field($model, 'role')->dropDownList($listData, ['prompt' => 'Select role', 'url' => \Yii::$app->getUrlManager()->createUrl('admin/users/getstates')], []); ?>
        
        <?= $form->field($model, 'state')->dropDownList([], ['prompt' => 'Select state', 'url' => \Yii::$app->getUrlManager()->createUrl('admin/users/getcities')], []); ?>
        
        <?= $form->field($model, 'city')->dropDownList([], ['prompt' => 'Select city', 'url' => \Yii::$app->getUrlManager()->createUrl('admin/users/getbranches')], []); ?>
        
        <div id="users-branch-block" style="display: none;">
        <?= $form->field($model, 'branch')->dropDownList([], ['prompt' => 'Select branch'], []); ?>
        </div>
        
        <?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'login_id')->textInput(['maxlength' => true]) ?>

        <div id="phone_sales" style="display: none;">
            <?= $form->field($modelSales, 'phone')->textInput(['maxlength' => true]) ?>
        </div>
        <div id="operation_sales" style="display: none;">
            <?= $form->field($modelOperations, 'phone')->textInput(['maxlength' => true]) ?>
        </div>

        <?= $form->field($model, 'address_line_1')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'address_line_2')->textInput(['maxlength' => true]) ?>
        
        <?= $form->field($model, 'profile_image')->fileInput([]) ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
<script>
        $(document).ready(function () {
            $(document).on('change', '#users-user_type', function () {
                if ($(this).val() == 7) {
                    $('#operation_sales').css('display', 'block');
                    $('#phone_sales').css('display', 'none');
                } else if ($(this).val() == 6) {
                    $('#phone_sales').css('display', 'block');
                    $('#operation_sales').css('display', 'none');
                }
            });
        });
</script>