<?php
$this->title = 'PMS- Wallet Detail';

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
                'tableOptions' => ['class' => 'table table-bordered tableheadingrow'],
                'columns' => [
                    //   ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'created_date',
                        'label' => 'Date',
                        'format' => 'html',
                        'content' => function($model) {
                            return Date('d-M-Y', strtotime($model->created_date));
                        }
                    ],
                    [
                        'attribute' => 'id',
                        'label' => 'Transaction Id',
                    ],
                    [
                        'attribute' => 'amount',
                        'format' => 'html',
                        'content' => function($model) {
                            return 'Rs. ' . $model->amount;
                        }
                    ],
                    [
                        'attribute' => 'transaction_type',
                        'format' => 'html',
                        'content' => function($model) {
                            $return = '';
                            switch ($model->transaction_type) {
                                CASE 0:
                                    $return = 'In Process';
                                    break;
                                CASE 1:
                                    $return = 'Added To Wallet';
                                    break;
                                CASE 2:
                                    $return = 'Adjusted Against Rent';
                                    break;
                                CASE 3:
                                    $return = 'Refund';
                                    break;
                            }
                            return $return;
                        }
                    ]
                ]
            ]);
            ?>



        </div>

        <div style="clear:both;"></div>
        <div class="col-md-3">
            <button type="button" class="btn savechanges_submit" data-toggle="modal" data-target="#myModal">Add Transaction</button>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <div class="col-lg-3 col-sm-12" style="padding-top:15px;">
        <?php $form = ActiveForm::begin(['action' => '#']); ?>
        <?= $form->field($accountDetails, 'account_holder_name')->textInput(['disabled' => true, 'class' => 'isCharacter form-control'])->label('Account holder name'); ?>
        <?= $form->field($accountDetails, 'bank_name')->textInput(['disabled' => true, 'class' => 'isCharacter form-control'])->label('Bank name'); ?>
        <?= $form->field($accountDetails, 'bank_branchname')->textInput(['disabled' => true, 'class' => 'isCharacter form-control'])->label('Branch name'); ?>
        <?= $form->field($accountDetails, 'bank_ifcs')->textInput(['disabled' => true, 'class' => 'isCharacter form-control'])->label('Bank IFSC'); ?>
        <?php
        if ($userType == '2') {
            echo $form->field($accountDetails, 'bank_account_number')->textInput(['disabled' => true, 'class' => 'isCharacter form-control'])->label('Account number');
        } else {
            echo $form->field($accountDetails, 'account_number')->textInput(['disabled' => true, 'class' => 'isCharacter form-control'])->label('Account number');
        }
        ?>

        <?php ActiveForm::end(); ?>

    </div>

</div>





<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modalbg modalbgpopup">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header loginheader">
                <h4 class="modal-title modal_signin signuptext" id="myModalLabel">Add Transaction</h4>

                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($searchModelOwnerLeads, 'transaction_type')->dropDownList(['2' => 'Adjusted Against Rent', '3' => 'Refund To Bank'], ['prompt' => 'Select Transaction Type', 'required' => true]); ?>
            <?= $form->field($searchModelOwnerLeads, 'amount')->textInput(['required' => true]); ?>
            <div class="row advisorbox">
                <div class="col-md-6">
                    <button type="submit" class="btn savechanges_submit_contractaction">Save</button>	 </div>
                <div class="col-md-6">
                    <button type="button" class="btn savechanges_submit cancel_confirm" data-dismiss="modal">Cancel</button>
                </div>
            </div>
            <?php ActiveForm::end(); ?>

        </div>

    </div>
</div>


<style>
    .modal-header.loginheader :before,.modal-header.loginheader :after{
        margin-top: 15px;
    }
    .modal-header.loginheader .close{
        top: 6px;
        right: 7px;
        position: absolute;
    }
</style>
