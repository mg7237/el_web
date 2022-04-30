<?php
$this->title = 'PMS- Advisor Leads';

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
    a.btn.savechanges_submit_contractaction.rightbtn {
        width: auto;
        padding: 2px 13px;
    }
</style>
<div>

    <div id="page-wrapper">

        <div class="row">


            <div class="row">
                <div class="col-lg-12 button_lead">

                </div>
                <?php
                $currProType = Yii::$app->user->identity->user_type;
                $currProId = Yii::$app->user->id;
                if ($currProType == 7) {
                    $currProModel = \app\models\OperationsProfile::find()->where(['operations_id' => $currProId])->one();
                } else if ($currProType == 6) {
                    $currProModel = \app\models\SalesProfile::find()->where(['sale_id' => $currProId])->one();
                }
                ?>

                <?php if ($currProModel->role_code != 'OPSTMG' && $currProModel->role_code != 'SLSTMG') { ?>
                    <div class="col-lg-4 col-sm-6 pull-right">
                        <a href="<?php echo Url::home(true); ?>external/createadviser" class="btn savechanges_submit_contractaction rightbtn" data-toggle="modal" data-target="#myModal" style= "margin-top:15px;">Create Advisor Leads</a>

                    </div>
                <?php } ?>

                <div class="col-lg-12 col-sm-12">
                    <div class="col-lg-3 col-sm-5 selectleft"> <a class="btn btn-default btn-select">
                            <input type="hidden" value="Sort by" name="" id="" class="btn-select-input">
                            <span class="btn-select-value">Sort by</span> <span class="btn-select-arrow glyphicon glyphicon-chevron-down"></span>
                            <ul style="display: none;" class="sort_ul">
                                <li class="selected">Sort by</li>
                                <li value="users.full_name">Advisor Name</li>
                                <li value="leads_advisor.address">Address</li>
                                <li value="leads_advisor.schedule_date_time">Schedule Date/Time</li>
                                <li value="leads_advisor.follow_up_date_time">Next Follow Up Date/Time</li>
                                <!-- <li>Status</li> -->
                            </ul>
                        </a> </div>
                    <div class="col-lg-8 col-sm-6 selectleft">
                        <form class="navbar-form navbar-search" action="" method="GET" role="search">
                            <div class="input-group">
                                <?php
                                $search = '';
                                if (isset($_REQUEST['LeadsAdvisor']['search'])) {
                                    $search = $_REQUEST['LeadsAdvisor']['search'];
                                }
                                ?>
                                <input type="hidden" name="LeadsTenant[sort]" id="sort_val" value="<?php echo $sort; ?>">
                                <input type="text" name="LeadsAdvisor[search]" class="form-control" value="<?= $search; ?>" placeholder="Search By Advisor Name, Advisor Email, Advisor Phone Number, Address">
                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-search btn-danger selecttext"> <span class="glyphicon glyphicon-search"></span> <span class="label-icon"></span> </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-lg-12 col-sm-12 advisortetxt">
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
                            return array('key' => $key, 'data-href' => Url::home(true) . 'external/advisersdetails?id=' . base64_encode($key), 'class' => $class);
                        },
                        'summary' => '',
                        'tableOptions' => ['class' => 'table table-bordered tableheadingrow', 'id' => 'ExportTable'],
                        'columns' => [
                            //   ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute' => 'full_name',
                                'label' => 'Advisor name',
                            ],
                            [
                                'attribute' => 'address',
                                'label' => 'Location/Address of property Advisor',
                                'format' => 'html',
                                'content' => function($model) {
                                    return $model->address . ',' . $model->address_line_2 . ',' . Yii::$app->userdata->getStateNameByStateCode($model->lead_state) . ',' . Yii::$app->userdata->getCityNameByCityCode($model->lead_city);
                                }
                            ],
                            [
                                'attribute' => 'ref_status',
                                'label' => 'Status',
                                'format' => 'html',
                                'content' => function($model) {
                                    return Yii::$app->userdata->getRefStatusById($model->ref_status);
                                }
                            ],
                            [
                                'label' => 'Schedule date/Time',
                                'format' => 'html',
                                'content' => function($model) {
                                    if (date('d-M-Y', strtotime($model->schedule_date_time)) != '01-Jan-1970') {
                                        return date('d-M-Y', strtotime($model->schedule_date_time)) . ' <br />' . date('g:i a', strtotime($model->schedule_date_time));
                                    }
                                }
                            ],
                            [
                                'label' => 'Next follow up date and time',
                                'format' => 'html',
                                'content' => function($model) {
                                    if (date('d-M-Y', strtotime($model->follow_up_date_time)) != '01-Jan-1970') {
                                        return date('d-M-Y', strtotime($model->follow_up_date_time)) . ' <br />' . date('g:i a', strtotime($model->follow_up_date_time));
                                    }
                                }
                            ],
                            [
                                'label' => 'Assigned Sales Person Id',
                                'format' => 'html',
                                'content' => function($model) {
                                    return Yii::$app->userdata->getLoginIdById(Yii::$app->userdata->getSalesFullNameById(5, $model->email_id));
                                }
                            ],
                        //   ['class' => 'yii\grid\ActionColumn'],
                        ]
                    ]);
                    ?>

                    <div class="col-lg-4"><button type="button" class="btn savechanges_submit_contractaction" id="btnExport">Export</button></div>
                </div>

                <div style="clear:both;"></div>

                <!-- /.col-lg-12 -->
            </div>
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
