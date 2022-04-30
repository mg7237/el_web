<?php
$this->title = 'PMS- My profile';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">

            <div class="col-lg-8  col-xs-offset-1">
                <p>&nbsp;</p>

                <div class="tenentdetaillistnew borderbottom">
                    <ul>
                        <li>Sales Person id</li>
                        <li><?= $model->id; ?></li>
                    </ul>
                </div>
                <div class="tenentdetaillist">
                    <ul>
                        <li>Name</li>
                        <li><?= $model->users->full_name; ?></li>
                    </ul>
                </div>
                <div class="tenentdetaillistnew">
                    <ul>
                        <li>Email</li>
                        <li><?= $model->users->login_id; ?></li>
                        <li>Contact</li>
                        <li><?= $model->phone; ?></li>
                        <li>Address</li>
                        <li>#<?= $model->address_line_1 . ' ' . $model->address_line_2; ?>, <?= Yii::$app->userdata->getCityName($model->city); ?>, <?= Yii::$app->userdata->getStateName($model->state); ?></li>
                        <li>Reporting Manager ID</li>
                        <li>1335600</li>
                    </ul>
                </div>
                <div class="approvalnew">

                    <form class="form-inline">
                        <!-------------------------------------------->
                        <div class="row"> 
                            <a style="margin-top:15px;" href="<?= Url::home(true) ?>site/changepassword" data-target="#myModal" data-toggle="modal" class="btn btn-info">Reset password</a>

                            <div class="modal fade" id="myModal" role="dialog">
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content" style="padding: 20px;">

                                    </div>
                                </div>
                            </div>





                        </div>


                </div>














                <div class="col-lg-12 areatext">
                    <p>Branch list</p>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Branch code</th>
                                <th>Branch name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($assinee as $key => $value) {
                                ?>
                                <tr>
                                    <td><?= $value->id ?></td>
                                    <th scope="row"><?= $value->name ?></th>
                                </tr>
                                <?php
                            }
                            ?>


                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-xs-2 col-md-2 rightpfrolietop">
                <a href="#" class="thumbnail">
                    <img class= "preview" src="<?= !empty($model->profile_image) ? $model->profile_image : Url::home(true) . 'images/photo.jpg' ?>" alt="...">
                </a>
            </div>
        </div>


    </div>

    <!-- /.col-lg-12 -->
</div>


