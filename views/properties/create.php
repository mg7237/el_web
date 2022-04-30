<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Properties */

$this->title = 'Create Properties';
$this->params['breadcrumbs'][] = ['label' => 'Properties', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="signup_conatiner">
    <div class="" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header loginheader">
                    <h4 class="modal-title modal_signin signuptext" id="myModalLabel"><?= Html::encode($this->title) ?> </h4>
                </div>
                <div class="modal-body ">


                    <?=
                    $this->render('_form', [
                        'model' => $model, 'modelAddress' => $modelAddress, 'modelAgreements' => $modelAgreements, 'modelImages' => $modelImages, 'modelPropertiesAttributes' => $modelPropertiesAttributes, 'propertyAttributeMap' => $propertyAttributeMap, 'modelPropertiesAttributesInputs' => $modelPropertiesAttributesInputs
                    ])
                    ?>

                </div>


            </div>
        </div>
    </div>
</div>

