<?php
$this->title = 'PMS- Adhoc Requests';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\grid\GridView;

// echo "<pre>";
// print_r($dataProviderOwner);
// die;

if (isset($_GET['LeadsTenant']['sort'])) {
    $sort = $_GET['LeadsTenant']['sort'];
} else {
    $sort = '';
}
?>
<style>
    .filters{
        display:none ; 
    }
</style>
<div class="col-lg-12 selectright">

    <div id="page-wrapper">

        <div class="row">


            <div class="row">
                <div class="col-lg-12 button_lead">

                </div>
                <div class="col-lg-4 pull-right">
                    <a href="<?php echo Url::home(true); ?>operations/createadhoc" class="btn savechanges_submit_contractaction" data-toggle="modal" data-target="#myModal" style= "margin-top:15px;">Create Adhoc Request</a>

                </div>
                <div class="col-lg-12">
                    <!-- <div class="col-lg-3 selectleft"> <a class="btn btn-default btn-select">
                      <input type="hidden" value="Sort by" name="" id="" class="btn-select-input">
                      <span class="btn-select-value">Sort by</span> <span class="btn-select-arrow glyphicon glyphicon-chevron-down"></span>
                      <ul style="display: none;" class="sort_ul">
                        <li class="selected">Sort by</li>
                        <li value="users.full_name">Advisor Name</li>
                        <li value="advisor_profile.address_line_1">Address</li>
                        <li value="leads_Advisor.schedule_date_time">Schedule Date/Time</li>
                        <li value="leads_advisor.follow_up_date_time">Next Follow Up Date/Time</li>
                      </ul>
                      </a> </div> -->
                    <div class="col-lg-8 selectleft">
                        <form class="navbar-form navbar-search" action="" method="GET" role="search">
                            <div class="input-group">
                                <?php
                                $search = '';
                                if (isset($_REQUEST['Request']['search'])) {
                                    $search = $_REQUEST['Request']['search'];
                                }
                                ?>
                           <!-- <input type="hidden" name="Request[sort]" id="sort_val" value="<?php // echo $sort;    ?>"> -->
                                <input type="text" name="Request[search]" class="form-control" value="<?= $search; ?>" placeholder="Search By Tenant Name, Title">
                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-search btn-danger selecttext"> <span class="glyphicon glyphicon-search"></span> <span class="label-icon"></span> </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-lg-12 advisortetxt">
                    <?php if (Yii::$app->session->hasFlash('success')) { ?>
                        <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                            <?= Yii::$app->session->getFlash('success'); ?>
                        </div>   
                    <?php } ?>


                    <?php
                    echo GridView::widget([
                        'dataProvider' => $dataProviderOwner,
                        'filterModel' => $searchModelOwnerLeads,
                        'rowOptions' => function ($model, $key, $index, $grid) {
                            $class = 'clickable-row';
                            return array('key' => $key, 'data-href' => Url::home(true) . 'operations/adhocdetails?id=' . base64_encode($key), 'class' => $class);
                        },
                        'summary' => '',
                        'tableOptions' => ['class' => 'table table-bordered tableheadingrow'],
                        'columns' => [
                            //   ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute' => 'tenant_id',
                                'label' => 'Tenant Id',
                            ],
                            [
                                'attribute' => 'tenant_name',
                                'label' => 'Tenant Name',
                                'format' => 'html',
                                'content' => function($model) {
                                    return Yii::$app->userdata->getFullNameById($model->tenant_id);
                                }
                            ],
                            [
                                'attribute' => 'title',
                                'label' => 'Title',
                            ],
                            [
                                'attribute' => 'created_date',
                                'label' => 'Creation Date',
                                'format' => 'html',
                                'content' => function($model) {
                                    return Date('d-m-Y', strtotime($model->created_date));
                                }
                            ],
                            [
                                'attribute' => 'request_status',
                                'label' => 'Request Status',
                                'format' => 'html',
                                'content' => function($model) {
                                    switch ($model->payment_status) {
                                        case 0:
                                            return 'Unpaid';
                                            break;

                                        case 1:
                                            return 'Paid';
                                            break;

                                        default:
                                            return '';
                                            break;
                                    }
                                }
                            ],
                            [
                                'attribute' => 'actual_charge',
                                'label' => 'Actual Charge',
                                'format' => 'html',
                                'content' => function($model) {
                                    return 'Rs. ' . $model->actual_charge;
                                }
                            ],
                            [
                                'attribute' => 'charge_to_tenant',
                                'label' => 'Charge To Tenant',
                                'format' => 'html',
                                'content' => function($model) {
                                    return 'Rs. ' . $model->charge_to_tenant;
                                }
                            ],
                            [
                                'attribute' => 'payment_due_date',
                                'label' => 'Payment Due Date',
                                'format' => 'html',
                                'content' => function($model) {
                                    return Date('d-m-Y', strtotime($model->payment_due_date));
                                }
                            ],
                        // [
                        //   'attribute'=>'address',
                        //   'label'=>'Location/Address of property Advisor',
                        //   'format'=>'html',
                        //   'content'=>function($model){
                        //     return $model->address;
                        //   }
                        // ],
                        //  [
                        //   'attribute'=>'ref_status',
                        //   'label'=>'Status',
                        //   'format'=>'html',
                        //   'content'=>function($model){
                        //     return \app\controllers\SiteController::refType($model->ref_status);
                        //   }
                        // ],
                        //  [
                        //   'label'=>'Schedule date/Time',
                        //   'format'=>'html',
                        //   'content'=>function($model){
                        //     return date('d-m-Y',strtotime( $model->schedule_date_time )) .' <br />'. date('g:i a', strtotime( $model->schedule_date_time ));
                        //   }
                        // ],
                        //  [
                        //   'label'=>'Next follow up date and time',
                        //   'format'=>'html',
                        //   'content'=>function($model){
                        //     return date('d-m-Y',strtotime( $model->follow_up_date_time )) .' <br />'. date('g:i a', strtotime( $model->follow_up_date_time ));
                        //   }
                        // ],
                        // [
                        //   'label'=>'Assigned Sales Person Id',
                        //   'format'=>'html',
                        //   'content'=>function($model){
                        //     return  Yii::$app->userdata->getLoginIdById(Yii::$app->userdata->getSalesFullNameById(5,$model->email_id ));
                        //   }
                        // ],
                        //   ['class' => 'yii\grid\ActionColumn'],
                        ]
                    ]);
                    ?>

                    <div class="col-lg-4"><button type="button" class="btn savechanges_submit">Export</button></div>
                </div>

                <div style="clear:both;"></div>

                <!-- /.col-lg-12 -->
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modalbg modalbgpopup" style="width:400px;">

        <!-- Modal content-->
        <div class="modal-content">


        </div>

    </div>
</div>


