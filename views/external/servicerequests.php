<?php
$this->title = 'PMS- Service Requests';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\grid\GridView;

/* echo "<pre>";
  print_r($model);
  die; */

if (isset($_GET['Service']['sort'])) {
    $sort = $_GET['Service']['sort'];
} else {
    $sort = '';
}
?>
<style>
    .filters{
        display:none ;
    }
    a.btn.savechanges_submit_contractaction.rightbtn.topMargin {
        width: auto;
        padding: 2px 13px;
    }
</style>
<div id="page-wrapper">
    <div class="row">

        <div class="col-lg-12 col-sm-12">
            <?php if (Yii::$app->session->hasFlash('success')) { ?>
                <div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                    <?= Yii::$app->session->getFlash('success'); ?>
                </div>
            <?php } ?>

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
            <!-- <div class="col-lg-8 selectleft">
             <form class="navbar-form navbar-search" action="" method="GET" role="search">
               <div class="input-group">
            <?php
            // $search = '';
            // if(isset($_REQUEST['Service']['search'])) {
            // $search = $_REQUEST['Service']['search'] ;
            // }
            ?>
     
                 <input type="text" name="Service[search]" class="form-control" value="<?//= $search ;  ?>" placeholder="Search by name, email, phone number, address, status">
                 <div class="input-group-btn">
                   <button type="submit" class="btn btn-search btn-danger selecttext"> <span class="glyphicon glyphicon-search"></span> <span class="label-icon"></span> </button>
                 </div>
               </div>
             </form>
           </div> -->
            <div class="col-lg-4 col-sm-8 pull-right">
                <a href="<?php echo Url::home(true); ?>external/createservice" class="btn savechanges_submit_contractaction rightbtn topMargin" >Create Service Request</a>

            </div>
        </div>
        <div class="col-lg-12 col-sm-12">
            <?php
//            echo GridView::widget([
//                'dataProvider' => $dataProviderOwner,
//                'filterModel' => $searchModelOwnerLeads,
//                'rowOptions' => function ($model, $key, $index, $grid) {
//                    $class = 'clickable-row';
//                    return array('key' => $key, 'data-href' => Url::home(true) . 'external/servicedetail?id=' . base64_encode($key), 'class' => $class);
//                },
//                'summary' => '',
//                'tableOptions' => ['class' => 'table table-bordered tableheadingrow'],
//                'columns' => [
//                    //   ['class' => 'yii\grid\SerialColumn'],
//                    [
//                        'attribute' => 'user_type',
//                        'label' => 'User Type',
//                        'format' => 'html',
//                        'content' => function($model) {
//                            return Yii::$app->userdata->getUserTypeByUserId($model->created_by);
//                        }
//                    ],
//                    [
//                        'attribute' => 'created_by',
//                        'label' => 'ID Of The Property Owner Or Tenant',
//                    ],
//                    [
//                        'attribute' => 'full_name',
//                        'label' => 'Name Of The Person',
//                        'format' => 'html',
//                        'content' => function($model) {
//                            return Yii::$app->userdata->getFullNameById($model->created_by);
//                        }
//                    ],
//                    [
//                        'attribute' => 'request_type',
//                        'label' => 'Type',
//                        'format' => 'html',
//                        'content' => function($model) {
//                            return Yii::$app->userdata->getRequestTypeName($model->request_type);
//                        }
//                    ],
//                    [
//                        'attribute' => 'title',
//                        'label' => 'Subject',
//                    ],
//                    [
//                        'attribute' => 'status',
//                        'label' => 'Status',
//                        'format' => 'html',
//                        'content' => function($model) {
//                            return Yii::$app->userdata->getStatusNameById($model->status);
//                        }
//                    ],
//                    [
//                        'attribute' => 'created_date',
//                        'label' => 'Created Date',
//                        'format' => 'html',
//                        'content' => function($model) {
//                            return Date('d-M-Y', strtotime($model->created_date));
//                        }
//                    ],
//                    [
//                        'attribute' => 'operation_id',
//                        'label' => 'Operation Name',
//                        'format' => 'html',
//                        'content' => function($model) {
//                            return Yii::$app->userdata->getAssignedSalesPerson($model->created_by);
//                        }
//                    ]
//                ]
//            ]);
            ?>


            <table class="table table-bordered " cellpadding="5"> 
                <thead>
                    <tr> 
                        <th>Serial no.</th>
                        <th>User Type</th>
                        <th>Name</th>
                        <th>Request Type</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Created Date</th>
                        <th>Operation Name</th>
                    </tr>
                </thead> 
                <tbody>

                    <?php
                    $i = 1;
                    if ($getAllRequest) {
                        foreach ($getAllRequest as $request) {
                            ?>  
                            <tr class="clickable-row" data-href="<?= Url::home(true) . 'external/servicedetail?id=' . base64_encode($request['id']) ?>">
                                <th><?= $i; ?></th>
                                <th><?= Yii::$app->userdata->getUserTypeByTypeId($request['client_type']); ?></th>
                                <th><?= Yii::$app->userdata->getUserNameById($request['client_id']); ?></th>
                                <td><?= $request['request_type']; ?></td>
                                <td><?= $request['title']; ?></td>
                                <td><?= $request['status'] ?></td>
                                <td><?= date('d-M-Y', strtotime($request['created_date'])); ?></td> 
                                <th><?= Yii::$app->userdata->getUserNameById($request['operated_by']); ?></th>
                            </tr> 

                            <?php
                            $i++;
                        }
                    } else {
                        ?>     
                        <tr> <td colspan="6"> No Request found</td></tr>
                    <?php } ?>     
                </tbody> 
            </table>

        </div>


    </div>
</div>