<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Change Password';
$this->params['breadcrumbs'][] = $this->title;
?>

<div id="page-wrapper" class= "autoheight">
    <div class="row">
        <div class="" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header loginheader">
                        <h4 class="modal-title modal_signin signuptext" id="myModalLabel"><?= Html::encode($this->title) ?> </h4>
                    </div>
                    <div class="modal-body loginbody signupbody">




                        <?php if (Yii::$app->session->hasFlash('success')) { ?>
                            <div class="alert alert-success">
                                <?= Yii::$app->session->getFlash('success'); ?>
                            </div>   
                        <?php }
                        ?>

                        <p>Please fill out the following fields to change password :</p>

                        <?php
                        $form = ActiveForm::begin([
                                    'id' => 'changepassword-form',
                                    'options' => ['class' => 'form-horizontal'],
                                    'fieldConfig' => [
                                    /*    'template'=>"{label}\n<div class=\"col-lg-3\">
                                      {input}</div>\n<div class=\"col-lg-5\">
                                      {error}</div>",
                                      'labelOptions'=>['class'=>'col-lg-2 control-label'], */
                                    ],
                        ]);
                        ?>
                        <?=
                        $form->field($model, 'oldpass', ['inputOptions' => [
                                'placeholder' => 'Old Password*'
                    ]])->passwordInput()->label(false);
                        ?>

                        <?=
                        $form->field($model, 'newpass', ['inputOptions' => [
                                'placeholder' => 'New Password*'
                    ]])->passwordInput()->label(false);
                        ?>

                        <?=
                        $form->field($model, 'repeatnewpass', ['inputOptions' => [
                                'placeholder' => 'Repeat New Password*'
                    ]])->passwordInput()->label(false);
                        ?>

                        <div class="form-group">

                            <?=
                            Html::submitButton('Change password', [
                                'class' => 'btn savechanges_submit_login'
                            ])
                            ?>

                        </div>
                        <?php ActiveForm::end(); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
