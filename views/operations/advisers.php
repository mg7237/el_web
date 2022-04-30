<?php
$this->title = 'PMS- Adviser Leads';

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
                    <a href="<?php echo Url::home(true); ?>operations/createadviser" class="btn savechanges_submit_contractaction" data-toggle="modal" data-target="#myModal" style= "margin-top:15px;">Create Advisers</a>

                </div>
                <div class="col-lg-12">
                    <div class="col-lg-3 selectleft"> <a class="btn btn-default btn-select">
                            <input type="hidden" value="Sort by" name="" id="" class="btn-select-input">
                            <span class="btn-select-value">Sort by</span> <span class="btn-select-arrow glyphicon glyphicon-chevron-down"></span>
                            <ul style="display: none;" class="sort_ul">
                                <li class="selected">Sort by</li>
                                <li value="users.full_name">Advisor Name</li>
                                <li value="advisor_profile.address_line_1">Address</li>
                                <li value="leads_Advisor.schedule_date_time">Schedule Date/Time</li>
                                <li value="leads_advisor.follow_up_date_time">Next Follow Up Date/Time</li>
                                <!-- <li>Status</li> -->
                            </ul>
                        </a> </div>
                    <div class="col-lg-8 selectleft">
                        <form class="navbar-form navbar-search" action="" method="GET" role="search">
                            <div class="input-group">
                                <?php
                                $search = '';
                                if (isset($_REQUEST['LeadsAdvisor']['search'])) {
                                    $search = $_REQUEST['LeadsAdvisor']['search'];
                                }
                                ?>
                                <input type="hidden" name="LeadsTenant[sort]" id="sort_val" value="<?php echo $sort; ?>">
                                <input type="text" name="LeadsAdvisor[search]" class="form-control" value="<?= $search; ?>" placeholder="Search by Advisor name, Advisor email, Advisor phone number, address, status">
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
                            return array('key' => $key, 'data-href' => Url::home(true) . 'operations/advisersdetails?id=' . base64_encode($key), 'class' => $class);
                        },
                        'summary' => '',
                        'tableOptions' => ['class' => 'table table-bordered tableheadingrow'],
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
                                    return $model->address;
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
                                    return date('d-m-Y', strtotime($model->schedule_date_time)) . ' <br />' . date('g:i a', strtotime($model->schedule_date_time));
                                }
                            ],
                            [
                                'label' => 'Next follow up date and time',
                                'format' => 'html',
                                'content' => function($model) {
                                    return date('d-m-Y', strtotime($model->follow_up_date_time)) . ' <br />' . date('g:i a', strtotime($model->follow_up_date_time));
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

                    <div class="col-lg-4"><button type="button" class="btn savechanges_submit">Export</button></div>
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


