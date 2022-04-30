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
            <div class="col-lg-8 selectleft">
                <form class="navbar-form navbar-search" action="" method="GET" role="search">
                    <div class="input-group">
                        <?php
                        $search = '';
                        if (isset($_REQUEST['Properties']['search'])) {
                            $search = $_REQUEST['Properties']['search'];
                        }
                        ?>

                        <input type="text" name="Properties[search]" class="form-control" value="<?= $search; ?>" placeholder="Search By Name, Email, Phone Number, Address">
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
                    return array('key' => $key, 'data-href' => Url::home(true) . 'external/propertylistingdetails?id=' . base64_encode($key), 'class' => $class);
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
                        'attribute' => 'address_line_1',
                        'label' => 'Property Owner Details',
                        'format' => 'html',
                        'content' => function($model) {
                            return Yii::$app->userdata->getFullNameById($model->owner_id) . "<br/>" . $model->address_line_1 . ',' . $model->address_line_2 . ',' . Yii::$app->userdata->getRegionName($model->region) . ',' . Yii::$app->userdata->getStateName($model->state) . ',' . Yii::$app->userdata->getCityName($model->city) . '<br/>' . Yii::$app->userdata->getNumberMobile($model->owner_id) . '<br/>' . Yii::$app->userdata->getUserEmailById($model->owner_id);
                        }
                    ],
                    [
                        'attribute' => 'property_type',
                        'label' => 'Property Type',
                        'format' => 'html',
                        'content' => function($model) {
                            return Yii::$app->userdata->getPropertyTypeById($model->property_type);
                        }
                    ],
                    [
                        'attribute' => 'status',
                        'label' => 'Status',
                        'format' => 'html',
                        'content' => function($model) {
                            // if(Yii::$app->userdata->getPropertyBook($model->id,$model->property_type)){
                            // return 'Booked';
                            // }
                            // else{

                            $status = Yii::$app->userdata->getListingStatus($model->id);
                            if ($status == 0) {
                                return 'Unlisted';
                            } else {
                                return 'Listed';
                            }
                            // }
                            // return Yii::$app->userdata->getPropertyTypeById($model->property_type);
                        }
                    ],
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

