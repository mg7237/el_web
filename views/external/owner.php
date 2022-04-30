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
</style>


<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-4 col-sm-9 pull-right" style="display: none;">
            <a href="<?php echo Url::home(true); ?>external/createowner" class="btn savechanges_submit" data-toggle="modal" data-target="#myModal" style= "margin-top:15px;">Create Property Owner Lead</a>
        </div>
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
                        <input type="text" name="LeadsOwner[search]" class="form-control" value="<?= $search; ?>" placeholder="Search By Owner Name, Owner Email, Owner Phone Number, Address">
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

            <?=
            GridView::widget([
                'dataProvider' => $dataProviderOwner,
                'filterModel' => $searchModelOwnerLeads,
                'summary' => '',
                'rowOptions' => function ($model, $key, $index, $grid) {
                    $class = 'clickable-owner clickable-row';
                    return array('key' => $key, 'data-href' => Url::home(true) . 'external/ownersdetails?id=' . base64_encode($model->owner_id), 'class' => $class);
                },
                'tableOptions' => ['class' => 'table table-bordered tableheadingrow'],
                'columns' => [
                    //   ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'owner_id',
                        'label' => 'Property Owner Id',
                    ],
                    [
                        'attribute' => 'full_name',
                        'label' => 'Property Owner Name',
                        'format' => 'html',
                        'content' => function($model) {
                            return Yii::$app->userdata->getFullNameById($model->owner_id);
                        }
                    ],
                    [
                        'attribute' => 'address',
                        'label' => 'Address',
                        'format' => 'html',
                        'content' => function($model) {
                            return Yii::$app->userdata->getOwnerAddress($model->owner_id);
                        }
                    ],
                    // [
                    //   'attribute'=>'email_id',
                    //   'label'=>'Referred by advisor',
                    //   'format'=>'html',
                    //   'content'=>function($model){
                    //     return Yii::$app->userdata->getUserEmailById($model->reffered_by ) .' <br />'. Yii::$app->userdata->getFullNameById( $model->reffered_by );
                    //   }
                    // ],
                    [
                        'label' => 'Contact details of property owner',
                        'format' => 'html',
                        'content' => function($model) {
                            return $model->phone . "<br/>" . Yii::$app->userdata->getUserEmailById($model->owner_id);
                        }
                    ],
                    [
                        'label' => 'Operation Person',
                        'format' => 'html',
                        'content' => function($model) {
                            return Yii::$app->userdata->getFullNameById($model->operation_id);
                        }
                    ],
                    [
                        'label' => 'Sales Person',
                        'format' => 'html',
                        'content' => function($model) {
                            return Yii::$app->userdata->getFullNameById($model->sales_id);
                        }
                    ],
                // [
                //   'attribute'=>'address',
                //   'label'=>'Location/Address of property owner',
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
                //   'label'=>'Next follow up date and time',
                //   'format'=>'html',
                //   'content'=>function($model){
                //     return date('d-m-Y',strtotime( $model->follow_up_date_time )) .' <br />'. date('g:i a', strtotime( $model->follow_up_date_time ));
                //   }
                // ],
                // [
                //  'label'=>'Assigned Sales Person Name',
                //  'format'=>'html',
                //  'content'=>function($model){
                //    return  Yii::$app->userdata->getFullNameById(Yii::$app->userdata->getSalesFullNameById(4,$model->email_id ));
                //  }
                // ],
                //   ['class' => 'yii\grid\ActionColumn'],
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

