<?php
$this->title = 'PMS- Properties';

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
</style>
<div id="page-wrapper">
    <div class="row">

        <div class="col-lg-12">
            <?php if (Yii::$app->session->hasFlash('notice')) { ?>
                <div class="alert alert-error fade in alert-dismissable" style="margin-top:18px;">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                    <strong>Notice!</strong> <?= Yii::$app->session->getFlash('notice'); ?>
                </div>   
            <?php } elseif (Yii::$app->session->hasFlash('success')) {
                ?>
                <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                    <?= Yii::$app->session->getFlash('success'); ?>
                </div>   
            <?php }
            ?>	

            <!-- <div class="col-lg-3 selectleft"> <a class="btn btn-default btn-select">
              <input type="hidden" class="btn-select-input" id="" name="" value="" />
              <span class="btn-select-value">Sort by</span> <span class='btn-select-arrow glyphicon glyphicon-chevron-down'></span>
              <ul class="sort_ul">
                <li class="selected">Sort by</li>
                <li value="users.full_name">Applicant name</li>
                <li value="users.login_id">Applicant email</li>
                <li value="leads_tenant.contact_number">Applicant phone number</li>
                <li value="applicant_profile.address_line_1">Address</li>
                <!- <li value="status">Status</li> ->
              </ul>
              </a> </div> -->
            <div class="col-lg-8 selectleft">
                <form class="navbar-form navbar-search" action="" method="GET" role="search">
                    <div class="input-group">
                        <?php
                        $search = '';
                        if (isset($_REQUEST['Properties']['search'])) {
                            $search = $_REQUEST['Properties']['search'];
                        }
                        ?>

                        <input type="text" name="Properties[search]" class="form-control" value="<?= $search; ?>" placeholder="Search by name, email, phone number, address, status">
                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-search btn-danger selecttext"> <span class="glyphicon glyphicon-search"></span> <span class="label-icon"></span> </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-12">


            <?php
            echo GridView::widget([
                'dataProvider' => $dataProviderOwner,
                'filterModel' => $searchModelOwnerLeads,
                'rowOptions' => function ($model, $key, $index, $grid) {
                    $class = 'clickable-row';
                    return array('key' => $key, 'data-href' => (!Yii::$app->userdata->getPropertyBookedStatus($model->id)) ? Url::home(true) . 'operations/propertiesdetail?id=' . base64_encode($key) : 'javascript:', 'class' => $class);
                },
                'summary' => '',
                'tableOptions' => ['class' => 'table table-bordered tableheadingrow'],
                'columns' => [
                    //   ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'id',
                        'label' => 'Property Id',
                    ],
                    [
                        'attribute' => 'property_name',
                        'label' => 'Property Name',
                    ],
                    [
                        'attribute' => 'owner_id',
                        'label' => 'Owner Name',
                        'format' => 'html',
                        'content' => function($model) {
                            return Yii::$app->userdata->getFullNameById($model->owner_id);
                        }
                    ],
                    [
                        'attribute' => 'address_line_1',
                        'label' => 'Property Address',
                        'format' => 'html',
                        'content' => function($model) {
                            return $model->address_line_1 . ',' . $model->address_line_2 . '<br/>' . Yii::$app->userdata->getRegionName($model->region) . ',' . Yii::$app->userdata->getStateName($model->state) . ',' . Yii::$app->userdata->getCityName($model->city);
                        }
                    ],
                    // [
                    // 	'attribute'=>'property_type',
                    // 	'label'=>'Property Type',
                    // 	'format'=>'html',
                    // 	'content'=>function($model){
                    // 		return Yii::$app->userdata->getPropertyTypeById($model->property_type);
                    // 	}
                    // ],
                    [
                        'attribute' => 'agreement_type',
                        'label' => 'Lease',
                        'format' => 'html',
                        'content' => function($model) {
                            return Yii::$app->userdata->getPropertyLeaseType($model->id);
                        }
                    ],
                    [
                        'attribute' => 'contract_start_date',
                        'label' => 'Contract Start Date',
                        'format' => 'html',
                        'content' => function($model) {
                            return Yii::$app->userdata->getPropertyStartDate($model->id);
                        }
                    ],
                    [
                        'attribute' => 'contract_end_date',
                        'label' => 'Contract End Date',
                        'format' => 'html',
                        'content' => function($model) {
                            return Yii::$app->userdata->getPropertyEndDate($model->id);
                        }
                    ],
                // [
                // 	'attribute'=>'status',
                // 	'label'=>'Status',
                // 	'format'=>'html',
                // 	'content'=>function($model){
                // 		if(Yii::$app->userdata->getPropertyBook($model->id,$model->property_type)){
                // 			return 'Booked';
                // 		}
                // 		else{
                // 			if($model->status==0){
                // 				return 'Unlisted';
                // 			}
                // 			else{
                // 				return 'Listed';
                // 			}
                // 		}
                // 		// return Yii::$app->userdata->getPropertyTypeById($model->property_type);
                // 	}
                // ],
                // [
                // 	'label'=>'Applicant contact details',
                // 	'format'=>'html',
                // 	'content'=>function($model){
                // 		return $model->contact_number.' <br />'. $model->email_id;
                // 	}
                // ],
                //   [
                // 	'attribute'=>'ref_status',
                // 	'label'=>'Status',
                // 	'format'=>'html',
                // 	'content'=>function($model){
                // 		return \app\controllers\SiteController::refType($model->ref_status);
                // 	}
                // ],
                //  [
                // 	'label'=>'Next follow up date and time',
                // 	'format'=>'html',
                // 	'content'=>function($model){
                // 		return date('d-m-Y',strtotime( $model->follow_up_date_time )) .' <br />'. date('g:i a', strtotime( $model->follow_up_date_time ));
                // 	}
                // ],
                // [
                // 	'label'=>'Assigned Sales Person Id',
                // 	'format'=>'html',
                // 	'content'=>function($model){
                // 		return  Yii::$app->userdata->getLoginIdById(Yii::$app->userdata->getSalesFullNameById(2,$model->email_id ));
                // 	}
                // ],
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

