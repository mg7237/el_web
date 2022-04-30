<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
//print_r($model);die;
$this->title = $model->link;
$this->params['breadcrumbs'][] = ['label' => 'Advertisements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
//echo "hello";die;
?>
<div class="advertisements-view">

    <h1><?= Html::encode($this->title) ?></h1>

	
	<?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
			'banner',
            'link',
            [                      // the owner name of the model
            'label' => 'Created Date',
            'value' => $model->created_date,
            ],
            [                      // the owner name of the model
            'label' => 'Updated Date',
            'value' => $model->updated_date,
            ],
            [                      
            'label' => 'Type',
            'value' => Yii::$app->userdata->getAdvertisementType($model->id)
            ],
        ],
    ]) ?>

</div>
