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
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12 formtext">
            <h1><?= Html::encode($this->title) ?></h1>

            <br />

            <div class="col-lg-12">
                <!-- <div class="col-lg-2 selectleft"> -->
                <!-- <a class="btn btn-default btn-select"> -->
                 <!-- <input type="hidden" class="btn-select-input" id="" name="" value="" /> -->
                 <!-- <span class="btn-select-value">Sort by</span> <span class='btn-select-arrow glyphicon glyphicon-chevron-down'></span> -->
                <!-- <ul> -->
                <!-- <li class="selected">Sort by</li> -->
                <!-- <li>Owner ID</li> -->
                <!-- <li>Owner Name</li> -->
                <!-- <li>subject/title</li> -->
                 <!-- <li>Status<</li> -->
                <!-- </ul> -->
                <!-- </a> -->
                <!-- </div> -->
                <div class="col-lg-7 col-sm-7 select_select">
                    <form class="navbar-form navbar-search" role="search">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" value="<?= (isset($_REQUEST['search'])) ? $_REQUEST['search'] : '' ?>" placeholder="Name, Subject, Status">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-search btn-danger selecttext"> <span class="glyphicon glyphicon-search"></span> <span class="label-icon"></span> </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-4 col-sm-5 button_leads">
                    <a href="<?php echo Url::home(true); ?>operations/createmaintenance" class="btn savechanges_submit" data-toggle="modal" data-target="#myModal" style= "margin-top:15px;">Create new request</a>
                </div>
            </div>

            <?=
            GridView::widget([
                'dataProvider' => $dataProviderOwner,
                //'filterModel' => $searchModelOwnerLeads,
                'rowOptions' => function ($model, $key, $index, $grid) {
                    $class = 'clickable-row';
                    return array('key' => $key, 'data-href' => Url::home(true) . 'operations/maintenancedetails?id=' . base64_encode($model->id), 'class' => $class);
                },
                'columns' => [
                    //['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'property_id',
                        'label' => 'Owner Id',
                        'value' => function($model) {
                            return Yii::$app->userdata->getOwnerIdByProperty($model->property_id);
                        }
                    //'filter' => false,
                    ],
                    [
                        'attribute' => 'property_id',
                        'label' => 'Owner Name',
                        'value' => function($model) {
                            return Yii::$app->userdata->getUserNameByProperty($model->property_id);
                        }
                    ],
                    [
                        'attribute' => 'property_id',
                        'label' => 'Property Id',
                    //'filter' => false,
                    ],
                    [
                        'attribute' => 'title',
                        'label' => 'Subject'
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
                        'attribute' => 'charges',
                        'label' => 'Actual Charges',
                    ],
                    [
                        'attribute' => 'charges_to_owner',
                        'label' => 'Charges To Owner'
                    ],
                    [
                        'attribute' => 'created_date',
                        'label' => 'Creation Date/Updation Date',
                    //'filter' => false,
                    ],
                    [
                        'attribute' => 'payment_due_date',
                        'label' => 'Payment Due Date',
                        'value' => function($model) {
                            if ($model->payment_due_date != '0000-00-00') {
                                return Date('d/m/Y', strtotime($model->payment_due_date));
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




<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modalbg modalbgpopup">

        <!-- Modal content-->
        <div class="modal-content">


        </div>

    </div>
</div>