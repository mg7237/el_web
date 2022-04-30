<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
?>
<div id="page-wrapper">
    <div class="row">

        <div class="modal-body signupbody">

            <p>&nbsp;</p>


            <div class="row">
                <div class="col-lg-12">

                    <div class="col-lg-8 col-sm-8  col-xs-offset-1">


                        <div class="tenentdetaillistnew">
                            <ul>
                                <li>Employee ID* </li>
                                <li><?= $models->operations_id ?></li>
                                <li>Reporting Manager Id</li>
                                <li>007</li>
                            </ul>
                        </div>
                        <div class="tenentdetaillist">
                            <ul>
                                <li>Name</li>
                                <li><?= $users->full_name ?></li>
                            </ul>
                        </div>
                        <div class="tenentdetaillistnew">
                            <ul>            
                                <li>Phone Number</li>
                                <li><?= $models->phone ?></li>
                                <li>Email</li>
                                <li><?= $users->login_id ?></li>
                                <li>Emergency contact number</li>
                                <li><?= $models->emer_contact ?></li>
                                <li>Address</li>
                                <li><?= $models->address_line_1 ?></li>
                                <li>Address line 2</li>
                                <li><?= $models->address_line_2 ?></li>
                                <li>State</li>
                                <li><?= $state ?></li>
                                <li>City</li>
                                <li><?= $city ?></li>
                                <li>Zipcode</li>
                                <li><?= $models->pincode ?></li>
                            </ul>
                        </div>
                        <div class="approvalnew">



                            <div class="lineheightbg"></div>

                        </div>
                        <p>&nbsp;</p>
                        <div class="col-lg-12 areatext">
                            <p style="color: #347c17;font-weight: bold;">Branch list</p>

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Branch code</th>
                                        <th>Branch name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($branches as $key => $value) {
                                        ?>
                                        <tr>
                                            <td>B-<?= $value->id ?></td>
                                            <th scope="row"><?= $value->name ?> </th>
                                        </tr>

                                        <?php
                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                        <a style="margin-top:15px;" href="../../site/changepassword" data-target="#myModal" data-toggle="modal" class="btn btn-info">Reset password</a>
                    </div>
                    <div class="col-xs-2 col-md-2 col-sm-3 rightpfrolietop" style="margin-left:35px;">
                        <a href="#" class="thumbnail">
                            <?php
                            if (trim($models->profile_image) != '') {
                                ?>
                                <img src="<?= $models->profile_image ?>" alt="...">
                                <?php
                            } else {
                                ?>
                                <img src="" alt="...">
                                <?php
                            }
                            ?>

                        </a>
                    </div>



                </div>




            </div>
            <p>&nbsp;</p>
        </div>


    </div>

    <div class="modal fade" id="myModal" role="dialog" style="display: none;">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal">×</button>

                </div>


                <div class="changepassword">
                    <h1>Change password</h1>

                    <?php
                    $form = ActiveForm::begin([
                                'id' => 'changepassword-form',
                                'enableAjaxValidation' => true,
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
                            'placeholder' => 'Old Password', 'class' => 'form-control'
                ]])->passwordInput();
                    ?>

                    <?=
                    $form->field($model, 'newpass', ['inputOptions' => [
                            'placeholder' => 'New Password', 'class' => 'form-control'
                ]])->passwordInput();
                    ?>

                    <?=
                    $form->field($model, 'repeatnewpass', ['inputOptions' => [
                            'placeholder' => 'Repeat New Password', 'class' => 'form-control'
                ]])->passwordInput();
                    ?>
                    <div class="clearfix"> </div>           

                    <div class="modal-footer">
                        <div class="col-md-6">
                            <?=
                            Html::submitButton('Save', [
                                'class' => 'btn btn-success btn btn-primary btn-md savechanges_submit_contractaction'
                            ])
                            ?>
                        </div>
                        <div class="col-md-6">
                            <button data-dismiss="modal" class="btn btn-default savechanges_submit " type="button">Close</button>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div></div>
        </div>



    </div>






</div>


<script src="/pms/web/assets/a140edba/jquery.js"></script>
<script src="/pms/web/assets/566bb936/yii.js"></script>
<script src="/pms/web/assets/566bb936/yii.validation.js"></script>
<script src="/pms/web/assets/566bb936/yii.activeForm.js"></script>
<script type="text/javascript">jQuery('#changepassword-form').yiiActiveForm([{"id":"passwordform-oldpass", "name":"oldpass", "container":".field-passwordform-oldpass", "input":"#passwordform-oldpass", "error":".help-block.help-block-error", "enableAjaxValidation":true, "validate":function (attribute, value, messages, deferred, $form) {yii.validation.required(value, messages, {"message":"Old Password cannot be blank."}); }}, {"id":"passwordform-newpass", "name":"newpass", "container":".field-passwordform-newpass", "input":"#passwordform-newpass", "error":".help-block.help-block-error", "enableAjaxValidation":true, "validate":function (attribute, value, messages, deferred, $form) {yii.validation.required(value, messages, {"message":"New Password cannot be blank."}); yii.validation.string(value, messages, {"message":"New Password must be a string.", "min":6, "tooShort":"New Password should contain at least 6 characters.", "max":12, "tooLong":"New Password should contain at most 12 characters.", "skipOnEmpty":1}); }}, {"id":"passwordform-repeatnewpass", "name":"repeatnewpass", "container":".field-passwordform-repeatnewpass", "input":"#passwordform-repeatnewpass", "error":".help-block.help-block-error", "enableAjaxValidation":true, "validate":function (attribute, value, messages, deferred, $form) {yii.validation.required(value, messages, {"message":"Repeat New Password cannot be blank."}); yii.validation.compare(value, messages, {"operator":"==", "type":"string", "compareAttribute":"passwordform-newpass", "skipOnEmpty":1, "message":"Repeat New Password must be equal to \"New Password\"."}); }}], []);</script>