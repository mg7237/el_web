<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
if(isset($_GET['LeadsTenant']['sort'])){
  $sort=$_GET['LeadsTenant']['sort'];
}
else{
  $sort='';
}
$this->title = 'Internal Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    </p>

        <div class="col-lg-12">
      <div class="col-lg-3 selectleft"> <a class="btn btn-default btn-select">
        <input type="hidden" value="Sort by" name="" id="" class="btn-select-input">
        <span class="btn-select-value">Sort by</span> <span class="btn-select-arrow glyphicon glyphicon-chevron-down"></span>
        <ul style="display: none;" class="sort_ul">
          <li class="selected">Sort by</li>
          <li value="users.id">Id</li>
          <li value="users.full_name">Name</li>
          <li value="users.login_id">Email</li>
          <li value="users.created_date">Created_date</li>
          
          <!-- <li>Status</li> -->
        </ul>
        </a> </div>
        <div class="col-lg-8 selectleft">
        <form class="navbar-form navbar-search" action="" method="GET" role="search">
          <div class="input-group">
        <?php
         $search = '';
         if(isset($_REQUEST['LeadsAdvisor']['search'])) {
          $search = $_REQUEST['LeadsAdvisor']['search'] ; 
         } 
         ?>
         <input type="hidden" name="LeadsTenant[sort]" id="sort_val" value="<?php echo $sort; ?>">
            <input type="text" name="LeadsAdvisor[search]" class="form-control" value="<?=  $search ;?>" placeholder="Search by Advisor name, Advisor email, Advisor phone number, address, status">
            <div class="input-group-btn">
              <button type="submit" class="btn btn-search btn-danger selecttext"> <span class="glyphicon glyphicon-search"></span> <span class="label-icon"></span> </button>
            </div>
          </div>
        </form>
      </div>
	</div>
    	 <div class="col-sm-12 col-md-12 col-xl-12">
			  <div class="col-sm-6 col-md-6 col-xl-6">
				
			 </div>
			  <div class="col-sm-6 col-md-6 col-xl-6">
                     <?= Html::a('Create Users', ['createadmin'], ['class' => 'btn btn-success pull-right']) ?>
			 </div>
		</div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
		'id' => 'admin-grid',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

         //   'id',
            'full_name',
            'login_id:email',
            [
				'attribute'=>'phone',
				'value'=>function($model){
					      return Yii::$app->userdata->getPhoneNumberById($model->id,$model->user_type);
						}
            ],
            [
				'attribute'=>'Created Date',
				'value'=>function($model){
					      return Date('d-m-Y',strtotime($model->created_date));
						}
            ],
            [
				'attribute'=>'role',
				'value'=>function($model){
					      return Yii::$app->userdata->getRoleByroleId($model->role);	
						}
            ],
            [
				'attribute'=>'manager_id',
				'value'=>function($model){
					      return Yii::$app->userdata->getManagerForUser($model->id);	
						}
            ],            
            
            [  
				'class' => 'yii\grid\ActionColumn',
				'contentOptions' => ['style' => 'width:100px;'],
				'header'=>'Actions',
				'template' => '{view} ',
				'buttons' => [

					//view button
					'view' => function ($url, $model) {
						return Html::a(''.($model->status == 1 ? 'Active' : 'Inative'), '#', [
									'url' => \Yii::$app->getUrlManager()->createUrl('admin/users/ajaxstatus') ,
									'title' => Yii::t('app', $model->status == 1 ? 'Active' : 'Inative'),
									'data-id' =>  $model->id  ,
									'data-st' =>  $model->status  ,
									'class'=>'btn btn-primary changestatusadmin btn-xs',                                  
						]);
					},
				],
		    ],
			[
				'class' => 'yii\grid\ActionColumn',
				//'template' => '{update} {delete}',
				'template' => '{update}',
				'buttons' => [
						'update' => function ($url,$model) {
								return Html::a(
										'<span class="glyphicon glyphicon-pencil"></span>', 
										\Yii::$app->getUrlManager()->createUrl('admin/users/updateadmin?id='.$model->id.'&type='.$model->user_type));
						},
				],
			]
        ],
    ]); ?>
</div>
<script type="text/javascript">
$(document).ready(function(){
    $(".btn-select-value").click(function(){
       $('ul.sort_ul').toggle();
    });
});


$(document).on('click','ul.sort_ul li',function(){
	var value=$(this).attr('value');
	$('#sort_val').val(value);
	$('.navbar-search').submit();
})
$(document).ready(function(){
	if($('#sort_val').val()!=''){
		$('ul.sort_ul li').removeClass('selected');
		$('ul.sort_ul li[value="'+$('#sort_val').val()+'"]').addClass('selected');
		$('.btn-select-value').text($('ul.sort_ul li[value="'+$('#sort_val').val()+'"]').text());
	}
	
})

</script>