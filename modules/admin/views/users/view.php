<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

$this->title = $model->full_name;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'full_name',
            'password',
            'login_id:email',
           
            [                      // the owner name of the model
            'label' => 'User Type',
            'value' => Yii::$app->userdata->getUsertypeByValue($model->user_type),
            ],
        ],
    ]) ?>

</div>
