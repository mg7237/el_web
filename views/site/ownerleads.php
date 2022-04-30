<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tenant Onbording Status - ' . Yii::$app->userdata->getPropertyNameById($property_id);
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="page-wrapper" class="autoheight">
    <div class="row">
        <div class="col-lg-12">
            <div class= "page_title">
                <h3><?= Html::encode($this->title) ?></h3>
            </div>

            <br />



            <table class="table table-bordered " cellpadding="5"> 
                <thead>
                    <tr> 
                        <th>Date</th>
                        <th>Applicant Name</th>
                        <th>Lead Status</th>
                        <th>Sales Person Remark</th>
                        <th>Sales Person Id</th>
                    </tr>
                </thead> 
                <tbody>
                    <?php
                    if (count($dataProvider) != 0) {
                        foreach ($dataProvider as $data) {
                            ?>
                            <tr>
                                <td><?= date('d-M-Y', strtotime($data->created_date)) ?></td>
                                <td><?= Yii::$app->userdata->getUserNameByEmail($data->applicant_id) ?></td>
                                <td><?= Yii::$app->userdata->getRefStatusByUserId($data->applicant_id, 2) ?></td>
                                <td><?= Yii::$app->userdata->getSalesRemark(Yii::$app->userdata->getUserIdByEmail($data->applicant_id), $property_id) ?></td>
                                <td><?= Yii::$app->userdata->getSalesEmailById($data->applicant_id) ?></td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr> <td colspan="6"> No Request found</td></tr>
                        <?php
                    }
                    ?>  



                </tbody> 
            </table>
        </div>
    </div>
</div>
