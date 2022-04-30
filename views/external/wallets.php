<?php
$this->title = 'PMS- Wallets';

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
    <div class="col-lg-12 col-sm-12">
        <h4>Wallet Management</h4>
    </div>
    <div class="row">


        <div class="col-lg-12 col-sm-12">
            <!-- <div class="col-lg-3 col-sm-6 selectleft"> <a class="btn btn-default btn-select">
              <input type="hidden" class="btn-select-input" id="" name="" value="" />
              <span class="btn-select-value">Sort by</span> <span class='btn-select-arrow glyphicon glyphicon-chevron-down'></span>
              <ul class="sort_ul">
                <li class="selected">Sort by</li>
                <li value="users.full_name">Owner name</li>
                <li value="users.login_id">Owner email</li>
                <li value="leads_owner.contact_number">Owner phone number</li>
                <li value="owner_profile.address_line_1">Address</li>
              </ul>
              </a> </div> -->
            <!--
              <div class="col-lg-8 col-sm-5 selectleft">
                    <form class="navbar-form navbar-search" action="" method="GET" role="search">
                      <div class="input-group">
            <?php
            //  $search = '';
            //  if(isset($_REQUEST['LeadsOwner']['search'])) {
            //   $search = $_REQUEST['LeadsOwner']['search'] ;
            //  }
            ?>
                  <input type="hidden" name="LeadsTenant[sort]" id="sort_val" value="<?php //echo $sort;       ?>">
                        <input type="text" name="LeadsOwner[search]" class="form-control" value="<?//=  $search  ; ?>" placeholder="Search by Owner Name, Owner Email, Owner Phone Number, Address">
                        <div class="input-group-btn">
                          <button type="submit" class="btn btn-search btn-danger selecttext"> <span class="glyphicon glyphicon-search"></span> <span class="label-icon"></span> </button>
                        </div>
                      </div>
                    </form>
                  </div> -->

        </div>







        <div class="col-lg-12 col-sm-12">


            <?php
            echo GridView::widget([
                'dataProvider' => $dataProviderOwner,
                'filterModel' => $searchModelOwnerLeads,
                'summary' => '',
                'rowOptions' => function ($model, $key, $index, $grid) {
                    $class = 'clickable-owner clickable-row';
                    return array('key' => $key, 'data-href' => Url::home(true) . 'external/walletdetails?id=' . base64_encode($key), 'class' => $class);
                },
                'tableOptions' => ['class' => 'table table-bordered tableheadingrow'],
                'columns' => [
                    //   ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'user_id',
                        'label' => 'Applicant Id',
                    ],
                    [
                        'attribute' => 'user_id',
                        'label' => 'Applicant Name',
                        'format' => 'html',
                        'content' => function($model) {
                            return Yii::$app->userdata->getFullNameById($model->user_id);
                        }
                    ],
                    [
                        'attribute' => 'amount',
                        'label' => 'Amount Available',
                        'format' => 'html',
                        'content' => function($model) {
                            return "Rs. " . $model->amount;
                        }
                    ],
                // [
                //   'attribute'=>'address',
                //   'label'=>'Location/Address of property owner',
                //   'format'=>'html',
                //   'content'=>function($model){
                //     return $model->address.' '.$model->address_line_2;
                //   }
                // ],
                //  [
                //   'attribute'=>'ref_status',
                //   'label'=>'Status',
                //   'format'=>'html',
                //   'content'=>function($model){
                //     return Yii::$app->userdata->getRefStatusById($model->ref_status);
                //   }
                // ],
                //  [
                //   'label'=>'Next follow up date and time',
                //   'format'=>'html',
                //   'content'=>function($model){
                //     if( $model->follow_up_date_time != '01-01-1970 01:00'){
                //       return date('d-M-Y',strtotime( $model->follow_up_date_time )) .' <br />'. date('g:i a', strtotime( $model->follow_up_date_time ));
                //     }else{
                //       return "";
                //     }
                //
    //   }
                // ],
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
