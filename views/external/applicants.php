<?php
$this->title = 'PMS- Applicant Leads';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\grid\GridView;

/* echo "<pre>";
  print_r($model);
  die; */

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
    .pull-right {
        float: right !important;
        text-align: right;
    }
    .pull-right a.btn.savechanges_submit {
        width: auto;
        padding: 2px 13px;
    }
</style>
<div id="page-wrapper">
    <div class="row">

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
            <div class="col-lg-4 col-sm-7 pull-right">
                <a href="<?php echo Url::home(true); ?>external/createapplicant" class="btn savechanges_submit" data-toggle="modal" data-target="#myModal2" style= "margin-top:15px;">Create Applicant Lead</a>
            </div>
        <?php } ?>

        <div class="col-lg-12 col-sm-12">
            <?php if (Yii::$app->session->hasFlash('success')) { ?>
                <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                    <?= Yii::$app->session->getFlash('success'); ?>
                </div>   
            <?php } ?>	

            <div class="col-lg-3 col-sm-6 selectleft"> <a class="btn btn-default btn-select">
                    <input type="hidden" class="btn-select-input" id="" name="" value="" />
                    <span class="btn-select-value">Sort by</span> <span class='btn-select-arrow glyphicon glyphicon-chevron-down'></span>
                    <ul class="sort_ul">
                        <li class="selected">Sort by</li>
                        <li value="users.full_name">Applicant name</li>
                        <li value="users.login_id">Applicant email</li>
                        <li value="leads_tenant.contact_number">Applicant phone number</li>
                        <li value="applicant_profile.address_line_1">Address</li>
                    </ul>
                </a> </div>
            <div class="col-lg-8 col-sm-5 selectleft">
                <form class="navbar-form navbar-search" action="" method="GET" role="search">
                    <div class="input-group">
                        <?php
                        $search = '';
                        if (isset($_REQUEST['LeadsTenant']['search'])) {
                            $search = $_REQUEST['LeadsTenant']['search'];
                        }
                        ?>
                        <input type="hidden" name="LeadsTenant[sort]" id="sort_val" value="<?php echo $sort; ?>">
                        <input type="text" name="LeadsTenant[search]" class="form-control" value="<?= $search; ?>" placeholder="Search By Property name, Property owner name, Property address">
                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-search btn-danger selecttext"> <span class="glyphicon glyphicon-search"></span> <span class="label-icon"></span> </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-12 col-sm-12">


            <?php
            //echo "<pre>";print_r($model);echo "</pre>";die;
            echo GridView::widget([
                'dataProvider' => $dataProviderOwner,
                'filterModel' => $searchModelOwnerLeads,
                'rowOptions' => function ($model, $key, $index, $grid) {
                    $class = 'clickable-row';
                    return array('key' => $key, 'data-href' => Url::home(true) . 'external/applicantsdetails?id=' . base64_encode($key), 'class' => $class);
                },
                'summary' => '',
                'tableOptions' => ['class' => 'table table-bordered tableheadingrow'],
                'columns' => [
                    //   ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'full_name',
                        'label' => 'Applicant name',
                    ],
                    [
                        'attribute' => 'email_id',
                        'label' => 'Referred by advisor',
                        'format' => 'html',
                        'content' => function($model) {
                            return Yii::$app->userdata->getUserEmailById($model->reffered_by) . ' <br />' . Yii::$app->userdata->getFullNameById($model->reffered_by);
                        }
                    ],
                    [
                        'label' => 'Applicant contact details',
                        'format' => 'html',
                        'content' => function($model) {
                            return $model->contact_number . ' <br />' . $model->email_id;
                        }
                    ],
                    [
                        'label' => 'Applicant address',
                        'format' => 'html',
                        'content' => function($model) {
                            return $model->address . ',' . $model->address_line_2;
                        }
                    ],
                    [
                        'attribute' => 'ref_status',
                        'label' => 'Status',
                        'format' => 'html',
                        'content' => function($model) {
                            //return \app\controllers\SiteController::refType($model->ref_status);
                            return Yii::$app->userdata->getRefStatus($model->ref_status);
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
                            return Yii::$app->userdata->getLoginIdById(Yii::$app->userdata->getSalesFullNameById(2, $model->email_id));
                        }
                    ],
                //   ['class' => 'yii\grid\ActionColumn'],
                ]
            ]);
            ?>




        </div>
        <!-- /.col-lg-12 -->

    </div>
</div>




<div class="modal fade" id="myModal2" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">


        </div>

    </div>
</div>

