<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'PMS - Maintenance Service Requests';
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="page-wrapper" class="autoheight">
    <div class="row">
        <div class="col-lg-12 page_title">
            <h3><?= Html::encode($this->title) ?></h3>

            <br />



            <?=
            GridView::widget([
                'dataProvider' => $dataProviderOwner,
                //'filterModel' => $searchModelOwnerLeads,
                'columns' => [
                    //['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'property_id',
                        'label' => 'Property Id',
                    //'filter' => false,
                    ],
                    [
                        'attribute' => 'created_date',
                        'label' => 'Creation Date/Updation Date',
                    //'filter' => false,
                    ],
                    [
                        'attribute' => 'status',
                        'label' => 'Status',
                        'filter' => false,
                        'value' => function($model, $key, $index, $column) {
                            return Yii::$app->userdata->getStatusNameById($model->status);
                        }
                    ],
                    [
                        'attribute' => 'charges_to_owner',
                        'label' => 'Charges'
                    ],
                    [
                        'attribute' => 'title',
                        'label' => 'Subject'
                    ],
                    [
                        'attribute' => 'payment_due_date',
                        'label' => 'Payment Due Date',
                        'value' => function($model) {
                            if ($model->payment_due_date != '1970-01-01') {
                                return Date('d-M-Y', strtotime($model->payment_due_date));
                            } else {
                                return '';
                            }
                        }
                    ]
                ],
            ]);
            ?>
        </div>
    </div>
</div>
