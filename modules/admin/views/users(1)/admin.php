<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Internal Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    </p>
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
            //'phone',
    //         [
				// 'attribute'=>'phone',
				// 'value'=>function($model){
				// 	      return Yii::$app->userdata->getPhoneById($model->id,$model->user_type);	
				// 		}
    //         ],
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
            // 'address',
            // 'lat',
            // 'lon',
            // 'state',
            // 'city',
            // 'zipcode',
            // 'ad_company',
            // 'ad_properties',
            // 'ow_interested',
            // 'ow_comments:ntext',
            // 'auth_key',
            // 'password_reset_token',
            // 'role',
            // 'created',
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
				'template' => '{update} {delete}',
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