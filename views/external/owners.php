<?php
$this->title = 'PMS- Property Owner Leads';

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
    .pull-right a.btn.savechanges_submit {
        width: auto;
        padding: 2px 13px;
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
            <div class="col-lg-4 col-sm-9 pull-right">
                <a href="<?php echo Url::home(true); ?>external/createowner" class="btn savechanges_submit" data-toggle="modal" data-target="#myModal" style= "margin-top:15px;">Create Property Owner Lead</a>
            </div>
        <?php } ?>
        <div class="col-lg-12 col-sm-12">
            <div class="col-lg-3 col-sm-6 selectleft"> <a class="btn btn-default btn-select">
                    <input type="hidden" class="btn-select-input" id="" name="" value="" />
                    <span class="btn-select-value">Sort by</span> <span class='btn-select-arrow glyphicon glyphicon-chevron-down'></span>
                    <ul class="sort_ul">
                        <li class="selected">Sort by</li>
                        <li value="users.full_name">Owner name</li>
                        <li value="users.login_id">Owner email</li>
                        <li value="leads_owner.contact_number">Owner phone number</li>
                        <li value="owner_profile.address_line_1">Address</li>
                    </ul>
                </a> </div>

            <div class="col-lg-8 col-sm-5 selectleft">
                <form class="navbar-form navbar-search" action="" method="GET" role="search">
                    <div class="input-group">
                        <?php
                        $search = '';
                        if (isset($_REQUEST['LeadsOwner']['search'])) {
                            $search = $_REQUEST['LeadsOwner']['search'];
                        }
                        ?>
                        <input type="hidden" name="LeadsTenant[sort]" id="sort_val" value="<?php echo $sort; ?>">
                        <input type="text" name="LeadsOwner[search]" class="form-control" value="<?= $search; ?>" placeholder="Search by Owner Name, Owner Email, Owner Phone Number, Address">
                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-search btn-danger selecttext"> <span class="glyphicon glyphicon-search"></span> <span class="label-icon"></span> </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>







        <div class="col-lg-12 col-sm-12">
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
                'summary' => '',
                'rowOptions' => function ($model, $key, $index, $grid) {
                    $class = 'clickable-owner clickable-row';
                    return array('key' => $key, 'data-href' => Url::home(true) . 'external/ownerssdetails?id=' . base64_encode($key), 'class' => $class);
                },
                'tableOptions' => ['class' => 'table table-bordered tableheadingrow'],
                'columns' => [
                    //   ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'full_name',
                        'label' => 'Owner name',
                    ],
                    [
                        'attribute' => 'email_id',
                        'label' => 'Referred by advisor',
                        'format' => 'html',
                        'content' => function($model) {
                            return Yii::$app->userdata->getFullNameById($model->reffered_by);
                        }
                    ],
                    [
                        'label' => 'Contact details of property owner',
                        'format' => 'html',
                        'content' => function($model) {
                            return $model->contact_number . ' <br />' . $model->email_id;
                        }
                    ],
                    [
                        'attribute' => 'address',
                        'label' => 'Location/Address of property owner',
                        'format' => 'html',
                        'content' => function($model) {
                            $ownerId = Yii::$app->userdata->getUserIdByEmail($model->email_id);
                            $ownerProModel = \app\models\OwnerProfile::findOne(['owner_id' => $ownerId]);
                            if (empty($ownerProModel->city_name)) {
                                return $model->address . ' ' . $model->address_line_2;
                            } else {
                                return $model->address . ' ' . $model->address_line_2 . ', ' . $ownerProModel->city_name;
                            }
                        }
                    ],
                    [
                        'attribute' => 'ref_status',
                        'label' => 'Status',
                        'format' => 'html',
                        'content' => function($model) {
                            // return $model->ref_status;
                            return Yii::$app->userdata->getRefStatusById($model->ref_status);
                        }
                    ],
                    [
                        'label' => 'Next follow up date and time',
                        'format' => 'html',
                        'content' => function($model) {
                            if ($model->follow_up_date_time != '') {
                                return date('d-M-Y', strtotime($model->follow_up_date_time)) . ' <br />' . date('g:i a', strtotime($model->follow_up_date_time));
                            } else {
                                return "N/A";
                            }
                        }
                    ],
                    [
                        'label' => (Yii::$app->user->identity->user_type == 7) ? 'Operation Executive' : 'Sales Executive',
                        'format' => 'html',
                        'content' => function($model) {
                            if (Yii::$app->user->identity->user_type == 7) {
                                $ownerId = Yii::$app->userdata->getUserIdByEmail($model->email_id);
                                $ownerProModel = \app\models\OwnerProfile::findOne(['owner_id' => $ownerId]);
                                return (empty(Yii::$app->userdata->getLoginIdById($ownerProModel['operation_id']))) ? 'N/A' : Yii::$app->userdata->getLoginIdById($ownerProModel->operation_id);
                            } else if (Yii::$app->user->identity->user_type == 6) {
                                $ownerId = Yii::$app->userdata->getUserIdByEmail($model->email_id);
                                $ownerProModel = \app\models\OwnerProfile::findOne(['owner_id' => $ownerId]);
                                return (empty(Yii::$app->userdata->getLoginIdById($ownerProModel['sales_id']))) ? 'N/A' : Yii::$app->userdata->getLoginIdById($ownerProModel->sales_id);
                            }
                        }
                    ],
                ]
            ]);
            ?>



        </div>

        <div style="clear:both;"></div>

        <!-- /.col-lg-12 -->
    </div>
</div>





<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modalbg modalbgpopup">

        <!-- Modal content-->
        <div class="modal-content">


        </div>

    </div>
</div>
